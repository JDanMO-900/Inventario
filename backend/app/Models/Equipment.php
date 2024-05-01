<?php

namespace App\Models;

use Encrypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function historyChange(){
        return $this->hasMany(HistoryChange::class);
    }

    public function equipmentDetail(){
        return $this->hasMany(EquipmentDetail::class);
    }

    

   

    // public function format(){
    //     return [
    //         'id' => Encrypt::encryptValue($this->id),
    //         'number_active' => $this->number_active,
    //         'number_internal_active' => $this->number_internal_active,
    //         'model' => $this->model,
    //         'serial_number' => $this->serial_number,
    //         'adquisition_date' => $this->adquisition_date,
    //         'invoice_number' => $this->invoice_number,
    //         'equipment_state_id' => $this->equipment_state_id,

    //         'equipment_type_id' => $this->equipment_type_id,
    //         'brand_id' => $this->brand_id,
    //         'equipmentDetails' => $this->equipmentDetail()->get()->map(fn($detail) => $detail->format()),
    //         'historyChanges' =>  $this->historyChange()->get()->map(fn($eq) => $eq->format()),
    //         'provider_id' => $this->provider_id,
    //         'created_at' => $this->created_at,
    //         'updated_at' => $this->updated_at,
    //     ];
    // }




    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {

        $data = Equipment::select(
            'equipment.*', 
            'equipment_state.*', 

            'equipment_type.*', 
            'brand.*', 
            'provider.*', 
            'equipment.id as id', 
            'brand.name as brand',
            'provider.name as provider',
            'equipment_state.name as state',

            'equipment_type.name as equipment_type_id',

            // Techcnical descriptions
            "technical_description.name as technicalDescription",
            "equipment_detail.attribute as attribute"


            )
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
           
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('provider', 'equipment.provider_id', '=', 'provider.id')
            ->leftJoin('equipment_detail', 'equipment_detail.equipment_id','=','equipment.id')
            ->leftJoin('technical_description', 'equipment_detail.technical_description_id','=','technical_description.id')
            ->where('equipment.number_internal_active', 'like', $search)
            ->orWhere('equipment.number_active','like', $search)
            ->orWhere('equipment.model','like', $search)
            ->orWhere('equipment.serial_number','like', $search)
            ->orWhere('equipment.adquisition_date','like', $search)
            ->orWhere('equipment.invoice_number','like', $search)
            ->orWhere('equipment.equipment_type_id','like', $search)
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
        return Equipment::select('equipment.*', 'equipment_state.*', 'equipment_type.*', 'brand.*', 'provider.*', 'equipment.id as id')
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
            
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('provider', 'equipment.provider_id', '=', 'provider.id')

            ->where('equipment.number_internal_active', 'like', $search)

            ->count();
    }




    public static function equipmentFilter($equip)
    {
        $data = Equipment::select(
            'equipment.*', 
            'equipment_state.*', 

            'equipment_type.*', 
            'brand.*', 
            'provider.*', 
            'equipment.id as id', 
            'brand.name as brand',
            'provider.name as provider',
            'equipment_state.name as state',

            'equipment_type.name as type',

            // Users
            'users.name as users',

            // Type action
            'type_action.name as type_action'


            )
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
           
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('provider', 'equipment.provider_id', '=', 'provider.id')
            ->join('equipment_detail', 'equipment_detail.equipment_id','=','equipment.id')
            ->join('technical_description', 'equipment_detail.technical_description_id','=','technical_description.id')
            ->join('history_change', 'history_change.equipment_id','=', 'equipment.id')
            ->join('history_user_detail', 'history_user_detail.history_change_id','=', 'history_change.id')
            ->join('type_action', 'type_action.id','=', 'history_change.type_action_id')
            ->join('users', 'users.id','=', 'history_user_detail.user_id')
            ->where('equipment.number_internal_active', 'like', $equip)

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

}
