<?php

namespace App\Models;

use App\Models\Equipment;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PDFData extends Model
{
    use HasFactory;

    public static function typeReport($search)
    {

        $data = Equipment::select()
            ->join('brand', 'brand.id', '=', 'equipment.brand_id')
            ->where('brand_id', $search['brand_condition'], $search['brand'])
            ->where('equipment_type_id', $search['type_condition'], $search['type'])
            ->get();

        return $data;
    }

    public static function locationReport($search)
    {
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
            ->where('location.name', 'like', $search->location)
            ->where('brand.name', 'like', $search->brand)
            ->where('equipment_type.name', 'like', $search->type)
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

        return $data;

    }

}
