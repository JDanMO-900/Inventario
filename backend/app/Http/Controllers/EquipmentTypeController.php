<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;

use Illuminate\Http\Request;
use Encrypt;

class EquipmentTypeController extends Controller
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
            $itemsPerPage =  EquipmentType::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $equipmenttype = EquipmentType::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        // $equipmenttype = Encrypt::encryptObject($equipmenttype, "id");

        $total = EquipmentType::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $equipmenttype,
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
        $equipmenttype = new EquipmentType;

		$equipmenttype->name = $request->name;
		$equipmenttype->deleted_at = $request->deleted_at;

        $equipmenttype->save();

        return response()->json([
            "message"=>"Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EquipmentType  equipmenttype
     * @return \Illuminate\Http\Response
     */
    public function show(EquipmentType $equipmenttype)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EquipmentType  $equipmenttype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $equipmenttype = EquipmentType::where('id', $data['id'])->first();
		$equipmenttype->name = $request->name;
		$equipmenttype->deleted_at = $request->deleted_at;

        $equipmenttype->save();

        return response()->json([
            "message"=>"Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EquipmentType  $equipmenttype
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        EquipmentType::where('id', $id)->delete();

        return response()->json([
            "message"=>"Registro eliminado correctamente.",
        ]);
    }
}
