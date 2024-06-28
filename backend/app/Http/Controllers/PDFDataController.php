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
    public function locationReport(Request $request)
    {
        Log::info($request);
        $data =  HistoryChange::select('history_change.*', 
        'type_action.*', 
        'equipment_id.*', 
        'process_state.*', 
        'location.*', 
        'dependency.*', 
        'history_change.id as id',
        // Ubicacion
        'location.name as location_id',
        'dependency.name as dependency_id',
        //Equipo1
        'equipment_id.serial_number as equipment_id',
        'equipment_id.model as model1',
        'equipment_type.name as type1',
        'brand.name as brand',
        'process_state.name as state_id',
        'type_action.name as type_action_id'
        )
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            // Equipo principal
            ->join('equipment as equipment_id', 'history_change.equipment_id', '=', 'equipment_id.id')
            ->join('equipment_type as equipment_type', 'equipment_id.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment_id.brand_id', '=', 'brand.id')
 

            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            ->join('location', 'history_change.location_id', '=', 'location.id')
            ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
            ->where('location.name', 'like', $request->location)
            ->where('brand.name', 'like', $request->brand)
            ->where('equipment_type.name', 'like', $request->type)

            
            // ->where('location.name', 'like', $search)
            // ->orWhere('dependency.name', 'like', $search)
            // ->orWhere('process_state.name', 'like', $search)
            // ->orWhere('type_action.name', 'like', $search)
            // ->orderBy("history_change.$sortBy", $sort)

            ->get();

            $data->each(function($item){
                $users = User::Join('history_user_detail', 'users.id','=', 'history_user_detail.user_id')
                ->where('history_user_detail.history_change_id', $item->id)
                ->pluck('users.name')
                ->toArray();
                $item->users = $users;
            });

            $data->each(function($item){
                $technician = User::leftJoin('history_tech', 'users.id','=', 'history_tech.user_tech_id')
                ->where('history_tech.history_change_id', $item->id)
                ->pluck('users.name')
                ->toArray();
                $item->technician = $technician;
            });



        $pdf = PDF::loadView('LocationReport', compact('data'));
        return $pdf->stream('LocationReport.pdf');

    }

    public function typeReport(Request $request)
    {
        Log::info($request['brand']);
        $search = [
            'brand' => ($request['brand']==-1)?-1:(Encrypt::decryptValue($request['brand'])),
            'brand_condition' => ($request['brand']==-1)?'>':'=',
            'type' => ($request['type']==-1)?-1:(Encrypt::decryptValue($request['type'])),
            'type_condition' => ($request['type']==-1)?'>':'='
        ];
        Log::info($search);
        $data = PDFData::typeReport($search);

        $pdf = PDF::loadView('TypeReport', compact('data'));
        return $pdf->stream('Tipos de equipos.pdf');
    }

}
