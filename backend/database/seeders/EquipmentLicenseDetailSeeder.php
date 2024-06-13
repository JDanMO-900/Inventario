<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EquipmentLicenseDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EquipmentLicenseDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EquipmentLicenseDetail::insert([
        [
            "id" => 1,
            "equipment_id" => 1,
            "license_id" => 1,
            
            
        ],
        [
            "id" => 2,
            "equipment_id" => 2,
            "license_id" => 2,
            
            
        ]
        ]);
    }
}
