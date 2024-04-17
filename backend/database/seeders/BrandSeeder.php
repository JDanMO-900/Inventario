<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::insert([
            [
                "id" => 1,
                "name" => "Dell",
                
                ],
                [
                "id" => 2,
                "name" => "Lenovo",
                
                ],
                [
                "id" => 3,
                "name" => "Huawei",
                ],
                [
                "id" => 4,
                "name" => "Apple",
                ],
                [
                "id" => 5,
                "name" => "Orbitec",
                ],
                [
                "id" => 6,
                "name" => "Canon",
                ],
               
        ]);
    }
}
