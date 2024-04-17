<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Encrypt;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'provider';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id', 'name', 'contact_name', 'contact_phone', 'address', 'created_at', 'updated_at', 'deleted_at', 
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return Provider::select('provider.*', 'provider.id as id')
        
		// ->where('provider.name', 'like', $search)
        ->where('provider.name', 'like', $search)
        ->orWhere('provider.contact_name', 'like', $search)
        ->orWhere('provider.contact_phone', 'like', $search)
        ->orWhere('provider.address', 'like', $search)
        ->skip($skip)
        ->take($itemsPerPage)
        ->orderBy("provider.$sortBy", $sort)
        ->get();
    }

    public static function counterPagination($search)
    {
        return Provider::select('provider.*', 'provider.id as id')
        
		->where('provider.name', 'like', $search)

        ->count();
    }
}
