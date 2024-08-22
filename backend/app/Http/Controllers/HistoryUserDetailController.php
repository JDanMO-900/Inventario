<?php

namespace App\Http\Controllers;

use Encrypt;
use App\Models\User;
use Illuminate\Http\Request;


use App\Models\HistoryChange;
use App\Models\HistoryUserDetail;
use Illuminate\Support\Facades\Log;

class HistoryUserDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function userFilter($username, Request $request)
    {

        if (in_array(null, $request->filter, true)) {

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

            $historyuserdetail = HistoryUserDetail::allDataGeneralUser($username, $search, $sortBy, $sort, $skip, $itemsPerPage);
            $historyuserdetail = Encrypt::encryptObject($historyuserdetail, "id");

            $total = HistoryUserDetail::counterPagination($search);

            return response()->json([
                "message" => "Registros obtenidos correctamente.",
                "data" => $historyuserdetail,
                "total" => $total,
            ]);
        }
        else{
            $search = [
                'typeMovement' => ($request->filter['typeMovement']),
                'typeMovement_condition' => ($request->filter['typeMovement'] == -1) ? '>' : '=',
                'processState' => ($request->filter['processStateFilter']),
                'processState_condition' => ($request->filter['processStateFilter'] == -1) ? '>' : '=',
                'start_range' => $request->filter['start_date'],
                'end_range' => $request->filter['end_date'],
            ];


            $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
            $sort = (isset($request->sortBy[0]['order'])) ? "asc" : 'desc';
    
            $historyuserdetail = HistoryUserDetail::filterUserHistoryChange($username, $search, $sortBy, $sort);
            $historyuserdetail = Encrypt::encryptObject($historyuserdetail, "id");
    
            return response()->json([
                "message" => "Registros obtenidos correctamente.",
                "data" => $historyuserdetail,
            ]);

        }

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
        $historyuserdetail = new HistoryUserDetail;

        $historyuserdetail->history_change_id = HistoryChange::where('quantity_out', $request->quantity_out)->first()->id;
        $historyuserdetail->user_id = User::where('name', $request->name)->first()->id;
        $historyuserdetail->user_tech_id = User::where('name', $request->name)->first()->id;
        $historyuserdetail->deleted_at = $request->deleted_at;

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
        $historyuserdetail->history_change_id = HistoryChange::where('quantity_out', $request->quantity_out)->first()->id;
        $historyuserdetail->user_id = User::where('name', $request->name)->first()->id;
        $historyuserdetail->user_tech_id = User::where('name', $request->name)->first()->id;
        $historyuserdetail->deleted_at = $request->deleted_at;

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
