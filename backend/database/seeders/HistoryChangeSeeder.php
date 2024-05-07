<?php

namespace Database\Seeders;

use App\Models\HistoryChange;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HistoryChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        HistoryChange::insert([
            [
                'start_date' => "2024-04-26",
                'end_date' => "2024-04-26",
                "description" => "Se entrego en caja",
                "quantity_out" => 0,
                "quantity_in" => 1,
                "type_action_id" => 1,
                "equipment_id" => 1,
                "equipment_used_in_id" => 1,
                "state_id" => 1,
                "location_id" => 1,
                "dependency_id" => 2,





            ],
            [
                'start_date' => "2024-02-27",
                'end_date' => "2024-02-28",
                "description" => "Se entrego sin cargador",
                "quantity_out" => 0,
                "quantity_in" => 1,
                "type_action_id" => 2,
                "equipment_id" => 2,
                "equipment_used_in_id" => 1,
                "state_id" => 1,
                "location_id" => 2,
                "dependency_id" => 1,

            ],
            [
                'start_date' => "2024-01-02",
                'end_date' => "2024-01-03",
                "description" => "Se realizo un procedimiento",
                "quantity_out" => 0,
                "quantity_in" => 1,
                "type_action_id" => 3,
                "equipment_id" => 1,
                "equipment_used_in_id" => 2,
                "state_id" => 1,
                "location_id" => 1,
                "dependency_id" => 4,





            ],
            [

                'start_date' => "2024-01-04",
                'end_date' => "2024-01-04",

                "description" => "Se coloco sobre la mesa",
                "quantity_out" => 0,
                "quantity_in" => 1,
                "type_action_id" => 4,
                "equipment_id" => 2,
                "equipment_used_in_id" => 1,
                "state_id" => 2,
                "location_id" => 2,
                "dependency_id" => 1,

            ],
        ]);
    }
}
