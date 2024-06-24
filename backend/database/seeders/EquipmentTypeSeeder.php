<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EquipmentType::insert([
            [
                'name' => 'Laptop'
            ],
            [
                'name' => 'Laptop Gamer Nitro 5'
            ],
            [
                'name' => 'Laptop Gamer ProArt Studio 16 OLED'
            ],
            [
                'name' => 'Laptop Gamer Victus 16'
            ],
            [
                'name' => 'MacBook Air'
            ],
            [
                'name' => 'Tablet iPad'
            ],
            [
                'name' => 'Tablet iPad Pro'
            ],
            [
                'name' => 'Tablet A7 Lite'
            ],
            [
                'name' => 'Tablet A8'
            ],
            [
                'name' => 'Tablet Kindle '
            ],
            [
                'name' => 'Tablet Kindle Kids'
            ],
            [
                'name' => 'Tablet Fire 7'
            ],
            [
                'name' => 'UPS'
            ],
            [
                'name' => 'Pantalla Interactiva'
            ],
            [
                'name' => 'Desktop'
            ],
            [
                'name' => 'Desktop OptiPlex 7000 SSF'
            ],
            [
                'name' => 'Desktop OptiPlex 7010 SSF'
            ],
            [
                'name' => 'Desktop All In One'
            ],
            [
                'name' => 'Impresor Multifuncional'
            ],
            [
                'name' => 'Proyector'
            ],
            [
                'name' => 'Proyector Interactivo'
            ],
            [
                'name' => 'Teléfono'
            ],
            [
                'name' => 'Mouse'
            ],
            [
                'name' => 'Teclado'
            ],
            [
                'name' => 'Teclado Letra Grande'
            ],
            [
                'name' => 'Disco Duro Externo'
            ],
            [
                'name' => 'Lector Código de Barra'
            ],
            [
                'name' => 'CPU'
            ],
            [
                'name' => 'CPU Gamer'
            ],
            [
                'name' => 'Monitor'
            ],
            [
                'name' => 'Monitor Curvo'
            ],
            [
                'name' => 'Pantalla TV'
            ],
            [
                'name' => 'Sopladora'
            ]
        ]);        
    }
}
