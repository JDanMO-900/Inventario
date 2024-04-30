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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            
            BrandSeeder::class,
            DependencySeeder::class,
            EquipmentStateSeeder::class,
            EquipmentTypeSeeder::class,
            ProviderSeeder::class,
            TechnicalDescriptionSeeder::class,
            RoleSeeder::class,
            UsersSeeder::class,
            EquipmentSeeder::class,
            LicenseSeeder::class,
            EquipmentLicenseDetailSeeder::class,
            ProcessStateSeeder::class,
            TypeActionSeeder::class,
    
 
            LocationSeeder::class,
            HistoryChangeSeeder::class,
            EquipmentDetailSeeder::class,
            HistoryUserDetailSeeder::class,



        ]);
    }
}
