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
                "name" => "Informatica",
                
                ],
                [
                "id" => 2,
                "name" => "Servicios de informacion",
                
                ],
                [
                "id" => 3,
                "name" => "Prototipado",
                ],
                [
                "id" => 4,
                "name" => "Robotica",
                ],
                [
                "id" => 5,
                "name" => "Realidad virtual",
                ],
                [
                "id" => 6,
                "name" => "Zona gamer",
                ],
               
        ]);

    }
}
