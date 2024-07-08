<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TechnicalDescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'technical_description';

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

     // Creando funcion que relaciona los modelos
     public function equipments(){
        return $this->hasMany(Equipment::class,'equipments_details', 'equipment_id', 'technical_description_id')->withPivot('value');
    }



    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return TechnicalDescription::select('technical_description.*', 'technical_description.id as id')

		->where('technical_description.name', 'like', $search)
        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("technical_description.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return TechnicalDescription::select('technical_description.*', 'technical_description.id as id')

		->where('technical_description.name', 'like', $search)

        ->count();
    }
    public function equipmentDetail()
    {
        return $this->hasMany(EquipmentDetail::class);
    }
}
