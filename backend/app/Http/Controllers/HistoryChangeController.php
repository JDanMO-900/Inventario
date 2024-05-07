<?php

namespace App\Http\Controllers;

use Encrypt;
use App\Models\User;
use App\Models\Location;
use App\Models\Equipment;

use App\Models\Dependency;
use App\Models\TypeAction;
use App\Models\ProcessState;
use Illuminate\Http\Request;
use App\Models\HistoryChange;
use App\Models\HistoryUserDetail;

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
        $historychange->type_action_id = TypeAction::where('name', $request->action)->first()->id;
        $historychange->equipment_id = Equipment::where('number_active', $request->number_active1)->first()->id;

        if($request->number_active != ""){
            $historychange->equipment_used_in_id = Equipment::where('number_active', $request->number_active2)->first()->id;

        }
        else {
            $historychange->equipment_used_in_id = null;
        }

        if($request->end_date != ""){
            $historychange->end_date = $request->end_date;

        }
        else {
            $historychange-> end_date = null;
        }
        
        $historychange->state_id = ProcessState::where('name', $request->process)->first()->id;


        $historychange->location_id = Location::where('name', $request->location_id)->first()->id;
        $historychange->dependency_id = Dependency::where('name', $request->dependency_id)->first()->id;

        $historychange->save();

        $historyuserdetail = new HistoryUserDetail;
        $lastInsertedRow = HistoryChange::latest()->first();
        

        $historyuserdetail->history_change_id = $lastInsertedRow->id;//Here I want to get my last row;
        $historyuserdetail->user_id = User::where('name', $request->user)->first()->id;
        // Modificar por nuevo valor
        $historyuserdetail->user_tech_id = User::where('name', $request->technician)->first()->id; 

        $historyuserdetail->save();


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
        $historychange->type_action_id = TypeAction::where('name', $request->action)->first()->id;
        $historychange->equipment_id = Equipment::where('number_active', $request->number_active1)->first()->id;
        
        if($request->number_active != ""){
            $historychange->equipment_used_in_id = Equipment::where('number_active', $request->number_active2)->first()->id;

        }
        else {
            $historychange->equipment_used_in_id = null;
        }

        if($request->end_date != ""){
            $historychange->end_date = $request->end_date;

        }
        else {
            $historychange-> end_date = null;
        }

        $historychange->state_id = ProcessState::where('name', $request->process)->first()->id;
        $historychange->location_id = Location::where('name', $request->location_id)->first()->id;
        $historychange->dependency_id = Dependency::where('name', $request->dependency_id)->first()->id;
        
        $historychange->save();



        // Hacer que por medio de history change encuentre el history user_detail y de esta manera se pueda editar
        $historyuserdetail = new HistoryUserDetail;
        $historyuserdetail->history_change_id =  $historychange -> id;
        $historyuserdetail->user_id = User::where('name', $request->user)->first()->id;
        // Modificar por nuevo valor
        $historyuserdetail->user_tech_id = User::where('name', $request->technician)->first()->id; 
        $historyuserdetail->save();









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
}
