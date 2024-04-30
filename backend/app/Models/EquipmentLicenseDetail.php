<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EquipmentLicenseDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_license_detail';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id', 'equipment_id', 'license_id', 'created_at', 'updated_at', 
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return EquipmentLicenseDetail::select('equipment_license_detail.*', 'equipment.*', 'license.*', 'equipment_license_detail.id as id')
        ->join('equipment', 'equipment_license_detail.equipment_id', '=', 'equipment.id')
->join('license', 'equipment_license_detail.license_id', '=', 'license.id')

		->where('equipment_license_detail.equipment_id', 'like', $search)

        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("equipment_license_detail.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return EquipmentLicenseDetail::select('equipment_license_detail.*', 'equipment.*', 'license.*', 'equipment_license_detail.id as id')
        ->join('equipment', 'equipment_license_detail.equipment_id', '=', 'equipment.id')
->join('license', 'equipment_license_detail.license_id', '=', 'license.id')

		->where('equipment_license_detail.equipment_id', 'like', $search)

        ->count();
    }
}
