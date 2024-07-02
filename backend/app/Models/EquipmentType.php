<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_type';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id', 'name', 'created_at', 'updated_at', 'deleted_at',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return EquipmentType::select('equipment_type.*', 'equipment_type.id as id')

		->where('equipment_type.name', 'like', $search)

        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("equipment_type.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return EquipmentType::select('equipment_type.*', 'equipment_type.id as id')

		->where('equipment_type.name', 'like', $search)

        ->count();
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'equipment_type_id', 'id');
    }
}
