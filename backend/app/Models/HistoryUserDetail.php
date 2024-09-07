<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryUserDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'history_user_detail';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'history_change_id',
        'user_id',

        'created_at',
        'updated_at',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;



    // public static function allDataGeneralUser($username, $search, $sortBy, $sort, $skip, $itemsPerPage)
    public static function allDataGeneralUser($username, $search, $sortBy, $sort)
    {
        $data = HistoryUserDetail::select(
            'history_user_detail.*',
            'history_change.*',
            'users.*',
            'history_user_detail.id as id',
            // Datos del equipo
            'equipment.*',
            'users.name as users',
            'equipment_type.name as equipment_type',
            'equipment.serial_number as equipment_id',
            'equipment.model as model',
            'brand.name as brand',
            'history_change.start_date as start_date',
            'history_change.end_date as end_date',
            'type_action.name as type_action',
            'type_action.is_internal as internal',
            'process_state.name as process_state',
            'type_action.name as type_action_id',
            'type_action.id as action_id',
            'process_state.id as process_id',
            'location.name as location'

        )

            ->join('history_change', 'history_user_detail.history_change_id', '=', 'history_change.id')
            ->join('location', 'location.id', '=', 'history_change.location_id')
            ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('users', 'history_user_detail.user_id', '=', 'users.id')
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            // ->skip($skip)
            // ->take($itemsPerPage)
            ->where('users.name', 'like', $username)
            ->orderBy("history_user_detail.$sortBy", $sort)
            ->get();

        $data->each(function ($item) {

            $technician = User::leftJoin('history_tech', 'users.id', '=', 'history_tech.user_tech_id')
                ->where('history_tech.history_change_id', $item->history_change_id)
                ->pluck('users.name')
                ->toArray();
            $item->technician = $technician;
        });

        return $data;
    }

    public static function equipmentUsers($username)
    {
        $data = HistoryUserDetail::query()
            ->join('users', 'history_user_detail.user_id', '=', 'users.id')
            ->join('history_change', 'history_user_detail.history_change_id', '=', 'history_change.id')
            ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            ->where('users.name', $username)
            ->where('type_action.id', 2)
            ->select([
                'users.name as usuario',
                'equipment.id',
                'equipment.number_active',
                'equipment.number_internal_active',
                'equipment.model',
                'equipment.serial_number',
                'brand.name as marca',
                'equipment_type.name as tipo',
            ])
            ->get();

        return $data;
    }

    public static function filterUserHistoryChange($username, $search, $sortBy, $sort)
    {
        $data = HistoryUserDetail::select(
            'history_user_detail.*',
            'history_change.*',
            'users.*',
            'history_user_detail.id as id',
            // Datos del equipo
            'equipment.*',
            'users.name as users',
            'equipment_type.name as equipment_type',
            'equipment.serial_number as equipment_id',
            'equipment.model as model',
            'brand.name as brand',
            'history_change.start_date as start_date',
            'history_change.end_date as end_date',
            'type_action.name as type_action',
            'type_action.is_internal as internal',
            'process_state.name as process_state',
            'type_action.name as type_action_id',
            'type_action.id as action_id',
            'process_state.id as process_id',
            'location.name as location'

        )

            ->join('history_change', 'history_user_detail.history_change_id', '=', 'history_change.id')
            ->join('location', 'location.id', '=', 'history_change.location_id')
            ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('users', 'history_user_detail.user_id', '=', 'users.id')
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            // ->skip($skip)
            // ->take($itemsPerPage)
            ->where('users.name', 'like', $username)
            ->where('history_change.type_action_id', $search['typeMovement_condition'], $search['typeMovement'])
            ->Where('history_change.state_id', $search['processState_condition'], $search['processState'])
            ->whereBetween('history_change.start_date', [$search['start_range'], $search['end_range']])
            ->orderBy("history_user_detail.$sortBy", $sort)
            ->get();

        $data->each(function ($item) {

            $technician = User::leftJoin('history_tech', 'users.id', '=', 'history_tech.user_tech_id')
                ->where('history_tech.history_change_id', $item->history_change_id)
                ->pluck('users.name')
                ->toArray();
            $item->technician = $technician;
        });

        return $data;
    }



    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        $data = HistoryUserDetail::select(
            'history_user_detail.*',
            'history_change.*',
            'users.*',
            'history_user_detail.id as id',

            // Datos del equipo
            'equipment1.*',

            'equipment.number_active as activo_fijo',
            'equipment.number_internal_active as activo_fijo_interno',

            'equipment.adquisition_date as adquisition_date',
            'equipment.invoice_number as factura',

            'equipment.serial_number as serial',
            'equipment.model as model',
            'brand.name as brand',
            'equipment_state.name as state',
            'equipment_type.name as type',

            // Datos del history change
            'history_change.description as description',

            'process_state.name as process',

            // Datos de la dependencia
            'dependency.name as dependency',

            // // Accion
            'type_action.name as action',

            // getting internal active number
            'equipment1.number_internal_active as number_internal_active1',

            // Location description


        )

            ->join('history_change', 'history_user_detail.history_change_id', '=', 'history_change.id')
            ->join('location', 'location.id', '=', 'history_change.location_id')
            ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
            ->join('users', 'history_user_detail.user_id', '=', 'users.id')
            ->join('equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('equipment_state', 'equipment.equipment_state_id', '=', 'equipment_state.id')
            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            ->join('equipment as equipment1', 'history_change.equipment_id', '=', 'equipment1.id')


            ->orWhere('equipment_type.name', 'like', $search)
            ->orWhere('equipment.model', 'like', $search)

            // ->skip($skip)
            // ->take($itemsPerPage)
            ->orderBy("history_user_detail.$sortBy", $sort)


            ->get();

        $data->each(function ($item) {
            $licenses = License::join('equipment_license_detail', 'license.id', '=', 'equipment_license_detail.license_id')
                ->where('equipment_license_detail.equipment_id', $item->equipment_id)
                ->pluck('license.name')
                ->toArray();

            $item->licenses = $licenses;
        });

        return $data;
    }

    public static function counterPagination($search)
    {
        return HistoryUserDetail::select('history_user_detail.*', 'history_change.*', 'users.*', 'history_user_detail.id as id')
            ->join('history_change', 'history_user_detail.history_change_id', '=', 'history_change.id')
            ->join('users', 'history_user_detail.user_id', '=', 'users.id')



            ->count();
    }
}
