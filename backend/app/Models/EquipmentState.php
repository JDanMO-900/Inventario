<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EquipmentState extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_state';

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
        return EquipmentState::select('equipment_state.*', 'equipment_state.id as id')

		->where('equipment_state.name', 'like', $search)

        // ->skip($skip)
        // ->take($itemsPerPage)
        ->orderBy("equipment_state.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return EquipmentState::select('equipment_state.*', 'equipment_state.id as id')

		->where('equipment_state.name', 'like', $search)

        ->count();
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

}

