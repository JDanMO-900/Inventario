<?php

namespace App\Http\Controllers;

use App\Models\EquipmentState;

use Illuminate\Http\Request;
use Encrypt;

class EquipmentStateController extends Controller
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
            $itemsPerPage =  EquipmentState::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $equipmentstate = EquipmentState::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $equipmentstate = Encrypt::encryptObject($equipmentstate, "id");

        $total = EquipmentState::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $equipmentstate,
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
        $equipmentstate = new EquipmentState;

		$equipmentstate->name = $request->name;
		$equipmentstate->deleted_at = $request->deleted_at;

        $equipmentstate->save();

        return response()->json([
            "message"=>"Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EquipmentState  equipmentstate
     * @return \Illuminate\Http\Response
     */
    public function show(EquipmentState $equipmentstate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EquipmentState  $equipmentstate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $equipmentstate = EquipmentState::where('id', $data['id'])->first();
		$equipmentstate->name = $request->name;
		$equipmentstate->deleted_at = $request->deleted_at;

        $equipmentstate->save();

        return response()->json([
            "message"=>"Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EquipmentState  $equipmentstate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        EquipmentState::where('id', $id)->delete();

        return response()->json([
            "message"=>"Registro eliminado correctamente.",
        ]);
    }
}
