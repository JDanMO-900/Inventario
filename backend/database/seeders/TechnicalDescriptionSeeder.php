<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TechnicalDescription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TechnicalDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TechnicalDescription::insert([
            [

                "name" => "Procesador",

            ],
            [

                "name" => "Potencia (W)",

            ],
            [

                "name" => "RAM (GB)",
            ],
            [

                "name" => "Disco duro (GB)",
            ],


        ]);
    }
}
