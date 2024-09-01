<?php

namespace App\Models;

use App\Models\Equipment;
use App\Models\HistoryChange;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PDFData extends Model
{
    use HasFactory;

    public static function allProducts($search)
    {
        $lastestHistoryChange = HistoryChange::select('equipment_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('equipment_id');

        $data = Equipment::select(
            'equipment.id',
            'equipment_type.name as type',
            'brand.name as brand',
            'equipment.model',
            'equipment.number_active',
            'equipment.serial_number',
            'equipment_state.name as state',
            DB::raw("CASE WHEN history_change.end_date IS NULL THEN location.name ELSE '' END as location")
        )
            ->join('brand', 'brand.id', '=', 'equipment.brand_id')
            ->join('equipment_type', 'equipment_type.id', '=', 'equipment.equipment_type_id')
            ->join('equipment_state', 'equipment_state.id', '=', 'equipment.equipment_state_id')
            ->leftJoinSub($lastestHistoryChange, 'latest_history', function ($join) {
                $join->on('latest_history.equipment_id', '=', 'equipment.id');
            })
            ->leftJoin('history_change', 'history_change.id', '=', 'latest_history.latest_id')
            ->leftJoin('location', 'history_change.location_id', '=', 'location.id')
            ->where('brand_id', $search['brand_condition'], $search['brand'])
            ->where('equipment_type_id', $search['type_condition'], $search['type'])
            ->where('location.id', $search['location_condition'], $search['location'])
            ->orderBy('equipment_type.name', 'ASC')
            ->get();

        return $data;
    }

    public static function technicalDescriptions($id)
    {
        $data = EquipmentDetail::select('equipment_detail.attribute as value', 'technical_description.name as item')
            ->join('technical_description', 'technical_description.id', '=', 'equipment_detail.technical_description_id')
            ->where('equipment_id', $id)
            ->orderBy('technical_description.name', 'ASC')
            ->get();

        return $data;
    }

    public static function getEquipmentLocation($id, $search)
    {
        $data = HistoryChange::select('location.name as location')
            ->join('location', 'location.id', '=', 'history_change.location_id')
            ->where('history_change.equipment_id', $id)
            ->where('history_change.location_id', $search['location_condition'], $search['location'])
            ->whereIn('history_change.type_action_id', ['2', '3', '4'])
            ->whereNull('history_change.end_date')
            ->orderBy('history_change.id', 'DESC')
            ->first();

        return $data;
    }

    public static function individualReport($search)
    {
        $data = HistoryChange::select(
            'history_change.*',
            'type_action.*',
            'equipment.*',
            'process_state.*',
            'location.*',
            'dependency.*',
            'history_change.id as id',
            // Ubicacion
            'location.name as location_id',
            'dependency.name as dependency_id',
            //Equipo1
            'equipment.serial_number as serial_number',
            'equipment.number_active as number_active',
            'equipment.model as model',
            'equipment_type.name as type',
            'brand.name as brand',
            'process_state.name as state_id',
            'type_action.name as type_action_id',
            'location.name as location',
            'history_change.description as description',
            'history_change.start_date as start_date'

        )
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            // Equipo principal
            ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
            ->join('equipment_type as equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
            ->join('brand', 'equipment.brand_id', '=', 'brand.id')
            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            ->join('location', 'history_change.location_id', '=', 'location.id')
            ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
            ->where('equipment.serial_number', 'like', $search)
            ->whereIn('history_change.type_action_id', [2,3])
            ->get();

        $data->each(function ($item) {
            $users = User::leftJoin('history_user_detail', 'users.id', '=', 'history_user_detail.user_id')
                ->where('history_user_detail.history_change_id', $item->id)
                ->pluck('users.name')
                ->toArray();
            $item->users = $users;


            $technicalAttributes = EquipmentDetail::leftJoin('technical_description', 'technical_description.id', '=', 'equipment_detail.technical_description_id')
                ->where('equipment_detail.equipment_id', $item->equipment_id)
                ->select('technical_description.name as technicalDescription', 'equipment_detail.attribute as attribute')
                ->get()
                ->toArray();

            $item->technicalAttributes = $technicalAttributes;
        });

        return $data;
    }

    public static function locationReport($search)
    {

        $brandExist = Brand::whereRaw('name = ?', [$search->brand])->exists();
        $typeExist = EquipmentType::whereRaw('name = ?', [$search->type])->exists();
        $locationExist = Location::whereRaw('name = ?', [$search->location])->exists();

        if (!$brandExist && !$typeExist && !$locationExist) {

            $data = HistoryChange::select(
                'history_change.*',
                'type_action.*',
                'equipment.*',
                'process_state.*',
                'location.*',
                'dependency.*',
                'history_change.id as id',
                // Ubicacion
                'location.name as location_id',
                'dependency.name as dependency_id',
                //Equipo1
                'equipment.serial_number as serial_number',
                'equipment.number_active as number_active',
                'equipment.model as model',
                'equipment_type.name as type',
                'brand.name as brand',
                'process_state.name as state_id',
                'type_action.name as type_action_id'
            )
                ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
                // Equipo principal
                ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
                ->join('equipment_type as equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
                ->join('brand', 'equipment.brand_id', '=', 'brand.id')
                ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
                ->join('location', 'history_change.location_id', '=', 'location.id')
                ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
                ->get();

            $data->each(function ($item) {
                $users = User::leftJoin('history_user_detail', 'users.id', '=', 'history_user_detail.user_id')
                    ->where('history_user_detail.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->users = $users;

                $technician = User::leftJoin('history_tech', 'users.id', '=', 'history_tech.user_tech_id')
                    ->where('history_tech.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->technician = $technician;

                $licenses = License::leftJoin('equipment_license_detail', 'license.id', '=', 'equipment_license_detail.license_id')
                    ->where('equipment_license_detail.equipment_id', $item->equipment_id)
                    ->pluck('license.name')
                    ->toArray();
                $item->licenses = $licenses;

                $technicalAttributes = EquipmentDetail::leftJoin('technical_description', 'technical_description.id', '=', 'equipment_detail.technical_description_id')
                    ->where('equipment_detail.equipment_id', $item->equipment_id)
                    ->select('technical_description.name as technicalDescription', 'equipment_detail.attribute as attribute')
                    ->get()
                    ->toArray();

                $item->technicalAttributes = $technicalAttributes;
            });

        } else if (!$brandExist && !$typeExist && $locationExist) {

            $data = HistoryChange::select(
                'history_change.*',
                'type_action.*',
                'equipment.*',
                'process_state.*',
                'location.*',
                'dependency.*',
                'history_change.id as id',
                // Ubicacion
                'location.name as location_id',
                'dependency.name as dependency_id',
                //Equipo1
                'equipment.serial_number as serial_number',
                'equipment.number_active as number_active',
                'equipment.model as model',
                'equipment_type.name as type',
                'brand.name as brand',
                'process_state.name as state_id',
                'type_action.name as type_action_id'
            )
                ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
                // Equipo principal
                ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
                ->join('equipment_type as equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
                ->join('brand', 'equipment.brand_id', '=', 'brand.id')
                ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
                ->join('location', 'history_change.location_id', '=', 'location.id')
                ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
                ->where('location.name', 'like', $search->location)
                ->get();

            $data->each(function ($item) {
                $users = User::leftJoin('history_user_detail', 'users.id', '=', 'history_user_detail.user_id')
                    ->where('history_user_detail.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->users = $users;

                $technician = User::leftJoin('history_tech', 'users.id', '=', 'history_tech.user_tech_id')
                    ->where('history_tech.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->technician = $technician;

                $licenses = License::leftJoin('equipment_license_detail', 'license.id', '=', 'equipment_license_detail.license_id')
                    ->where('equipment_license_detail.equipment_id', $item->equipment_id)
                    ->pluck('license.name')
                    ->toArray();
                $item->licenses = $licenses;

                $technicalAttributes = EquipmentDetail::leftJoin('technical_description', 'technical_description.id', '=', 'equipment_detail.technical_description_id')
                    ->where('equipment_detail.equipment_id', $item->equipment_id)
                    ->select('technical_description.name as technicalDescription', 'equipment_detail.attribute as attribute')
                    ->get()
                    ->toArray();

                $item->technicalAttributes = $technicalAttributes;
            });
        } else if ($brandExist && !$typeExist) {
            $data = HistoryChange::select(
                'history_change.*',
                'type_action.*',
                'equipment.*',
                'process_state.*',
                'location.*',
                'dependency.*',
                'history_change.id as id',
                // Ubicacion
                'location.name as location_id',
                'dependency.name as dependency_id',
                //Equipo1
                'equipment.serial_number as serial_number',
                'equipment.number_active as number_active',
                'equipment.model as model',
                'equipment_type.name as type',
                'brand.name as brand',
                'process_state.name as state_id',
                'type_action.name as type_action_id'
            )
                ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
                // Equipo principal
                ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
                ->join('equipment_type as equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
                ->join('brand', 'equipment.brand_id', '=', 'brand.id')
                ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
                ->join('location', 'history_change.location_id', '=', 'location.id')
                ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
                ->where('location.name', 'like', $search->location)
                ->where('brand.name', 'like', $search->brand)
                ->get();

            $data->each(function ($item) {
                $users = User::Join('history_user_detail', 'users.id', '=', 'history_user_detail.user_id')
                    ->where('history_user_detail.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->users = $users;

                $technician = User::leftJoin('history_tech', 'users.id', '=', 'history_tech.user_tech_id')
                    ->where('history_tech.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->technician = $technician;

                $licenses = License::join('equipment_license_detail', 'license.id', '=', 'equipment_license_detail.license_id')
                    ->where('equipment_license_detail.equipment_id', $item->equipment_id)
                    ->pluck('license.name')
                    ->toArray();
                $item->licenses = $licenses;

                $technicalAttributes = EquipmentDetail::leftJoin('technical_description', 'technical_description.id', '=', 'equipment_detail.technical_description_id')
                    ->where('equipment_detail.equipment_id', $item->equipment_id)
                    ->select('technical_description.name as technicalDescription', 'equipment_detail.attribute as attribute')
                    ->get()
                    ->toArray();

                $item->technicalAttributes = $technicalAttributes;
            });
        } else if (!$brandExist && $typeExist) {

            $data = HistoryChange::select(
                'history_change.*',
                'type_action.*',
                'equipment.*',
                'process_state.*',
                'location.*',
                'dependency.*',
                'history_change.id as id',
                // Ubicacion
                'location.name as location_id',
                'dependency.name as dependency_id',
                //Equipo1
                'equipment.serial_number as serial_number',
                'equipment.number_active as number_active',
                'equipment.model as model',
                'equipment_type.name as type',
                'brand.name as brand',
                'process_state.name as state_id',
                'type_action.name as type_action_id'
            )
                ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
                // Equipo principal
                ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
                ->join('equipment_type as equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
                ->join('brand', 'equipment.brand_id', '=', 'brand.id')
                ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
                ->join('location', 'history_change.location_id', '=', 'location.id')
                ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
                ->where('location.name', 'like', $search->location)
                ->where('equipment_type.name', 'like', $search->type)
                ->get();

            $data->each(function ($item) {
                $users = User::Join('history_user_detail', 'users.id', '=', 'history_user_detail.user_id')
                    ->where('history_user_detail.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->users = $users;

                $technician = User::leftJoin('history_tech', 'users.id', '=', 'history_tech.user_tech_id')
                    ->where('history_tech.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->technician = $technician;

                $licenses = License::join('equipment_license_detail', 'license.id', '=', 'equipment_license_detail.license_id')
                    ->where('equipment_license_detail.equipment_id', $item->equipment_id)
                    ->pluck('license.name')
                    ->toArray();
                $item->licenses = $licenses;

                $technicalAttributes = EquipmentDetail::leftJoin('technical_description', 'technical_description.id', '=', 'equipment_detail.technical_description_id')
                    ->where('equipment_detail.equipment_id', $item->equipment_id)
                    ->select('technical_description.name as technicalDescription', 'equipment_detail.attribute as attribute')
                    ->get()
                    ->toArray();

                $item->technicalAttributes = $technicalAttributes;
            });
        } else {
            $data = HistoryChange::select(
                'history_change.*',
                'type_action.*',
                'equipment.*',
                'process_state.*',
                'location.*',
                'dependency.*',
                'history_change.id as id',
                // Ubicacion
                'location.name as location_id',
                'dependency.name as dependency_id',
                //Equipo1
                'equipment.serial_number as serial_number',
                'equipment.number_active as number_active',
                'equipment.model as model',
                'equipment_type.name as type',
                'brand.name as brand',
                'process_state.name as state_id',
                'type_action.name as type_action_id'
            )
                ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
                // Equipo principal
                ->join('equipment', 'history_change.equipment_id', '=', 'equipment.id')
                ->join('equipment_type as equipment_type', 'equipment.equipment_type_id', '=', 'equipment_type.id')
                ->join('brand', 'equipment.brand_id', '=', 'brand.id')
                ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
                ->join('location', 'history_change.location_id', '=', 'location.id')
                ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
                ->where('location.name', 'like', $search->location)
                ->where('brand.name', 'like', $search->brand)
                ->where('equipment_type.name', 'like', $search->type)
                ->get();

            $data->each(function ($item) {
                $users = User::Join('history_user_detail', 'users.id', '=', 'history_user_detail.user_id')
                    ->where('history_user_detail.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->users = $users;

                $technician = User::leftJoin('history_tech', 'users.id', '=', 'history_tech.user_tech_id')
                    ->where('history_tech.history_change_id', $item->id)
                    ->pluck('users.name')
                    ->toArray();
                $item->technician = $technician;

                $licenses = License::join('equipment_license_detail', 'license.id', '=', 'equipment_license_detail.license_id')
                    ->where('equipment_license_detail.equipment_id', $item->equipment_id)
                    ->pluck('license.name')
                    ->toArray();
                $item->licenses = $licenses;

                $technicalAttributes = EquipmentDetail::leftJoin('technical_description', 'technical_description.id', '=', 'equipment_detail.technical_description_id')
                    ->where('equipment_detail.equipment_id', $item->equipment_id)
                    ->select('technical_description.name as technicalDescription', 'equipment_detail.attribute as attribute')
                    ->get()
                    ->toArray();

                $item->technicalAttributes = $technicalAttributes;
            });
        }


        return $data;
    }
    public static function getEquipmentData($brand)
    {
        $data = Equipment::select(
            'equipment.id',
            'equipment_type.name as type',
            'brand.name as brand',
            'equipment.model',
            'equipment.number_active',
            'equipment_state.name as state',
            'equipment.equipment_state_id as location'
        )
            ->join('brand', 'brand.id', '=', 'equipment.brand_id')
            ->join('equipment_type', 'equipment_type.id', '=', 'equipment.equipment_type_id')
            ->join('equipment_state', 'equipment_state.id', '=', 'equipment.equipment_state_id')
            ->orderBy('equipment_type.name', 'ASC');
        if (!empty($brand) && $brand !== 'TODAS LAS MARCAS') {
            $data->where('brand.name', $brand);
        }
        return $data->get();
    }
    public static function getTechniacalDetailData($equipment)
    {
        return EquipmentDetail::select(
            'technical_description.name as component',
            'equipment_detail.attribute as capacity',
        )
            ->join('technical_description', 'equipment_detail.technical_description_id', '=', 'technical_description.id')
            ->where('equipment_detail.equipment_id', $equipment)->get();
    }

    public static function getAvailableEquipment($dates)
    {
        return Equipment::select('equipment.number_active', 'equipment.serial_number', 'equipment.model', 'et.name as type', 'br.name as brand')
            ->join('equipment_type as et', 'et.id', '=', 'equipment.equipment_type_id')
            ->join('brand as br', 'br.id', '=', 'equipment.brand_id')
            ->whereBetween('equipment.created_at', $dates)
            ->where('equipment.availability', 1)
            ->orderBy('et.name', 'ASC')
            ->get();
    }
}
