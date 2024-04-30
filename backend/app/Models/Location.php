<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'location';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
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



    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return Location::select('location.*', 'location.id as id')


            ->where('location.name', 'like', $search)

            ->skip($skip)
            ->take($itemsPerPage)
            ->orderBy("location.$sortBy", $sort)
            ->get();
    }

    public static function counterPagination($search)
    {
        return Location::select('location.*','location.id as id')


            ->where('location.name', 'like', $search)

            ->count();
    }
}
