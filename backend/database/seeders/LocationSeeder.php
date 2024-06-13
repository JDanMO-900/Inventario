<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Location::insert([
            [
                'name' => 'Nivel -1: Parqueo',
            ],
            [
                'name' => 'Nivel 1: Recepción',
            ],
            [
                'name' => 'Nivel 1: Monitoreo',
            ],
            [
                'name' => 'Nivel 2: Técnología',
            ],
            [
                'name' => 'Nivel 3: Cómputo público',
            ],
            [
                'name' => 'Nivel 4: Cómputo público',
            ],
            [
                'name' => 'Nivel 5: Colección general',
            ],
            [
                'name' => 'Nivel 5: Dirección',
            ],
            [
                'name' => 'Nivel 6: Tecnología',
            ],
            [
                'name' => 'Nivel 6: Prototipado',
            ],
            [
                'name' => 'Nivel 6: Robótica',
            ],
            [
                'name' => 'Nivel 7: Área de restaurantes',
            ]  
        ]);
       


    }
}
