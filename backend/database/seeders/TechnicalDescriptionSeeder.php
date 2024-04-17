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
                "id" => 1,
                "name" => "Equipo nuevo de caja",
                
                ],
                [
                "id" => 2,
                "name" => "Se realizaron modificaciones",
                
                ],
                [
                "id" => 3,
                "name" => "Se devolvio por motivos de garantia",
                ],
                
               
        ]);
    }
}
