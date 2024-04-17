<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'brand';

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
        return Brand::select('brand.*', 'brand.id as id')
        
		->where('brand.name', 'like', $search)

        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("brand.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return Brand::select('brand.*', 'brand.id as id')
        
		->where('brand.name', 'like', $search)

        ->count();
    }
}
