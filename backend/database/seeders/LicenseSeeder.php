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
                "name" => "Microsoft Office 2021 Pro-Plus",
            ],
            [
                "name" => "Eset Endpoint Security",
            ],
            [
                "name" => "Adobe Creative Cloud",
            ],
            [
                "name" => "AutoDesk",
            ]
        ]);
    }
}
