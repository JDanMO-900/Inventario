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
                "name" => "HP"                
            ], 
            [
                "id" => 2,
                "name" => "ACER"                
            ],   
            [
                "id" => 3,
                "name" => "ASUS"                
            ], 
            [
                "id" => 4,
                "name" => "APPLE"                
            ], 
            [
                "id" => 5,
                "name" => "AMAZON"                
            ], 
            [
                "id" => 6,
                "name" => "SAMSUNG"                
            ], 
            [
                "id" => 7,
                "name" => "FORZA"                
            ], 
            [
                "id" => 8,
                "name" => "ORBITEC"                
            ], 
            [
                "id" => 9,
                "name" => "CPD"                
            ], 
            [
                "id" => 10,
                "name" => "APC"                
            ], 
            [
                "id" => 11,
                "name" => "CENTRA"                
            ], 
            [
                "id" => 12,
                "name" => "HUAWEI"                
            ], 
            [
                "id" => 13,
                "name" => "DELL"                
            ], 
            [
                "id" => 14,
                "name" => "CANNON"                
            ], 
            [
                "id" => 15,
                "name" => "EPSON"                
            ], 
            [
                "id" => 16,
                "name" => "PANASONIC"                
            ], 
            [
                "id" => 17,
                "name" => "LENOVO"                
            ], 
            [
                "id" => 18,
                "name" => "LG"                
            ], 
            [
                "id" => 19,
                "name" => "BOOGIIO"                
            ], 
            [
                "id" => 20,
                "name" => "ZKTCO"                
            ], 
            [
                "id" => 21,
                "name" => "PDVPOS"                
            ], 
            [
                "id" => 22,
                "name" => "GALAXIA"                
            ], 
            [
                "id" => 23,
                "name" => "GRANDSTREAM"                
            ], 
            [
                "id" => 24,
                "name" => "MOTOROLA"                
            ]          
        ]);
    }
}
