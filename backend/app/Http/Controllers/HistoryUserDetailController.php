<?php

namespace App\Http\Controllers;

use Encrypt;
use App\Models\User;
use App\Models\Equipment;

use App\Models\TypeAction;
use App\Models\ProcessState;
use Illuminate\Http\Request;
use App\Models\HistoryChange;
use App\Models\HistoryUserDetail;
use App\Http\Controllers\Controller;
use App\Models\EquipmentLicenseDetail;

class HistoryUserDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     public function generalUser(Request $request){
        
        // return response()->json([
        //     "message" => "Registros obtenidos correctamente.",
        //     "data" => HistoryUserDetail::allDataGeneralUser($username),

            
        // ]);
        $itemsPerPage = $request->itemsPerPage ?? 10;
        $skip = ($request->page - 1) * $request->itemsPerPage;

        // Getting all the records
        if (($request->itemsPerPage == -1)) {
            $itemsPerPage = HistoryUserDetail::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0])) ? $request->sortBy[0] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $username = ($request->username) ? "%$request->username%" : '%%';

        $historyuserdetail = HistoryUserDetail::allDataGeneralUser($username ,$search, $sortBy, $sort, $skip, $itemsPerPage);
        $historyuserdetail = Encrypt::encryptObject($historyuserdetail, "id");

        $total = HistoryUserDetail::counterPagination($search);

        return response()->json([
            "message" => "Registros obtenidos correctamente.",
            "data" => $historyuserdetail,
            "total" => $total,
        ]);
 
 
     }


    public function index(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage ?? 10;
        $skip = ($request->page - 1) * $request->itemsPerPage;

        // Getting all the records
        if (($request->itemsPerPage == -1)) {
            $itemsPerPage = HistoryUserDetail::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0])) ? $request->sortBy[0] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $historyuserdetail = HistoryUserDetail::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $historyuserdetail = Encrypt::encryptObject($historyuserdetail, "id");

        $total = HistoryUserDetail::counterPagination($search);

        return response()->json([
            "message" => "Registros obtenidos correctamente.",
            "data" => $historyuserdetail,
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


        $historyChange = new HistoryChange();
        $historyChange->location = $request->location;
		$historyChange->description = $request->description;
		$historyChange->quantity_out = $request->quantity_out;
		$historyChange->quantity_in = $request->quantity_in;
		$historyChange->type_action_id = TypeAction::where('name', $request->action)->first()->id;
		$historyChange->equipment_id = Equipment::where('number_internal_active', $request->number_internal_active1)->first()->id;
		$historyChange->equipment_used_in_id = Equipment::where('number_internal_active', $request->number_internal_active2)->first()->id;
		$historyChange->state_id = ProcessState::where('name', $request->process)->first()->id;
        $historyChange->save();

        $historyuserdetail = new HistoryUserDetail;
        

        $historyuserdetail->history_change_id = HistoryChange::where('location', $request->location)->first()->id;
        $historyuserdetail->user_id = User::where('name', $request->name)->first()->id;
        $historyuserdetail->technician = $request->technician;

        $historyuserdetail->save();

        return response()->json([
            "message" => "Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HistoryUserDetail  historyuserdetail
     * @return \Illuminate\Http\Response
     */
    public function show(HistoryUserDetail $historyuserdetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HistoryUserDetail  $historyuserdetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $historyuserdetail = HistoryUserDetail::where('id', $data['id'])->first();
        $historyuserdetail->history_change_id = HistoryChange::where('location', $request->location)->first()->id;
        $historyuserdetail->user_id = User::where('name', $request->name)->first()->id;
        $historyuserdetail->technician = $request->technician;

        $historyuserdetail->save();

        return response()->json([
            "message" => "Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HistoryUserDetail  $historyuserdetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        HistoryUserDetail::where('id', $id)->delete();

        return response()->json([
            "message" => "Registro eliminado correctamente.",
        ]);
    }
}
