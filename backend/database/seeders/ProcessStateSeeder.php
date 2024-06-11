<?php

namespace Database\Seeders;

use App\Models\ProcessState;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProcessStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProcessState::insert([
            [

                "name" => "Pendiente",

            ],
            [
 
                "name" => "En proceso",

            ],
            [

                "name" => "Finalizado",

            ],
            [

                "name" => "Cancelado",

            ],

        ]);
    }
}
