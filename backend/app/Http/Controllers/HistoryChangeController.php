<?php

namespace App\Http\Controllers;

use App\Models\HistoryChange;
use App\Models\TypeAction;
use App\Models\Equipment;
use App\Models\ProcessState;

use Illuminate\Http\Request;
use Encrypt;

class HistoryChangeController extends Controller
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
            $itemsPerPage =  HistoryChange::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $historychange = HistoryChange::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $historychange = Encrypt::encryptObject($historychange, "id");

        $total = HistoryChange::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $historychange,
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
        $historychange = new HistoryChange;

		$historychange->location = $request->location;
		$historychange->description = $request->description;
		$historychange->quantity_out = $request->quantity_out;
		$historychange->quantity_in = $request->quantity_in;
		$historychange->type_action_id = TypeAction::where('name', $request->name)->first()->id;
		$historychange->equipment_id = Equipment::where('number_internal_active', $request->number_internal_active)->first()->id;
		$historychange->equipment_used_in_id = Equipment::where('number_internal_active', $request->number_internal_active)->first()->id;
		$historychange->state_id = ProcessState::where('created_at', $request->created_at)->first()->id;
		$historychange->deleted_at = $request->deleted_at;

        $historychange->save();

        return response()->json([
            "message"=>"Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HistoryChange  historychange
     * @return \Illuminate\Http\Response
     */
    public function show(HistoryChange $historychange)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HistoryChange  $historychange
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $historychange = HistoryChange::where('id', $data['id'])->first();
		$historychange->location = $request->location;
		$historychange->description = $request->description;
		$historychange->quantity_out = $request->quantity_out;
		$historychange->quantity_in = $request->quantity_in;
		$historychange->type_action_id = TypeAction::where('name', $request->name)->first()->id;
		$historychange->equipment_id = Equipment::where('number_internal_active', $request->number_internal_active)->first()->id;
		$historychange->equipment_used_in_id = Equipment::where('number_internal_active', $request->number_internal_active)->first()->id;
		$historychange->state_id = ProcessState::where('created_at', $request->created_at)->first()->id;
		$historychange->deleted_at = $request->deleted_at;

        $historychange->save();

        return response()->json([
            "message"=>"Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HistoryChange  $historychange
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        HistoryChange::where('id', $id)->delete();

        return response()->json([
            "message"=>"Registro eliminado correctamente.",
        ]);
    }
}
