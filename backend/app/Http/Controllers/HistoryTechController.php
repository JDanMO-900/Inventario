<?php

namespace App\Http\Controllers;

use App\Models\HistoryTech;
use App\Models\HistoryChange;
use App\Models\User;

use Illuminate\Http\Request;
use Encrypt;

class HistoryTechController extends Controller
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
            $itemsPerPage =  HistoryTech::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0])) ? $request->sortBy[0] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $historytech = HistoryTech::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $historytech = Encrypt::encryptObject($historytech, "id");

        $total = HistoryTech::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $historytech,
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
        $historytech = new HistoryTech;

		$historytech->history_change_id = HistoryChange::where('id', $request->id)->first()->id;
		$historytech->user_tech_id = User::where('name', $request->name)->first()->id;

        $historytech->save();

        return response()->json([
            "message"=>"Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HistoryTech  historytech
     * @return \Illuminate\Http\Response
     */
    public function show(HistoryTech $historytech)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HistoryTech  $historytech
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $historytech = HistoryTech::where('id', $data['id'])->first();
		$historytech->history_change_id = HistoryChange::where('id', $request->id)->first()->id;
		$historytech->user_tech_id = User::where('name', $request->name)->first()->id;

        $historytech->save();

        return response()->json([
            "message"=>"Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HistoryTech  $historytech
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        HistoryTech::where('id', $id)->delete();

        return response()->json([
            "message"=>"Registro eliminado correctamente.",
        ]);
    }
}
