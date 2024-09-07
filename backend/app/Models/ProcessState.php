<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProcessState extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'process_state';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id', 'name', 'created_at', 'updated_at', 
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return ProcessState::select('process_state.*', 'process_state.id as id')
        
		->where('process_state.name', 'like', $search)

        // ->skip($skip)
        // ->take($itemsPerPage)
        ->orderBy("process_state.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return ProcessState::select('process_state.*', 'process_state.id as id')
        
		->where('process_state.name', 'like', $search)

        ->count();
    }
}
