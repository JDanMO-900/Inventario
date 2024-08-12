<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Encrypt;
use App\Models\Brand;
use App\Models\License;
use App\Models\Provider;
use App\Models\Equipment;
use App\Models\Dependency;
use Illuminate\Http\Request;
use App\Models\EquipmentType;

use App\Models\HistoryChange;
use App\Models\EquipmentState;
use App\Models\EquipmentDetail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\TechnicalDescription;
use App\Models\EquipmentLicenseDetail;
use App\Models\Location;
use App\Models\TypeAction;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function equipmentSearch(string $equip)
    {
        return Equipment::equipmentFilter($equip);
    }

    public function availableEquipment()
    {
        return Equipment::availableEquipments();
    }



    public function equipmentInUseByUser($username, Request $request)
    {

        $itemsPerPage = $request->itemsPerPage ?? 10;
        $skip = ($request->page - 1) * $request->itemsPerPage;

        // Getting all the records
        if (($request->itemsPerPage == -1)) {
            $itemsPerPage = Equipment::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0]['key'])) ? $request->sortBy[0]['key'] : 'id';

        $sort = (isset($request->sortBy[0]['order'])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        // $equipment = Equipment::userEquipment($username, $search, $sortBy, $sort, $skip, $itemsPerPage);
        $equipment = Equipment::userEquipment($username, $search, $sortBy, $sort);
        $equipment = Encrypt::encryptObject($equipment, "id");

        $total = Equipment::counterPagination($search);

        return response()->json([
            "message" => "Registros obtenidos correctamente.",
            "data" => $equipment,
            "total" => $total,
        ]);
    }

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
        $sort = (isset($request->sortBy[0]['order'])) ? "asc" : 'desc';
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
        $existingEquipment = Equipment::where('number_active', $request->number_active)
            ->orWhere('serial_number', $request->serial_number)
            ->first();



        if ($existingEquipment) {
            return response()->json(
                [
                    "message" => "Alerta: No fue posible realizar el registro ¡Este equipo ya esta registrado!"
                ]
            );
        } else {
            // availability
            $equipment = new Equipment;
            $equipment->availability = $request->availability;
            $equipment->number_active = $request->number_active;
            $equipment->number_internal_active = $request->number_internal_active;
            $equipment->model = $request->model;
            $equipment->serial_number = $request->serial_number;
            $equipment->adquisition_date = $request->adquisition_date;
            $equipment->invoice_number = $request->invoice_number;
            $equipment->equipment_state_id = EquipmentState::where('name', $request->state)->first()->id;
            $equipment->equipment_type_id = EquipmentType::where('name', $request->equipment_type_id)->first()->id;
            $equipment->brand_id = Brand::where('name', $request->brand)->first()->id;

            if ($request->provider != "") {
                $equipment->provider_id = Provider::where('name', $request->provider)->first()->id;
            } else {
                $equipment->provider_id = null;
            }

            $equipment->save();

            if ($request->licenses) {
                foreach ($request->licenses as $licenseName) {
                    $detalleLicencias = new EquipmentLicenseDetail();
                    $detalleLicencias->equipment_id = Equipment::where('number_active', $request->number_active)->first()->id;
                    $detalleLicencias->license_id = License::where('name', $licenseName)->first()->id;
                    $detalleLicencias->save();
                }
            }

            // Recibe las licencias
            if ($request->technicalAttributes) {
                foreach ($request->technicalAttributes as $technicalAttributes) {
                    $detalleEquipment = new EquipmentDetail();
                    $detalleEquipment->attribute = $technicalAttributes['attribute'];
                    $detalleEquipment->equipment_id = Equipment::where('number_active', $request->number_active)->first()->id;
                    $detalleEquipment->technical_description_id = TechnicalDescription::where('name', $technicalAttributes['technicalDescription'])->first()->id;
                    $detalleEquipment->save();
                }
            }
            // Recibe las licencias

            return response()->json([
                "message" => "Registro creado correctamente.",
            ]);
        }
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

        $existingEquipment = Equipment::where('number_active', $request->number_active)
            ->orWhere('serial_number', $request->serial_number)
            ->first();

        $data = Encrypt::decryptArray($request->all(), 'id');
        $equipment = Equipment::where('id', $data['id'])->first();

        if ($equipment->id == $existingEquipment->id) {

            $equipment->number_active = $request->number_active;
            $equipment->number_internal_active = $request->number_internal_active;
            $equipment->model = $request->model;
            $equipment->serial_number = $request->serial_number;
            $equipment->adquisition_date = $request->adquisition_date;
            $equipment->invoice_number = $request->invoice_number;
            $equipment->equipment_state_id = EquipmentState::where('name', $request->state)->first()->id;
            $equipment->equipment_type_id = EquipmentType::where('name', $request->equipment_type_id)->first()->id;
            $equipment->brand_id = Brand::where('name', $request->brand)->first()->id;
            if ($request->provider != "") {
                $equipment->provider_id = Provider::where('name', $request->provider)->first()->id;
            } else {
                $equipment->provider_id = null;
            }
            
            $equipment->save();

            // Recibe las licencias
            $equipmentId = $equipment->id;
            EquipmentLicenseDetail::where('equipment_id', $equipmentId)->forceDelete();
            foreach ($request->licenses as $licenseName) {
                $detalleLicencias = new EquipmentLicenseDetail();
                $detalleLicencias->equipment_id = Equipment::where('number_active', $request->number_active)->first()->id;
                $detalleLicencias->license_id = License::where('name', $licenseName)->first()->id;
                $detalleLicencias->save();
            }

            EquipmentDetail::where('equipment_id', $equipmentId)->forceDelete();
            // Prueba
            if ($request->technicalAttributes) {
                foreach ($request->technicalAttributes as $technicalAttributes) {
                    $detalleEquipment = new EquipmentDetail();
                    $detalleEquipment->attribute = $technicalAttributes['attribute'];
                    $detalleEquipment->equipment_id = Equipment::where('number_active', $request->number_active)->first()->id;
                    $detalleEquipment->technical_description_id = TechnicalDescription::where('name', $technicalAttributes['technicalDescription'])->first()->id;
                    $detalleEquipment->save();
                }
            }

            return response()->json([
                "message" => "Registro modificado correctamente.",
            ]);
        } else {
            return response()->json(
                [
                    "message" => "Alerta: No fue posible realizar la modificación ¡Este equipo ya esta registrado!"
                ]
            );
        }
    }


    public function updateAvailability(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');
        $equipment = Equipment::where('id', $data['id'])->first();

        if (strtolower($request->availability) == "disponible") {
            $equipment->availability = 0;
        } else {
            $equipment->availability = 1;
        }

        $equipment->save();
        $changeEndUseDate = HistoryChange::where('equipment_id', $equipment->id)
            ->latest()
            ->first();
        $changeEndUseDate->end_date = Carbon::now();
        $changeEndUseDate->save();

        return response()->json([
            "message" => "Disponibilidad cambiada con éxito",
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

    public function getReportGeneral(Request $request)
    {
        $brand = $request->input('brand');

        $history = HistoryChange::getEquipmentData($brand);
        $equipments = [];

        foreach ($history as $equipment) {

            $equipmentData  = [
                "id" => $equipment->id,
                "brand" => $equipment->brand,
                "state" => $equipment->state,
                "type" => $equipment->type,
                "type_action" => $equipment->type_action,
                "location" => $equipment->location,
                "dependency" => $equipment->dependency,
                "number_active" => $equipment->number_active,
                "serial_number" => $equipment->serial_number,
                "description" => $equipment->description,
                "assignment_date" => $equipment->assignment_date,
            ];
            $details  = HistoryChange::getTechniacalDetailData($equipment->id);

            $technicalDetails = [];

            foreach ($details as $detail) {
                $technicalDetails[] = [
                    "component" => $detail->component,
                    "capacity" => $detail->capacity,
                ];
            }

            $equipmentData['technical_details'] = $technicalDetails;

            $equipments[] = $equipmentData;
        }

        return  response()->json([
            "data: " =>  $equipments
        ]);
    }
}
