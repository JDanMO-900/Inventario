<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Provider::insert([
            [
                "id" => 1,
                "name" => "Zona digital",
                "contact_name" => "Rolando",
                "contact_phone" => "4785-4789",
                "address" => "Metrocentro"                
            ],
            [
                "id" => 2,
                "name" => "Steren",
                "contact_name" => "Marco Antonio Solis",
                "contact_phone" => "7876-4626",
                "address" => "Escalon"                
            ],
            [
                "id" => 3,
                "name" => "Market Place",
                "contact_name" => "Camilo Diaz",
                "contact_phone" => "4785-4789",
                "address" => "En casa"
            ],
            [
                "id" => 4,
                "name" => "Casa Rivas",
                "contact_name" => "Marie Curie",
                "contact_phone" => "4785-4789",
                "address" => "En casa"
            ],
            [
                "id" => 5,
                "name" => "Walmart",
                "contact_name" => "Rene Perez Joglar",
                "contact_phone" => "4785-4789",
                "address" => "En casa"
            ],
            [
                "id" => 6,
                "name" => "PriceSmart",
                "contact_name" => "Felipe PricesMart",
                "contact_phone" => "4785-4789",
                "address" => "Santa Elena"
            ],
            [
                "id" => 7,
                "name" => "Digital Solutions",
                "contact_name" => "Oscar Fuentes",
                "contact_phone" => "2566-4888",
                "address" => "Santa Tecla"
            ],
               
        ]);
        

    }
}
