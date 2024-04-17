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
                "id" => 1,
                "name" => "Pendiente",

            ],
            [
                "id" => 2,
                "name" => "En proceso",

            ],
            [
                "id" => 3,
                "name" => "Finalizado",

            ],

        ]);
    }
}
