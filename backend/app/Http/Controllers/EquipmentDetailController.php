<?php

namespace App\Http\Controllers;

use App\Models\EquipmentDetail;
use App\Models\Equipment;
use App\Models\TechnicalDescription;

use Illuminate\Http\Request;
use Encrypt;

class EquipmentDetailController extends Controller
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
            $itemsPerPage =  EquipmentDetail::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $equipmentdetail = EquipmentDetail::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);




        // $equipmentdetail = Encrypt::encryptObject($equipmentdetail, "id");

        $total = EquipmentDetail::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $equipmentdetail,
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

        $equipment = Equipment::findOrFail( $request->equipment_id );
        $technicalDescription = TechnicalDescription::findOrFail( $request->technical_description_id );



		$equipmentDetail = new EquipmentDetail([
            'attribute' =>$request->attribute,
            'equipment_id' =>$equipment->id,
            'technical_description_id' => $technicalDescription->id
        ]);

        $equipmentDetail->save();

        return response()->json([
            "message"=>"Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EquipmentDetail  equipmentdetail
     * @return \Illuminate\Http\Response
     */
    public function show(EquipmentDetail $equipmentdetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EquipmentDetail  $equipmentdetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $equipmentdetail = EquipmentDetail::where('id', $data['id'])->first();
		$equipmentdetail->attribute = $request->attribute;
		$equipmentdetail->equipment_id = Equipment::where('number_internal_active', $request->number_internal_active)->first()->id;
		$equipmentdetail->technical_description_id = TechnicalDescription::where('name', $request->name)->first()->id;
		$equipmentdetail->deleted_at = $request->deleted_at;

        $equipmentdetail->save();

        return response()->json([
            "message"=>"Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EquipmentDetail  $equipmentdetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        EquipmentDetail::where('id', $id)->delete();

        return response()->json([
            "message"=>"Registro eliminado correctamente.",
        ]);
    }


}
