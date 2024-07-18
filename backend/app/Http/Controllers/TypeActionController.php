<?php

namespace App\Http\Controllers;

use Encrypt;

use App\Models\TypeAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TypeActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage ?? 10;
        $skip = ($request->page - 1) * $request->itemsPerPage;

        // Getting all the records
        if (($request->itemsPerPage == -1)) {
            $itemsPerPage =  TypeAction::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $TypeAction = TypeAction::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $TypeAction = Encrypt::encryptObject($TypeAction, "id");

        $total = TypeAction::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $TypeAction,
            "total" => $total,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $TypeAction = new TypeAction;
		$TypeAction->name = $request->name;
        $TypeAction->is_internal = $request->is_internal;
        $TypeAction->save();

        return response()->json([
            "message"=>"Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeAction  TypeAction
     * @return \Illuminate\Http\Response
     */
    public function show(TypeAction $TypeAction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeAction  $TypeAction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $TypeAction = TypeAction::where('id', $data['id'])->first();
		$TypeAction->name = $request->name;
        if($request->is_internal === 'Personal interno'){
            
        }
        else{
            $TypeAction->is_internal = 0;
        }
        

        $TypeAction->save();

        return response()->json([
            "message"=>"Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeAction  $TypeAction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        TypeAction::where('id', $id)->delete();

        return response()->json([
            "message"=>"Registro eliminado correctamente.",
        ]);
    }
}
