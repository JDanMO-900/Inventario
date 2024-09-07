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
        $data =  TypeAction::select('type_action.*', 'type_action.id as id')

            ->where('type_action.name', 'like', $search)

            // ->skip($skip)
            // ->take($itemsPerPage)
            ->orderBy("type_action.$sortBy", $sort)
            ->get();

        $data->each(function ($item) {
            $is_internal = $item->is_internal ? 'Personal interno' : 'Personal externo';
            $item->is_internal = $is_internal;
        });

        return $data;
    }

    public static function counterPagination($search)
    {
        return TypeAction::select('type_action.*', 'type_action.id as id')

            ->where('type_action.name', 'like', $search)

            ->count();
    }

    public function historyChange()
    {
        return $this->hasMany(HistoryChange::class);
    }
}
