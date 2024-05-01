<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Equipment::insert([
            [

                "number_active" => "555",
                "number_internal_active" => "4871",
                "model" => "xert-25",
                "serial_number"=> "len-123-fasdcd-2323",
                "adquisition_date" => "2024-03-26",
                "invoice_number" => "F2024-03-26KSL",
                "equipment_state_id"=> 1,
                
                "equipment_type_id"=> 1,
                "brand_id"=> 1,
                "provider_id"=> 1,
                

                
            ],
            [

                "number_active" => "345",
                "number_internal_active" => "5471",
                "model" => "xert-30",
                "serial_number"=> "len-123-faahcd-2324",
                "adquisition_date" => "2024-03-26",
                "invoice_number" => "F2024-04-26KSL",
                "equipment_state_id"=> 2,
                
                "equipment_type_id"=> 3,
                "brand_id"=> 2,
                "provider_id"=> 2,
                

                
            ],
            [

                "number_active" => "478",
                "number_internal_active" => "422-354-852",
                "model" => "A2685",
                "serial_number"=> "ap-da45785-2536",
                "adquisition_date" => "2024-03-27",
                "invoice_number" => "F2024-04-65jL",
                "equipment_state_id"=> 1,
                
                "equipment_type_id"=> 6,
                "brand_id"=> 3,
                "provider_id"=> 2,
                

                
            ],
            [

                "number_active" => "254-548-345",
                "number_internal_active" => "985-471-858",
                "model" => "RST-2484-82",
                "serial_number"=> "zan-123-faahcd-4325",
                "adquisition_date" => "2024-03-26",
                "invoice_number" => "F2023-04-28wzt",
                "equipment_state_id"=> 3,
                
                "equipment_type_id"=> 10,
                "brand_id"=> 4,
                "provider_id"=> 4,
                

                
                ]
                ]);
    }
}
