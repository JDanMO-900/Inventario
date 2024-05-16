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
        'equipment_used_in_id',
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
    //         'equipment_used_in_id' => $this->equipment_id,
    //         'state_id' => $this->state_id,
    //         'location_id' => $this->location_id,
    //         'dependency_id' => $this->location_id,

    //         'created_at' => $this->created_at,
    //         'updated_at' => $this->updated_at,
    //     ];

    // }

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return HistoryChange::select('history_change.*', 
        'type_action.*', 
        'equipment1.*', 
        'equipment2.*', 
        'process_state.*', 
        'location.*', 
        'dependency.*', 
        'history_change.id as id',

        // Datos del usuario
        'users.name as users',
        'technician.name as technician',

        // Ubicacion
        'location.name as location_id',
        'dependency.name as dependency_id',

        //Equipo1
        'equipment1.serial_number as serial_number1',
        'equipment1.model as model1',
        'equipment_type1.name as type1',
        'brand1.name as brand1',

        //Equipo2
        'equipment2.serial_number as serial_number2',
        'equipment2.model as model2',
        'equipment_type2.name as type2',
        'brand2.name as brand2',

        'process_state.name as state_id',
        'type_action.name as type_action_id'

        
        

        
        )
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            // Equipo principal
            ->join('equipment as equipment1', 'history_change.equipment_id', '=', 'equipment1.id')
            ->join('equipment_type as equipment_type1', 'equipment1.equipment_type_id', '=', 'equipment_type1.id')
            ->join('brand as brand1', 'equipment1.brand_id', '=', 'brand1.id')
            // Equipo secundario
            ->leftJoin('equipment as equipment2', 'history_change.equipment_used_in_id', '=', 'equipment2.id')
            ->leftJoin('equipment_type as equipment_type2', 'equipment2.equipment_type_id', '=', 'equipment_type2.id')
            ->leftJoin('brand as brand2', 'equipment2.brand_id', '=', 'brand2.id')

            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            ->join('location', 'history_change.location_id', '=', 'location.id')
            ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
            ->join('history_user_detail', 'history_user_detail.history_change_id','=', 'history_change.id')
            ->join('users', 'history_user_detail.user_id','=', 'users.id')
            ->join('users as technician', 'history_user_detail.user_tech_id','=', 'technician.id')
            ->where('location.name', 'like', $search)
            ->orWhere('dependency.name', 'like', $search)
            ->orWhere('process_state.name', 'like', $search)
            ->orWhere('type_action.name', 'like', $search)
            ->orWhere(function($query) use ($search){
                $query->where('users.name', 'like', $search);
            })




            ->skip($skip)
            ->take($itemsPerPage)
            ->orderBy("history_change.$sortBy", $sort)
            ->get();
            
    }

    public static function counterPagination($search)
    {
        return HistoryChange::select('history_change.*', 'type_action.*', 'equipment1.*', 'equipment2.*', 'process_state.*', 'location.*', 'history_change.id as id')
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            ->join('equipment as equipment1', 'history_change.equipment_id', '=', 'equipment1.id')
            ->leftJoin('equipment as equipment2', 'history_change.equipment_used_in_id', '=', 'equipment2.id')
            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            ->join('location', 'history_change.location_id', '=', 'location.id')
            ->join('dependency', 'history_change.dependency_id', '=', 'dependency.id')
            ->where('history_change.location_id', 'like', $search)

            ->count();
    }
}
