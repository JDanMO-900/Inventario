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
                "name" => "Mantenimiento",
                "is_internal" => 1
            ],
            [              
                "name" => "Asignación",
                "is_internal" => 1
            ],
            [           
                "name" => "Traslado",
                "is_internal" => 1
            ],
            [               
                "name" => "Préstamo",
                "is_internal" => 0
            ],
            [               
                "name" => "Soporte",
                "is_internal" => 0
            ],
            // [               
            //     "name" => "Solicitud equipo",
            //     "is_internal" => 0
            // ]   
        ]);
    }
}
