<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        EquipmentType::insert([
            [
                "id" => 1,
                "name" => "Laptop",
                
                ],
                [
                "id" => 2,
                "name" => "All in one",
                
                ],
                [
                "id" => 3,
                "name" => "Pantalla interactiva",
                ],
                [
                "id" => 4,
                "name" => "UPS",
                ],
                [
                "id" => 5,
                "name" => "Monitor",
                ],
                [
                "id" => 6,
                "name" => "Teclado",
                ],
               
        ]);
        
    }
}
