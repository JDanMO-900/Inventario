<?php

namespace Database\Seeders;

use App\Models\EquipmentState;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EquipmentStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EquipmentState::insert([
            [

                "name" => "Dañado",
                
                ],
                [

                "name" => "Fuera de uso",
                ],
                [

                "name" => "Buen estado",
                ],
                
                
               
        ]);
    }
}
