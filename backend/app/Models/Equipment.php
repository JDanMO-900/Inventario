<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Encrypt;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'number_active',
        'number_internal_active',
        'model',
        'serial_number',
        'adquisition_date',
        'invoice_number',
        'equipment_state_id',
        'dependency_id',
        'equipment_type_id',
        'brand_id',
        'provider_id',
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


    // Creando funcion que relaciona los modelos
    public function technicalDescription()
    {
        return $this->hasMany(TechnicalDescription::class, 'equipments_details', 'equipment_id', 'technical_description_id')->withPivot('value');
    }

    public function historyChange(){
        return $this->hasMany(HistoryChange::class);
    }

    public function equipmentDetail(){
        return $this->hasMany(EquipmentDetail::class);
    }

    

   

    public function format(){
        return [
            'id' => Encrypt::encryptValue($this->id),
            'number_active' => $this->number_active,
            'number_internal_active' => $this->number_internal_active,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'adquisition_date' => $this->adquisition_date,
            'invoice_number' => $this->invoice_number,
            'equipment_state_id' => $this->equipment_state_id,
            'dependency_id' => $this->dependency_id,
            'equipment_type_id' => $this->equipment_type_id,
            'brand_id' => $this->brand_id,
            'equipmentDetails' => $this->equipmentDetail()->get()->map(fn($detail) => $detail->format()),
            'historyChanges' =>  $this->historyChange()->get()->map(fn($eq) => $eq->format()),
            'provider_id' => $this->provider_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }




    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        $data = Equipment::select(
            'equipment.*', 
            'equipment_state.*', 
            'dependency.*', 
            'equipment_type.*', 
            'brand.*', 
            'provider.*', 
            'equipment.id as id', 
            'brand.name as brand',
            'provider.name as provider',
            'equipment_state.name as state',
            'dependency.name as dependency',
            'equipment_type.name as type'
            )
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
            ->join('dependency', 'equipment.dependency_id', '=', 'dependency.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('provider', 'equipment.provider_id', '=', 'provider.id')
            ->where('equipment.number_internal_active', 'like', $search)
            ->orWhere('equipment.number_active','like', $search)
            ->orWhere('equipment.model','like', $search)
            ->orWhere('equipment.serial_number','like', $search)
            ->orWhere('equipment.adquisition_date','like', $search)
            ->orWhere('equipment.invoice_number','like', $search)
            ->skip($skip)
            ->take($itemsPerPage)
            ->orderBy("equipment.$sortBy", $sort)
            ->get();

            $data->each(function ($item) {
                $licenses = License::join('equipment_license_detail', 'license.id', '=', 'equipment_license_detail.license_id')
                    ->where('equipment_license_detail.equipment_id', $item->id )
                    ->pluck('license.name')
                    ->toArray();
    
                $item->licenses = $licenses;
            });
    
            return $data;
    }

    public static function counterPagination($search)
    {
        return Equipment::select('equipment.*', 'equipment_state.*', 'dependency.*', 'equipment_type.*', 'brand.*', 'provider.*', 'equipment.id as id')
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
            ->join('dependency', 'equipment.dependency_id', '=', 'dependency.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('provider', 'equipment.provider_id', '=', 'provider.id')

            ->where('equipment.number_internal_active', 'like', $search)

            ->count();
    }
}
