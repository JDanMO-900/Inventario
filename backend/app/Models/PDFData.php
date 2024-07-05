<?php

namespace App\Models;

use App\Models\Equipment;
use App\Models\HistoryChange;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PDFData extends Model
{
    use HasFactory;

    public static function typeReport($search){
        $data = Equipment::select(
        'equipment.id', 
        'equipment_type.name as type',
        'brand.name as brand', 
        'equipment.model', 
        'equipment.number_active',
        'equipment_state.name as state',
        'equipment.equipment_state_id as location')
        ->join('brand', 'brand.id', '=', 'equipment.brand_id')
        ->join('equipment_type', 'equipment_type.id', '=', 'equipment.equipment_type_id')
        ->join('equipment_state', 'equipment_state.id', '=', 'equipment.equipment_state_id')
        ->where('brand_id', $search['brand_condition'], $search['brand'])
        ->where('equipment_type_id', $search['type_condition'], $search['type'])
        ->orderBy('equipment_type.name','ASC')
        ->get();

        return $data;
    }

    public static function technicalDescriptions($id){
        $data = EquipmentDetail::select('equipment_detail.attribute as value','technical_description.name as item')
        ->join('technical_description', 'technical_description.id', '=', 'equipment_detail.technical_description_id')
        ->where('equipment_id', $id)
        ->orderBy('technical_description.name','ASC')
        ->get();

        return $data;
    }

    public static function getEquipmentLocation($id){
        $data = HistoryChange::select('location.name as location')
        ->join('location', 'location.id', '=', 'history_change.location_id')
        ->where('history_change.equipment_id', $id)
        ->whereIn('history_change.type_action_id', ['2', '3', '4'])
        ->whereNull('history_change.end_date')
        ->orderBy('history_change.id', 'DESC')
        ->first();

        return $data;
    }
}
