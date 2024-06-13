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
                'name' => 'Laptop'
            ],
            [
                'name' => 'All in One'
            ],
            [
                'name' => 'Desktop'
            ],
            [
                'name' => 'Teclado'
            ],
            [
                'name' => 'Mouse'
            ],
            [
                'name' => 'Pantalla interactiva'
            ],
            [
                'name' => 'HDMI'
            ],
            [
                'name' => 'Televisor'
            ],
            [
                'name' => 'Kindle'
            ],
            [
                'name' => 'Tablet'
            ],
            [
                'name' => 'iPad'
            ], 
            [
                'name' => 'UPS'
            ]
        ]);
        
    }
}
