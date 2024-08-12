<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\EquipmentLicenseDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([            
            BrandSeeder::class,
            DependencySeeder::class,
            EquipmentStateSeeder::class,
            EquipmentTypeSeeder::class,
            ProviderSeeder::class,
            TechnicalDescriptionSeeder::class,
            RoleSeeder::class,
            UsersSeeder::class,
            LicenseSeeder::class,
            ProcessStateSeeder::class,
            TypeActionSeeder::class,
            LocationSeeder::class,
            // EquipmentSeeder::class,
            // EquipmentLicenseDetailSeeder::class,
            // HistoryChangeSeeder::class,
            // EquipmentDetailSeeder::class,
            // HistoryUserDetailSeeder::class,
            // HistoryTechSeeder::class

        ]);
    }
}
