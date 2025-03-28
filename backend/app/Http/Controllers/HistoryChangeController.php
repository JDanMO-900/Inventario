<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
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

        if(in_array(null, $request->filter, true)){
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
    
            $historychange = HistoryChange::allDataSearched($search, $sortBy, $sort);
            $historychange = Encrypt::encryptObject($historychange, "id");
    
            $total = HistoryChange::counterPagination($search);
    
            return response()->json([
                "message" => "Registros obtenidos correctamente.",
                "data" => $historychange,
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
    
            $historychange = HistoryChange::filterHistoryChange($search, $sortBy, $sort);
            $historychange = Encrypt::encryptObject($historychange, "id");
    
            return response()->json([
                "message" => "Registros obtenidos correctamente.",
                "data" => $historychange,
            ]);

        }


    }

    public function filterMovement(Request $request){

        $search = [
            'typeMovement' => ($request->search['typeMovement']),
            'typeMovement_condition' => ($request->search['typeMovement'] == -1) ? '>' : '=',
            'type' => ($request->search['type']),
            'type_condition' => ($request->search['type'] == -1) ? '>' : '=',
            'processState' => ($request->search['processState']),
            'processState_condition' => ($request->search['processState'] == -1) ? '>' : '=',
            'start_range' => $request->search['start_date'],
            'end_range' => $request->search['end_date'],
        ];

        $historychange = HistoryChange::filterMovementByParameters($search);
        $historychange = Encrypt::encryptObject($historychange, "id");

        return response()->json([
            "message" => "Registros obtenidos correctamente.",
            "data" => $historychange,
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
   

        foreach ($request->equipment_id as $equipment) {
            $historychange = new HistoryChange;
            $historychange->description = $request->description;
            $historychange->quantity_out = $request->quantity_out;
            $historychange->quantity_in = $request->quantity_in;
            $historychange->start_date = $request->start_date;
            $historychange->type_action_id = TypeAction::where('name', $request->type_action_id)->first()->id;
            $historychange->equipment_id = Equipment::where('serial_number', $equipment)->first()->id;
            $available1 = Equipment::where('serial_number', $equipment)->first();
            $available1->availability = false;
            $available1->save();

            if ($request->end_date != "") {
                $historychange->end_date = $request->end_date;

            } else {
                $historychange->end_date = null;
            }


            $historychange->state_id = ProcessState::where('name', $request->state_id)->first()->id;
            $historychange->location_id = Location::where('name', $request->location_id)->first()->id;
            $historychange->dependency_id = Dependency::where('name', $request->dependency_id)->first()->id;
            $historychange->save();
            $lastInsertedRow = $historychange->id;

            if (is_array($request->users)) {
                foreach ($request->users as $user) {
                    $historyuserdetail = new HistoryUserDetail;
                    $historyuserdetail->history_change_id = $lastInsertedRow;
                    $historyuserdetail->user_id = User::where('name', $user)->first()->id;
                    $historyuserdetail->save();
                }
            } else {
                $historyuserdetail = new HistoryUserDetail;
                $historyuserdetail->history_change_id = $lastInsertedRow;
                $historyuserdetail->user_id = User::where('name', $request->users)->first()->id;
                $historyuserdetail->save();
            }

            if ($request->technician != "") {
                if (is_array($request->technician)) {
                    foreach ($request->technician as $tech) {
                        $historyusertech = new HistoryTech;
                        $historyusertech->history_change_id = $lastInsertedRow;
                        $historyusertech->user_tech_id = User::where('name', $tech)->first()->id;
                        $historyusertech->save();
                    }
                } else {
                    $historyusertech = new HistoryTech;
                    $historyusertech->history_change_id = $lastInsertedRow;
                    $historyusertech->user_tech_id = User::where('name', $request->technician)->first()->id;
                    $historyusertech->save();
                }
            }

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

        $historychangeEquipment = HistoryChange::where('id', $historychange->id)->first();
        
        $equipmentRetrieveId = Equipment::where('serial_number', $request->equipment_id)->first()->id;

        # If there are a mistake in the equipment selected, this code change the availability of the equipment to available
        if ($historychangeEquipment->equipment_id != $equipmentRetrieveId) {
            $availableEquipment = Equipment::where('id', $historychangeEquipment->equipment_id)->first();
            $availableEquipment->availability = true;
            $availableEquipment->save();

            $historychange->equipment_id = Equipment::where('serial_number', $request->equipment_id)->first()->id;
            $available1 = Equipment::where('serial_number', $request->equipment_id)->first();
            $available1->availability = false;
            $available1->save();


        }
        else{
            $historychange->equipment_id = Equipment::where('serial_number', $request->equipment_id)->first()->id;
        }


        if ($request->end_date != "") {
            $historychange->end_date = $request->end_date;

        } else {
            $historychange->end_date = null;
        }

        Log::info($request->state_id);

        $historychange->state_id = ProcessState::where('name', $request->state_id)->first()->id;
        $historychange->location_id = Location::where('name', $request->location_id)->first()->id;
        $historychange->dependency_id = Dependency::where('name', $request->dependency_id)->first()->id;

        $historychange->save();


        // Hacer que por medio de history change encuentre el history user_detail y de esta manera se pueda editar

        HistoryUserDetail::where('history_change_id', $historychange->id)->forceDelete();

        if (is_array($request->users)) {
            foreach ($request->users as $user) {
                $historyuserdetail = new HistoryUserDetail;
                $historyuserdetail->history_change_id = $historychange->id;
                $historyuserdetail->user_id = User::where('name', $user)->first()->id;
                $historyuserdetail->save();
            }
        } else {
            $historyuserdetail = new HistoryUserDetail;
            $historyuserdetail->history_change_id = $historychange->id;
            $historyuserdetail->user_id = User::where('name', $request->users)->first()->id;
            $historyuserdetail->save();
        }


        HistoryTech::where('history_change_id', $historychange->id)->forceDelete();

        if (is_array($request->technician)) {
            foreach ($request->technician as $tech) {
                $historyusertech = new HistoryTech;
                $historyusertech->history_change_id = $historychange->id;
                $historyusertech->user_tech_id = User::where('name', $tech)->first()->id;
                $historyusertech->save();
            }
        } else {
            $historyusertech = new HistoryTech;
            $historyusertech->history_change_id = $historychange->id;
            $historyusertech->user_tech_id = User::where('name', $request->technician)->first()->id;
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
        $history_change_to_destroy = HistoryChange::where('id', $id)->first();
        $history_change_to_compare = HistoryChange::where('equipment_id',$history_change_to_destroy->equipment_id)->get();
        $last_action_history = null;

        for ($i=0; $i < count($history_change_to_compare); $i++) { 
            if($history_change_to_destroy->type_action_id <5 && $history_change_to_compare[$i]->equipment_id == $history_change_to_destroy->equipment_id){
                $last_action_history = $history_change_to_compare[$i];
            }
            else if($history_change_to_destroy->type_action_id >=5){
                $last_action_history = $history_change_to_compare[$i];
            }
        }
        
        if($last_action_history->id == $history_change_to_destroy->id && $last_action_history->type_action_id <5){
            $available1 = Equipment::where('id', $history_change_to_destroy->equipment_id)->first();
            $available1->availability = true;
            $available1->save();
        }

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
        return response()->json([
            "message" => "Movimiento terminado, equipo disponible de nuevo",
        ]);
    }

    public function updateUserCancelMovement(Request $request)
    {

        $data = Encrypt::decryptArray($request->all(), 'id');
        $historychange = HistoryChange::where('id', $request->history_change)->first();
        $historychange->end_date = Carbon::now();
        $historychange->state_id = ProcessState::where('name', $request->state_id)->first()->id;
         
        $historychange->save();


        $type_action_retrive = TypeAction::where('name', $request->type_action_id)->first();
        if($type_action_retrive-> id == 4){
            $available1 = Equipment::where('serial_number', $request->equipment_id)->first();
            $available1->availability = true;
            $available1->save();
        }


        return response()->json([
            "message" => "Movimiento cancelado",
        ]);

    }

    public function updateProcessState(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');
        $historychange = HistoryChange::where('id', $data['id'])->first();
        $historychange->state_id = ProcessState::where('name', $request->state_id)->first()->id;

        if ($historychange->state_id == 4 OR $historychange->state_id == 3) {
            $available1 = Equipment::where('id', $historychange->equipment_id)->first();
            $available1->availability = true;
            $available1->save();
            $historychange->end_date = Carbon::now();
        }
 
        $historychange->save();

        return response()->json([
            "message" => "El estado del proceso ha sido cambiado con exito",
        ]);
    }

    public function finishIncompleteMovement(Request $request)
    {

        $data = Encrypt::decryptArray($request->all(), 'id');
        $historychange = HistoryChange::where('id', $data['id'])->first();
        $historychange->state_id = 3;
        $historychange->end_date = Carbon::now();
        $historychange->save();

        return response()->json([
            "message" => "Cambio realizado",
        ]);
    }

}
