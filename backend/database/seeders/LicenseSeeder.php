<?php

namespace Database\Seeders;

use App\Models\License;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        License::insert([
            [
             
                "name" => "Office 365",
                
                ],
                [
                    
                    "name" => "Adobe",
                    
                    ],
                ]);
    }
}
