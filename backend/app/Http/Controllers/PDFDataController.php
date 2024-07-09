<?php

namespace App\Http\Controllers;

use Encrypt;
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

        $data = PDFData::individualReport($serial_number);
        $pdf = PDF::loadView('IndividualReport', compact('data'));
        return $pdf->stream('IndividualReport.pdf');
    }

    public function locationReport(Request $request)
    {
        
        $data = PDFData::locationReport($request);
        $pdf = PDF::loadView('LocationReport', compact('data'));
        return $pdf->stream('LocationReport.pdf');

    }

    public function typeReport(Request $request)
    {
        $search = [
            'brand' => ($request['brand']==-1)?-1:(Encrypt::decryptValue($request['brand'])),
            'brand_condition' => ($request['brand']==-1)?'>':'=',
            'type' => ($request['type']==-1)?-1:(Encrypt::decryptValue($request['type'])),
            'type_condition' => ($request['type']==-1)?'>':'='
        ];

        $data = [];
        $equipments = PDFData::typeReport($search);

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
                'state' => $value->state,
                'location' => PDFData::getEquipmentLocation($id),
                'descriptions' => $text
            ];  

            $data[] = $equipment;            
        }

        // Log::info($data);
        $pdf = PDF::loadView('TypeReport', compact('data'));  
        $pdf->setPaper('A4', 'landscape');      

        return $pdf->stream('Tipos de equipos.pdf');
    }
}
