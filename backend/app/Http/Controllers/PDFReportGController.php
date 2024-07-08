<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\HistoryChange;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PDFReportGController extends Controller
{
    //
    public function reportGeneral(Request $request)
    {

        $brand = $request->input('brand');

        if ($brand != 'TODAS LAS MARCAS' && $brand != '') {
            $data = HistoryChange::getEquipmentData($brand);
        } else if ($brand = 'TODAS LAS MARCAS' || $brand = '') {

            $history = HistoryChange::with([
                'equipment.brand', 'equipment.state', 'equipment.type', 'locations', 'typeActions', 'dependencys'
            ])->where('type_action_id', '=', '2')->get();

            $data = $history->map(function ($history) {
                return [
                    'equipment_id' => $history->equipment->id,
                    'assignment_date' => $history->start_date,
                    'brand' => $history->equipment->brand->name,
                    'model' => $history->equipment->model,
                    'serial_number' => $history->equipment->serial_number,
                    'number_active' => $history->equipment->number_active,
                    'type' => $history->equipment->type->name,
                    'state' => $history->equipment->state->name,
                    'description' => $history->description,
                    'type_action' => $history->typeActions->name,
                    'location' => $history->locations->name,
                    'dependency' => $history->dependencys->name,

                ];
            });
        }

        $pdf = PDF::loadView('ReportGeneral', compact('data'));
        return $pdf->stream('ReportGeneral.pdf');
    }
}
