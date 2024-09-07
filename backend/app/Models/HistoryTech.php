<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class HistoryTech extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'history_tech';

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id',
        'history_change_id',
        'user_tech_id',
        'created_at',
        'updated_at',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        return HistoryTech::select('history_tech.*', 'history_change.*', 'users.*', 'history_tech.id as id')
            ->join('history_change', 'history_tech.history_change_id', '=', 'history_change.id')
            ->join('users', 'history_tech.user_tech_id', '=', 'users.id')

            ->where('history_tech.id', 'like', $search)

            // ->skip($skip)
            // ->take($itemsPerPage)
            ->orderBy("history_tech.$sortBy", $sort)
            ->get();
    }

    public static function counterPagination($search)
    {
        return HistoryTech::select('history_tech.*', 'history_change.*', 'users.*', 'history_tech.id as id')
            ->join('history_change', 'history_tech.history_change_id', '=', 'history_change.id')
            ->join('users', 'history_tech.user_tech_id', '=', 'users.id')

            ->where('history_tech.id', 'like', $search)

            ->count();
    }
}
