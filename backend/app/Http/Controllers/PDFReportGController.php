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
        $brandId = $request->input('brand');
        echo ($brandId);
        echo ('CONTROLADOR AQUI');

        if ($brandId == 'TODAS LAS MARCAS') {
            echo ('si entra EN GENERAL');

            $history = HistoryChange::with([
                'equipment.brand', 'equipment.state', 'equipment.type', 'locations', 'typeActions', 'dependencys'
            ])->where('type_action_id', '=', '2')->get();

            $data = $history->map(function ($history) {

                return [
                    'equipment_id' => $history->equipment->id,
                    'brand_id' => $history->equipment->brand->id,
                    'assignment_date' => $history->start_date,
                    'marca' => $history->equipment->brand->name,
                    'modelo' => $history->equipment->model,
                    'serial' => $history->equipment->serial_number,
                    'n_active' => $history->equipment->number_active,
                    'type' => $history->equipment->type->name,
                    'state' => $history->equipment->state->name,
                    'type_action' => $history->typeActions->name,
                    'locations' => $history->locations->name,
                    'dependency' => $history->dependencys->name,
                ];
            });
            $pdf = PDF::loadView('ReportGeneral', compact('data'));
            return $pdf->stream('ReportGeneral.pdf');
        } else {
            echo ('si entra POR MARCA');
            $query = HistoryChange::with([
                'equipment.brand', 'equipment.state', 'equipment.type', 'locations', 'typeActions', 'dependencys'
            ])->where('type_action_id', '=', '2');

            if ($brandId) {
                $query->whereHas('equipment', function ($q) use ($brandId) {
                    $q->where('brand_id', $brandId);
                });
            }

            $history = $query->get();

            $data = $history->map(function ($history) {

                return [
                    'equipment_id' => $history->equipment->id,
                    'brand_id' => $history->equipment->brand->id,
                    'assignment_date' => $history->start_date,
                    'marca' => $history->equipment->brand->name,
                    'modelo' => $history->equipment->model,
                    'serial' => $history->equipment->serial_number,
                    'n_active' => $history->equipment->number_active,
                    'type' => $history->equipment->type->name,
                    'state' => $history->equipment->state->name,
                    'type_action' => $history->typeActions->name,
                    'locations' => $history->locations->name,
                    'dependency' => $history->dependencys->name,
                ];
            });
            $pdf = PDF::loadView('ReportGeneral', compact('data'));
            echo ( $data);
            return $pdf->stream('ReportGeneral.pdf');
        }

        //return $pdf->stream('ReportGeneral.pdf');
    }
}
