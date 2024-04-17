<?php

namespace App\Models;

use Encrypt;
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
        'location',
        'description',
        'quantity_out',
        'quantity_in',
        'type_action_id',
        'equipment_id',
        'equipment_used_in_id',
        'state_id',
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



    public function format()
    {
        return [
            'id' => Encrypt::encryptValue($this->id),
            'location' => $this->location,
            'description' => $this->description,
            'quantity_out' => $this->quantity_out,
            'quantity_in' => $this->quantity_in,
            'type_action_id' => $this->type_action_id,
            'equipment_id' => $this->equipment_id,
            'equipment_used_in_id' => $this->equipment_id,
            'state_id' => $this->state_id,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return HistoryChange::select('history_change.*', 'type_action.*', 'equipment1.*', 'equipment2.*', 'process_state.*', 'history_change.id as id')
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            ->join('equipment as equipment1', 'history_change.equipment_id', '=', 'equipment1.id')
            ->join('equipment as equipment2', 'history_change.equipment_used_in_id', '=', 'equipment2.id')
            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')
            ->where('history_change.location', 'like', $search)
            ->orWhere('history_change.description', 'like', $search)
            ->orWhere('history_change.quantity_out', 'like', $search)
            ->orWhere('history_change.quantity_in', 'like', $search)

            ->skip($skip)
            ->take($itemsPerPage)
            ->orderBy("history_change.$sortBy", $sort)
            ->get();
            
    }

    public static function counterPagination($search)
    {
        return HistoryChange::select('history_change.*', 'type_action.*', 'equipment1.*', 'equipment2.*', 'process_state.*', 'history_change.id as id')
            ->join('type_action', 'history_change.type_action_id', '=', 'type_action.id')
            ->join('equipment as equipment1', 'history_change.equipment_id', '=', 'equipment1.id')
            ->join('equipment as equipment2', 'history_change.equipment_used_in_id', '=', 'equipment2.id')
            ->join('process_state', 'history_change.state_id', '=', 'process_state.id')

            ->where('history_change.location', 'like', $search)

            ->count();
    }
}
