<?php

namespace App\Http\Controllers;

use Encrypt;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PDFData;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Models\HistoryChange;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PDFDataController extends Controller
{

    public function individualReport($serial_number)
    {
        Log::info("Serial".$serial_number);

        $data = PDFData::individualReport($serial_number);
        Log::info($data);

        $pdf = PDF::loadView('IndividualReport', compact('data'));
        return $pdf->stream('IndividualReport.pdf');
    }

    public function locationReport(Request $request)
    {

        $data = PDFData::locationReport($request);

        $pdf = PDF::loadView('LocationReport', compact('data'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('LocationReport.pdf');
    }

    public function allProducts(Request $request)
    {
        $search = [
            'brand' => ($request['brand'] == -1) ? -1 : (Encrypt::decryptValue($request['brand'])),
            'brand_condition' => ($request['brand'] == -1) ? '>' : '=',
            'type' => ($request['type'] == -1) ? -1 : (Encrypt::decryptValue($request['type'])),
            'type_condition' => ($request['type'] == -1) ? '>' : '=',
            'location' => ($request['location'] == -1) ? -1 : (Encrypt::decryptValue($request['location'])),
            'location_condition' => ($request['location'] == -1) ? '>' : '='
        ];
  
        $data = [];
        $equipments = PDFData::allProducts($search);

        foreach ($equipments as $key => $value) {
            $id = $value->id;
            $descriptions = PDFData::technicalDescriptions($id);
            $text = "";
            foreach ($descriptions as $item) {
                $text .= "{$item['item']}: {$item['value']} \n";
            }
            $equipment = [
                'id' => $id,
                'type' => $value->type,
                'brand' => $value->brand,
                'model' => $value->model,
                'number_active' => $value->number_active,
                'serial_number' => $value->serial_number,
                'state' => $value->state,
                'location' => $value->location,
                'descriptions' => $text
            ];

            $data[] = $equipment;
        }

        Log::info($data);
        $pdf = PDF::loadView('AllProductsReport', compact('data'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Tipos de equipos.pdf');
    }

    public function reportGeneral(Request $request)
    {
        $brand = $request->brand;
        $history = PDFData::getEquipmentData($brand);
        $data = [];

        foreach ($history as $equipment) {

            $detail = PDFData::technicalDescriptions($equipment->id);
            $component = "";
            foreach ($detail as $item) {
                $component .= "{$item['item']}: {$item['value']} \n";
            }

            $equipmentData  = [
                "id" => $equipment->id,
                "model" => $equipment->model,
                "brand" => $equipment->brand,
                "state" => $equipment->state,
                "type" => $equipment->type,
                "type_action" => $equipment->type_action,
                "location" => $equipment->location,
                "dependency" => $equipment->dependency,
                "number_active" => $equipment->number_active,
                "serial_number" => $equipment->serial_number,
                "description" => $equipment->description,
                "detail" => $component,
                "assignment_date" => $equipment->assignment_date,
                "request" => $brand
            ];

    
          /*   $details  = PDFData::getTechniacalDetailData($equipment->id);

            $technicalDetails = [];

            foreach ($details as $detail) {
                $technicalDetails[] = [
                    "component" => $detail->component,
                    "capacity" => $detail->capacity,
                ];
            }

            $equipmentData['technical_details'] = $technicalDetails;*/

            $data[] = $equipmentData;
        }

        $pdf = PDF::loadView('ReportGeneral', compact('data'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('ReportGeneral.pdf');
    }

    public function availableEquipment(Request $request){
        $data = PDFData::getAvailableEquipment([
            Carbon::parse($request->start_date)->startOfDay(), 
            Carbon::parse($request->end_date)->endOfDay()
        ]);       
        // Log::info($data);
        $pdf = PDF::loadView('AvailableEquipmentReport', compact('data'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('AvailableEquipmentReport.pdf');
    }
}
