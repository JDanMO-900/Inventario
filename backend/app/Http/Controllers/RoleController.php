<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request){
        $itemsPerPage = $request->itemsPerPage ?? 10;
        $skip = ($request->page - 1) * $request->itemsPerPage;

        // Getting all the records
        if (($request->itemsPerPage == -1)) {
            $itemsPerPage =  Role::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $role = Role::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        // $role = Encrypt::encryptObject($role, "id");

        $total = Role::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $role,
            "total" => $total,
        ]);

    }
}
