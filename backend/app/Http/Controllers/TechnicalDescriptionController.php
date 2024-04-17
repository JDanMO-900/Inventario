<?php

namespace App\Http\Controllers;

use App\Models\TechnicalDescription;

use Illuminate\Http\Request;
use Encrypt;

class TechnicalDescriptionController extends Controller
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
            $itemsPerPage =  TechnicalDescription::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $technicaldescription = TechnicalDescription::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $technicaldescription = Encrypt::encryptObject($technicaldescription, "id");

        $total = TechnicalDescription::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $technicaldescription,
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
        $technicaldescription = new TechnicalDescription;

		$technicaldescription->name = $request->name;
		$technicaldescription->deleted_at = $request->deleted_at;

        $technicaldescription->save();

        return response()->json([
            "message"=>"Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TechnicalDescription  technicaldescription
     * @return \Illuminate\Http\Response
     */
    public function show(TechnicalDescription $technicaldescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TechnicalDescription  $technicaldescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $technicaldescription = TechnicalDescription::where('id', $data['id'])->first();
		$technicaldescription->name = $request->name;
		$technicaldescription->deleted_at = $request->deleted_at;

        $technicaldescription->save();

        return response()->json([
            "message"=>"Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TechnicalDescription  $technicaldescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        TechnicalDescription::where('id', $id)->delete();

        return response()->json([
            "message"=>"Registro eliminado correctamente.",
        ]);
    }
}
