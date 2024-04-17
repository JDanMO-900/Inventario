<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TypeAction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'type_action';

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
        return TypeAction::select('type_action.*', 'type_action.id as id')
        
		->where('type_action.name', 'like', $search)

        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("type_action.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return TypeAction::select('type_action.*', 'type_action.id as id')
        
		->where('type_action.name', 'like', $search)

        ->count();
    }
}
