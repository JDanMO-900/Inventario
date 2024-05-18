<?php

namespace App\Http\Controllers;

use Encrypt;
use App\Models\User;
use App\Models\Location;
use App\Models\Equipment;

use App\Models\Dependency;
use App\Models\TypeAction;
use App\Models\HistoryTech;
use App\Models\ProcessState;
use Illuminate\Http\Request;
use App\Models\HistoryChange;
use App\Models\HistoryUserDetail;
use Illuminate\Support\Facades\Log;

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
            $itemsPerPage = HistoryChange::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortBy[0]['order'])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $historychange = HistoryChange::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $historychange = Encrypt::encryptObject($historychange, "id");

        $total = HistoryChange::counterPagination($search);

        return response()->json([
            "message" => "Registros obtenidos correctamente.",
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


        $historychange->description = $request->description;
        $historychange->quantity_out = $request->quantity_out;
        $historychange->quantity_in = $request->quantity_in;
        $historychange->start_date = $request->start_date;
        $historychange->type_action_id = TypeAction::where('name', $request->type_action_id)->first()->id;
        $historychange->equipment_id = Equipment::where('serial_number', $request->equipment_id)->first()->id;
        $available1 = Equipment::where('serial_number', $request->equipment_id)->first();
        $available1->availability = false;
        $available1->save();

        if ($request->serial_number2 != "") {
            $historychange->equipment_used_in_id = Equipment::where('serial_number', $request->serial_number2)->first()->id;
            $available2 = Equipment::where('serial_number', $request->serial_number2)->first();
            $available2->availability = false;
            $available2->save();

        } else {
            $historychange->equipment_used_in_id = null;
        }

        if ($request->end_date != "") {
            $historychange->end_date = $request->end_date;

        } else {
            $historychange->end_date = null;
        }

        $historychange->state_id = ProcessState::where('name', $request->state_id)->first()->id;


        $historychange->location_id = Location::where('name', $request->location_id)->first()->id;
        $historychange->dependency_id = Dependency::where('name', $request->dependency_id)->first()->id;

        $historychange->save();

        foreach ($request->users as $user) {
            $lastInsertedRow = HistoryChange::latest()->first();
            $historyuserdetail = new HistoryUserDetail;
            $historyuserdetail->history_change_id = $lastInsertedRow->id;
            $historyuserdetail->user_id = User::where('name', $user)->first()->id;
            $historyuserdetail->save();
        }

        foreach ($request->technician as $tech) {
            $lastInsertedRow = HistoryChange::latest()->first();
            $historyusertech = new HistoryTech;
            $historyusertech->history_change_id = $lastInsertedRow->id;
            $historyusertech->user_tech_id = User::where('name', $tech)->first()->id;
            $historyusertech->save();
        }


        return response()->json([
            "message" => "Registro creado correctamente.",
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
        $historychange->description = $request->description;
        $historychange->quantity_out = $request->quantity_out;
        $historychange->quantity_in = $request->quantity_in;
        $historychange->start_date = $request->start_date;
        $historychange->type_action_id = TypeAction::where('name', $request->type_action_id)->first()->id;

        $historychange->equipment_id = Equipment::where('serial_number', $request->equipment_id)->first()->id;

        if ($request->serial_number2 != "") {
            $historychange->equipment_used_in_id = Equipment::where('serial_number', $request->serial_number2)->first()->id;

        } else {
            $historychange->equipment_used_in_id = null;
        }

        if ($request->end_date != "") {
            $historychange->end_date = $request->end_date;

        } else {
            $historychange->end_date = null;
        }

        $historychange->state_id = ProcessState::where('name', $request->state_id)->first()->id;
        $historychange->location_id = Location::where('name', $request->location_id)->first()->id;
        $historychange->dependency_id = Dependency::where('name', $request->dependency_id)->first()->id;

        $historychange->save();


        // Hacer que por medio de history change encuentre el history user_detail y de esta manera se pueda editar

        HistoryUserDetail::where('history_change_id', $historychange->id)->forceDelete();

        Log::info($request->users);
        foreach ($request->users as $user) {
            $historyuserdetail = new HistoryUserDetail;
            $historyuserdetail->history_change_id = $historychange->id;
            $historyuserdetail->user_id = User::where('name', $user)->first()->id;
            $historyuserdetail->save();
        }

        HistoryTech::where('history_change_id', $historychange->id)->forceDelete();

        foreach ($request->technician as $tech) {
            $historyusertech = new HistoryTech;
            $historyusertech->history_change_id = $historychange->id;
            $historyusertech->user_tech_id = User::where('name', $tech)->first()->id;
            $historyusertech->save();
        }


        return response()->json([
            "message" => "Registro modificado correctamente.",
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
            "message" => "Registro eliminado correctamente.",
        ]);
    }

    public function updateEndProcess(Request $request)
    {

        $data = Encrypt::decryptArray($request->all(), 'id');
        $historychange = HistoryChange::where('id', $data['id'])->first();
        $historychange->end_date = $request->finish_date;
        $historychange->description = $request->description;
        $historychange->state_id = ProcessState::where('name', $request->state_id)->first()->id;
        $historychange->save();


        $available1 = Equipment::where('serial_number', $request->equipment_id)->first();
        $available1->availability = true;
        $available1->save();

        if ($request->serial_number2 != "") {
            $historychange->equipment_used_in_id = Equipment::where('serial_number', $request->serial_number2)->first()->id;
            $available2 = Equipment::where('serial_number', $request->serial_number2)->first();
            $available2->availability = true;
            $available2->save();

        } else {
            $historychange->equipment_used_in_id = null;
        }

        return response()->json([
            "message" => "Movimiento terminado, equipo disponible de nuevo",
        ]);
    }




}
