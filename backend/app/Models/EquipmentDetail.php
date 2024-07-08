<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EquipmentDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_detail';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'attribute',
        'equipment_id',
        'technical_description_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function technical()
    {
        return $this->belongsTo(TechnicalDescription::class, 'technical_description_id', 'id');
    }

    public function format()
    {
        return [
            'id' => $this->id,
            'attribute' => $this->attribute,
            'equipment_id' => $this->equipment_id,
            'technical_description' => $this->technical_description_id,
            'equipment' => $this->equipment()->get(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function getById(int $id){
        $equipment = Equipment::where('equipment_id', $id)->get()->map(fn ($equipment) => $equipment->format());

        return $equipment;
    }



    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return EquipmentDetail::select('*')
            ->join('equipment', 'equipment_detail.equipment_id', '=', 'equipment.id')
            ->join('technical_description', 'equipment_detail.technical_description_id', '=', 'technical_description.id')
            ->where('equipment_detail.attribute', 'like', $search)
            ->skip($skip)
            ->take($itemsPerPage)
            ->orderBy("equipment_detail.$sortBy", $sort)
            ->get()
            ->map(fn($equipDetail) => $equipDetail->format());
    }

    public static function counterPagination($search)
    {
        return EquipmentDetail::select('equipment_detail.*', 'equipment.*', 'technical_description.*', 'equipment_detail.id as id')
            ->join('equipment', 'equipment_detail.equipment_id', '=', 'equipment.id')
            ->join('technical_description', 'equipment_detail.technical_description_id', '=', 'technical_description.id')

            ->where('equipment_detail.attribute', 'like', $search)

            ->count();
    }


}
