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
      
                "name" => "mantenimiento",
                "is_internal" => 1

            ],
            [
              
                "name" => "asignación",
                "is_internal" => 1

            ],
            [
            
                "name" => "traslado",
                "is_internal" => 1

            ],
            [
               
                "name" => "préstamo",
                "is_internal" => 0

            ],
            [
               
                "name" => "soporte",
                "is_internal" => 0

            ],
            [
               
                "name" => "solicitud equipo",
                "is_internal" => 0

            ],


            

        ]);
    }
}
