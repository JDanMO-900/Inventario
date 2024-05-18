<?php

namespace Database\Seeders;

use App\Models\HistoryTech;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HistoryTechSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{

    HistoryTech::insert([
        [
            'history_change_id'=>1, 
            'user_tech_id'=>2,

        ],
        [
            'history_change_id'=>2, 
            'user_tech_id'=>2,

        ],
        [
            'history_change_id'=>3, 
            'user_tech_id'=>3,

        ],
        [
            'history_change_id'=>4, 
            'user_tech_id'=>4,

        ],
        
        

    ]);
}
}
