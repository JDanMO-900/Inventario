<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'license';

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
        return License::select('license.*', 'license.id as id')
        
		->where('license.name', 'like', $search)

        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("license.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return License::select('license.*', 'license.id as id')
        
		->where('license.name', 'like', $search)

        ->count();
    }
}
