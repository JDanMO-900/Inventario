<?php

namespace Database\Seeders;

use App\Models\EquipmentDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EquipmentDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        EquipmentDetail::insert([
            [

                "attribute" => 100,
                "equipment_id" => 1,
                "technical_description_id" => 1,
            ],
            [

                "attribute" => 200,
                "equipment_id" => 2,
                "technical_description_id" => 2,
                ]
                ]);
        

    }
}
