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
                "id" => 1,
                "name" => "Dañado",
                
                ],
                [
                "id" => 2,
                "name" => "Fuera de uso",
                ],
                [
                "id" => 3,
                "name" => "Buen estado",
                ],
                
                
               
        ]);
    }
}
