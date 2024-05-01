<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [

                'name'  => 'Ciudadano promedio',
  
                'email' => 'a@gmail.com',
                'password' => 'atol',
                'role_id' => 3

            ],
            [
     
                'name'  => 'Jose Daniel',
                'email' => 'prueba@prueba.com',
                'password' => 'atol',
                'role_id' => 3

            ],
            [

                'name'  => 'Kelvin Alas',
                'email' => 'a1@gmail.com',
                'password' => 'atol',
                'role_id' => 3

            ],
            [
 
                'name'  => 'Jonathan Rivas',
                'email' => 'prueba1@prueba.com',
                'password' => 'atol',
                'role_id' => 3

            ],
            [

                'name'  => 'Mario Reyes',
                'email' => 'a2@gmail.com',
                'password' => 'atol',
                'role_id' => 3

            ],
            [
 
                'name'  => 'Rudy Valiente',
                'email' => 'prueba2@prueba.com',
                'password' => 'atol',
                'role_id' => 3

            ],



        ]);
    }
}
