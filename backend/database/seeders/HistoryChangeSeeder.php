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
         
                "location" => "Nivel 2",
                "description" => "Se entrego en area infantil",
                "quantity_out" => 0,
                "quantity_in" => 1,
                "type_action_id" => 1,
                "equipment_id" => 1,
                "equipment_used_in_id" => 1,
                "state_id" => 1,
                

                
                ],
                [
               
                    "location" => "Nivel 3",
                    "description" => "Se entrego en area braile",
                    "quantity_out" => 0,
                    "quantity_in" => 1,
                    "type_action_id" => 2,
                    "equipment_id" => 2,
                    "equipment_used_in_id" => 1,
                    "state_id" => 2,
                    
    
                    
                    ],
        ]);
    }
}
