<?php

namespace Database\Seeders;

use App\Models\TypeAction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeAction::insert([
            [
      
                "name" => "Daño",

            ],
            [
              
                "name" => "Asignacion",

            ],
            [
            
                "name" => "Traslado",

            ],
            [
               
                "name" => "Prestamo",

            ],
            [
               
                "name" => "Soporte",

            ],
            

        ]);
    }
}
