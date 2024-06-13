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
                'name'  => 'Leonel López',  
                'email' => 'lalopez@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 2
            ],
            [     
                'name'  => 'Jose Daniel',
                'email' => 'jmolina@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ],
            [
                'name'  => 'Kelvin Alas',
                'email' => 'kalas@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ],
            [ 
                'name'  => 'Jonathan Rivas',
                'email' => 'jjmejia@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ],
            [
                'name'  => 'Mario Reyes',
                'email' => 'mreyes@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ],
            [ 
                'name'  => 'Rudy Valiente',
                'email' => 'rvaliente@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ]
        ]);
    }
}
