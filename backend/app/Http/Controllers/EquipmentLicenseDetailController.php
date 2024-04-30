<?php

namespace App\Http\Controllers;

use App\Models\EquipmentLicenseDetail;
use App\Models\Equipment;
use App\Models\License;

use Illuminate\Http\Request;
use Encrypt;

class EquipmentLicenseDetailController extends Controller
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
            $itemsPerPage =  EquipmentLicenseDetail::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0])) ? $request->sortBy[0] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $equipmentlicensedetail = EquipmentLicenseDetail::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $equipmentlicensedetail = Encrypt::encryptObject($equipmentlicensedetail, "id");

        $total = EquipmentLicenseDetail::counterPagination($search);

        return response()->json([
            "message"=>"Registros obtenidos correctamente.",
            "data" => $equipmentlicensedetail,
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
        $equipmentlicensedetail = new EquipmentLicenseDetail;

		$equipmentlicensedetail->equipment_id = Equipment::where('number_active', $request->number_active)->first()->id;
		$equipmentlicensedetail->license_id = License::where('name', $request->name)->first()->id;

        $equipmentlicensedetail->save();

        return response()->json([
            "message"=>"Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EquipmentLicenseDetail  equipmentlicensedetail
     * @return \Illuminate\Http\Response
     */
    public function show(EquipmentLicenseDetail $equipmentlicensedetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EquipmentLicenseDetail  $equipmentlicensedetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $equipmentlicensedetail = EquipmentLicenseDetail::where('id', $data['id'])->first();
		$equipmentlicensedetail->equipment_id = Equipment::where('number_active', $request->number_active)->first()->id;
		$equipmentlicensedetail->license_id = License::where('name', $request->name)->first()->id;

        $equipmentlicensedetail->save();

        return response()->json([
            "message"=>"Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EquipmentLicenseDetail  $equipmentlicensedetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        EquipmentLicenseDetail::where('id', $id)->delete();

        return response()->json([
            "message"=>"Registro eliminado correctamente.",
        ]);
    }
}
