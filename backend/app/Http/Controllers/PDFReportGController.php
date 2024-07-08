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

        $history = HistoryChange::getEquipmentData($brand);
        $equipments = [];

        foreach ($history as $equipment) {

            $equipmentData  = [
                "id" => $equipment->id,
                "brand" => $equipment->brand,
                "state" => $equipment->state,
                "type" => $equipment->type,
                "type_action" => $equipment->type_action,
                "location" => $equipment->location,
                "dependency" => $equipment->dependency,
                "number_active" => $equipment->number_active,
                "serial_number" => $equipment->serial_number,
                "description" => $equipment->description,
                "assignment_date" => $equipment->assignment_date,
            ];
            $details  = HistoryChange::getTechniacalDetailData($equipment->id);

            $technicalDetails = [];

            foreach ($details as $detail) {
                $technicalDetails[] = [
                    "component" => $detail->component,
                    "capacity" => $detail->capacity,
                ];
            }

            $equipmentData['technical_details'] = $technicalDetails;

            $equipments[] = $equipmentData;
        }

        return  response()->json([
            "data: " =>  $equipments
        ]);
    }
}
