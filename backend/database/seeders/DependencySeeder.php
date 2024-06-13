<?php

namespace Database\Seeders;

use App\Models\Dependency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DependencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        Dependency::insert([
            [
                "id" => 1,
                "name" => "BINAES"            
            ],
            [
                "id" => 2,
                "name" => "Archivo General de la Nación"            
            ],
            [
                "id" => 3,
                "name" => "Ministerio de Cultura, Edif. A5"
            ]               
        ]);

    }
}
