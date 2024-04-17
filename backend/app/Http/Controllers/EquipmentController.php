<?php

namespace App\Http\Controllers;

use App\Models\License;
use Encrypt;
use App\Models\Brand;
use App\Models\Provider;
use App\Models\Equipment;
use App\Models\Dependency;
use Illuminate\Http\Request;

use App\Models\EquipmentType;
use App\Models\EquipmentState;
use Illuminate\Routing\Controller;
use App\Models\EquipmentLicenseDetail;

class EquipmentController extends Controller
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
            $itemsPerPage = Equipment::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $equipment = Equipment::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $equipment = Encrypt::encryptObject($equipment, "id");

        $total = Equipment::counterPagination($search);

        return response()->json([
            "message" => "Registros obtenidos correctamente.",
            "data" => $equipment,
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


        $equipment = new Equipment;
        $equipment->number_active = $request->number_active;
        $equipment->number_internal_active = $request->number_internal_active;
        $equipment->model = $request->model;
        $equipment->serial_number = $request->serial_number;
        $equipment->adquisition_date = $request->adquisition_date;
        $equipment->invoice_number = $request->invoice_number;

        $equipment->equipment_state_id = EquipmentState::where('name', $request->state)->first()->id;
        $equipment->dependency_id = Dependency::where('name', $request->dependency)->first()->id;
        $equipment->equipment_type_id = EquipmentType::where('name', $request->type)->first()->id;
        $equipment->brand_id = Brand::where('name', $request->brand)->first()->id;
        $equipment->provider_id = Provider::where('name', $request->provider)->first()->id;


        $equipment->deleted_at = $request->deleted_at;

        $equipment->save();


        // REcibe las licencias
        foreach ($request->licenses as $licenseName) {
            $detalleLicencias = new EquipmentLicenseDetail();
            $detalleLicencias->equipment_id = Equipment::where('number_active', $request->number_active)->first()->id;
            $detalleLicencias->license_id = License::where('name', $licenseName)->first()->id;
            $detalleLicencias->save();

        }




        return response()->json([
            "message" => "Registro creado correctamente.",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Equipment  equipment
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $equipment = Equipment::where('id', $data['id'])->first();
        $equipment->number_active = $request->number_active;
        $equipment->number_internal_active = $request->number_internal_active;
        $equipment->model = $request->model;
        $equipment->serial_number = $request->serial_number;
        $equipment->adquisition_date = $request->adquisition_date;
        $equipment->invoice_number = $request->invoice_number;
        $equipment->equipment_state_id = EquipmentState::where('name', $request->state)->first()->id;
        $equipment->dependency_id = Dependency::where('name', $request->dependency)->first()->id;
        $equipment->equipment_type_id = EquipmentType::where('name', $request->type)->first()->id;
        $equipment->brand_id = Brand::where('name', $request->brand)->first()->id;
        $equipment->provider_id = Provider::where('name', $request->provider)->first()->id;
        $equipment->deleted_at = $request->deleted_at;

        $equipment->save();
        // REcibe las licencias
        foreach ($request->licenses as $licenseName) {
            $detalleLicencias = new EquipmentLicenseDetail();
            $detalleLicencias->equipment_id = Equipment::where('number_active', $request->number_active)->first()->id;
            $detalleLicencias->license_id = License::where('name', $licenseName)->first()->id;
            $detalleLicencias->save();

        }

        return response()->json([
            "message" => "Registro modificado correctamente.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = Encrypt::decryptValue($request->id);

        Equipment::where('id', $id)->delete();

        return response()->json([
            "message" => "Registro eliminado correctamente.",
        ]);
    }
}
