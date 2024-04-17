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
                'id' => 1,
                'name'  => 'Ciudadano promedio',
  
                'email' => 'a@gmail.com',
                'password' => 'atol',
                'role_id' => 2

            ],
            [
                "id" => 2,
                'name'  => 'Jose Daniel',
                'email' => 'prueba@prueba.com',
                'password' => 'atol',
                'role_id' => 3

            ],



        ]);
    }
}
