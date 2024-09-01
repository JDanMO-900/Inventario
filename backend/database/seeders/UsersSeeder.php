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
                'name'  => 'Raisa Ramírez',  
                'email' => 'rramirez@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 2
            ],
            [
                'name'  => 'Edenilson Váldez',  
                'email' => 'evaldez@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 2
            ],  
            [
                'name'  => 'Nataren Bonilla',  
                'email' => 'mbonilla@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ], 
            [
                'name'  => 'Isela Gallardo',  
                'email' => 'igallardo@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ], 
            [
                'name'  => 'Fabricio Corvera',  
                'email' => 'ccorvera@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ], 
            [
                'name'  => 'David Figueroa',  
                'email' => 'dfigueroa@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ], 
            [
                'name'  => 'Armando Ceron',  
                'email' => 'lceron@cultura.gob.sv',
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
                'name'  => 'Mary Paz',  
                'email' => 'pflores@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ], 
            [
                'name'  => 'Josué Palacios',  
                'email' => 'jpalacios@cultura.gob.sv',
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
                'name'  => 'Nilson López',  
                'email' => 'nlopez@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ], 
            [
                'name'  => 'Fernando Vásquez',  
                'email' => 'fvasquez@cultura.gob.sv',
                'password' => '12345',
                'role_id' => 3
            ]        
        ]);
    }
}
