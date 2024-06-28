<?php

namespace App\Models;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PDFData extends Model
{
    use HasFactory;

    public static function typeReport($search){
        $data = Equipment::select()
        ->join('brand', 'brand.id', '=', 'equipment.brand_id')
        ->where('brand_id', $search['brand_condition'], $search['brand'])
        ->where('equipment_type_id', $search['type_condition'], $search['type'])
        ->get();

        return $data;
    }
}
