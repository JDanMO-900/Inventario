<?php

namespace App\Models;

use Encrypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryChange extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'history_change';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',

        'description',
        'quantity_out',
        'quantity_in',
        'start_date',
        'end_date',

        'type_action_id',
        'equipment_id',
        'state_id',
        'location_id',
        'dependency_id',

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



    // public function format()
    // {
    //     return [
    //         'id' => Encrypt::encryptValue($this->id),

    //         'description' => $this->description,
    //         'quantity_out' => $this->quantity_out,
    //         'quantity_in' => $this->quantity_in,
    //         'type_action_id' => $this->type_action_id,
    //         'equipment_id' => $this->equipment_id,
    //         'state_id' => $this->state_id,
    //         'location_id' => $this->location_id,
    //         'dependency_id' => $this->location_id,

    //         'created_at' => $this->created_at,
    //         'updated_at' => $this->updated_at,
    //     ];

    // }

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {

        $data =  HistoryChange::select('history_change.*',
        'type_action.*',
        'equipment_id.*',
        'process_state.*',
        'location.*',
        'dependency.*',
        'history_change.id as id',



        // Ubicacion
        'location.name as location_id',
        'dependency.name as dependency_id',

        //Equipo1
        'equipment_id.serial_number as equipment_id',
        'equipment_id.model as model1',
        'equipment_type1.name as type1',
        'brand1.name as brand1',



        'process_state.name as state_id',
        'type_action.name as type_action_id'
        )
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            // Equipo principal
            ->join('equipment as equipment_id', 'history_change.equipment_id', '=', 'equipment_id.id')
            ->join('equipment_type as equipment_type1', 'equipment_id.equipment_type_id', '=', 'equipment_type1.id')
            ->join('brand as brand1', 'equipment_id.brand_id', '=', 'brand1.id')


            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            ->join('location', 'history_change.location_id', '=', 'location.id')
            ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')


            ->where('location.name', 'like', $search)
            ->orWhere('dependency.name', 'like', $search)
            ->orWhere('process_state.name', 'like', $search)
            ->orWhere('type_action.name', 'like', $search)

            ->skip($skip)
            ->take($itemsPerPage)
            ->orderBy("history_change.$sortBy", $sort)
            ->get();

            $data->each(function($item){
                $users = User::Join('history_user_detail', 'users.id','=', 'history_user_detail.user_id')
                ->where('history_user_detail.history_change_id', $item->id)
                ->pluck('users.name')
                ->toArray();
                $item->users = $users;
            });

            $data->each(function($item){
                $technician = User::leftJoin('history_tech', 'users.id','=', 'history_tech.user_tech_id')
                ->where('history_tech.history_change_id', $item->id)
                ->pluck('users.name')
                ->toArray();
                $item->technician = $technician;
            });

            return $data;

    }

    public static function counterPagination($search)
    {
        return HistoryChange::select('history_change.*', 'type_action.*', 'equipment_id.*', 'process_state.*', 'location.*', 'history_change.id as id')
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            ->join('equipment as equipment_id', 'history_change.equipment_id', '=', 'equipment_id.id')
            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            ->join('location', 'history_change.location_id', '=', 'location.id')
            ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
            ->where('history_change.location_id', 'like', $search)

            ->count();
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function locations()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function typeActions()
    {
        return $this->belongsTo(TypeAction::class, 'type_action_id');
    }
    public function dependencys()
    {
        return $this->belongsTo(Dependency::class, 'dependency_id');
    }



}
