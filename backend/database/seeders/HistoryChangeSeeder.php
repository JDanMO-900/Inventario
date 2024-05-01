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
