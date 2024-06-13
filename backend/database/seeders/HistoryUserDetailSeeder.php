<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\HistoryUserDetail;

class HistoryUserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HistoryUserDetail::insert([
            [
                'history_change_id'=> 1, 
                'user_id' => 1, 
            ],
            [
                'history_change_id' => 2, 
                'user_id'=> 2, 
            ],
            [
                'history_change_id' => 3, 
                'user_id' =>2, 
            ],
            [
                'history_change_id' => 4, 
                'user_id' => 2, 
            ]
        ]);
    }
}
