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
        'availability',
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
            'equipment.availability'
        )
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->leftJoin('provider', 'equipment.provider_id', '=', 'provider.id')
            ->where('equipment.number_internal_active', 'like', $search)
            ->orWhere('equipment.number_active', 'like', $search)
            ->orWhere('equipment.model', 'like', $search)
            ->orWhere('equipment.serial_number', 'like', $search)
            ->orWhere('equipment.adquisition_date', 'like', $search)
            ->orWhere('equipment.invoice_number', 'like', $search)
            ->orWhere('equipment.equipment_type_id', 'like', $search)
            ->orWhere('equipment.availability', 'like', $search)
            ->skip($skip)
            ->take($itemsPerPage)
            ->orderBy("equipment.$sortBy", $sort)
            ->get();

            

        Log::info(($search === 'En uso') ? 0 : (($search === 'Dispo') ? 1 : ''));

        $data->each(function ($item) {
            $availability = $item->availability ? 'Disponible' : 'En uso';
            $item->availability = $availability;

            $licenses = License::leftJoin('equipment_license_detail', 'license.id', '=', 'equipment_license_detail.license_id')
                ->where('equipment_license_detail.equipment_id', $item->id)
                ->pluck('license.name')
                ->toArray();

            $item->licenses = $licenses;

            $technicalAttributes = EquipmentDetail::leftJoin('technical_description', 'technical_description.id', '=', 'equipment_detail.technical_description_id')
                ->where('equipment_detail.equipment_id', $item->id)
                ->select('technical_description.name as technicalDescription', 'equipment_detail.attribute as attribute')
                ->get()
                ->toArray();

            $item->technicalAttributes = $technicalAttributes;
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
            'type_action.name as type_action',
            "history_change.start_date as start_date",
            "history_change.end_date as end_date"

        )
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->leftJoin('provider', 'equipment.provider_id', '=', 'provider.id')
            ->leftJoin('history_change', 'history_change.equipment_id', '=', 'equipment.id')
            ->join('history_user_detail', 'history_user_detail.history_change_id', '=', 'history_change.id')
            ->join('users', 'users.id', '=', 'history_user_detail.user_id')
            ->join('type_action', 'type_action.id', '=', 'history_change.type_action_id')
            ->where('equipment.serial_number', 'like', $equip)
            ->orderBy('history_change.start_date', 'desc')

            ->get();

        $data->each(function ($item) {
            $licenses = License::join('equipment_license_detail', 'license.id', '=', 'equipment_license_detail.license_id')
                ->where('equipment_license_detail.equipment_id', $item->id)
                ->pluck('license.name')
                ->toArray();

            $item->licenses = $licenses;
        });

        return $data;
    }


    public static function availableEquipments()
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
            'equipment.availability',
            'equipment.model as model',
            'equipment.serial_number as serial_number'
        )
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->leftJoin('provider', 'equipment.provider_id', '=', 'provider.id')
            ->where('equipment.availability', 'like', 1)
            ->get();

        $data->each(function ($item) {
            $availability = $item->availability ? 'Disponible' : 'En uso';
            $item->availability = $availability;
            $item->format = '(Tipo: ' . $item->equipment_type_id . ') ' . '(Modelo: ' . $item->model . ') ' . '(Activo fijo: ' . $item->number_internal_active . ') ' . '(Registro interno: ' . $item->serial_number . ')';
        });


        return $data;
    }

    public static function userEquipment($username, $search, $sortBy, $sort, $skip, $itemsPerPage)
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
            'equipment.availability',
            'equipment.model as model',
            'equipment.serial_number as serial_number',
            'history_change.start_date as start_date',
            'history_change.end_date as end_date',

        )
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('history_change', 'equipment.id', '=', 'history_change.equipment_id')
            ->leftJoin('provider', 'equipment.provider_id', '=', 'provider.id')
            ->join('history_user_detail', 'history_change.id', '=', 'history_user_detail.history_change_id')
            ->join('users', 'users.id', '=', 'history_user_detail.user_id')
            ->where('equipment.availability', 'like', 0)
            ->where('users.name', 'like', $username)
            ->whereNull('history_change.end_date')
            ->get();

        $data->each(function ($item) {
            $availability = $item->availability ? 'Disponible' : 'En uso';
            $item->availability = $availability;
            $item->format = '(Tipo: ' . $item->equipment_type_id . ') ' . '(Modelo: ' . $item->model . ') ' . '(Activo fijo: ' . $item->number_internal_active . ') ' . '(Registro interno: ' . $item->serial_number . ')';
        });




        return $data;
    }

}


