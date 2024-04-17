<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Dependency extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dependency';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id', 'name', 'created_at', 'updated_at', 'deleted_at', 
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return Dependency::select('dependency.*', 'dependency.id as id')
        
		->where('dependency.name', 'like', $search)

        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("dependency.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return Dependency::select('dependency.*', 'dependency.id as id')
        
		->where('dependency.name', 'like', $search)

        ->count();
    }
}
