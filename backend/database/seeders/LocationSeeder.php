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
                'name' => 'Nivel 1: Lobby',
            ],
            [
                'name' => 'Nivel 2: primera infancia',
            ],
            [
                'name' => 'Nivel 3: Niñez',
            ],
            [
                'name' => 'Nivel 4: Juvenil',
            ],
            [
                'name' => 'Nivel 5: Colección general',
            ],
            [
                'name' => 'Nivel 6: Tecnología e innovación',
            ],
            [
                'name' => 'Nivel 7: Área de restaurantes',
            ],

           
            
        ]);
       


    }
}
