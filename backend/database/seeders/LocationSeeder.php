<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::insert([
            [
                'name' => 'Nivel -1: Sistema de Parqueo'
            ],
            [
                'name' => 'Nivel 1: Sala de Ventas Edit. ESA'
            ] ,
            [
                'name' => 'Nivel 1: Recepción Zona Norte'
            ] ,
            [
                'name' => 'Nivel 1: Recepción Zona Sur'
            ] ,
            [
                'name' => 'Nivel 1: Cuarto Monitoreo Cerberus'
            ] ,
            [
                'name' => 'Nivel 1: Monitoreo y Videovigilancia'
            ] ,
            [
                'name' => 'Nivel 1: Unidad de Fondos Antiguos'
            ] ,
            [
                'name' => 'Nivel 1: Sala de Consulta Digital'
            ] ,
            [
                'name' => 'Nivel 1: Pozo de Datos P9'
            ] ,
            [
                'name' => 'Nivel 2: Recepción'
            ] ,
            [
                'name' => 'Nivel 2: Primer Infancia'
            ],
            [
                'name' => 'Nivel 2: Pozo de Datos P5'
            ] ,
            [
                'name' => 'Nivel 2: Pasillo Sur'
            ] ,
            [
                'name' => 'Nivel 2: Conservación'
            ] ,
            [
                'name' => 'Nivel 2: Jefatura de Servicios de Información'
            ] ,
            [
                'name' => 'Nivel 2: Agencia Salvadoreña ISBN'
            ] ,
            [
                'name' => 'Nivel 2: Enfermería'
            ] ,
            [
                'name' => 'Nivel 3: Recepción'
            ] ,
            [
                'name' => 'Nivel 3: Área Sensorial'
            ] ,
            [
                'name' => 'Nivel 3: Área Braille'
            ] ,
            [
                'name' => 'Nivel 3: Zona Gamer'
            ],
            [
                'name' => 'Nivel 3: Centro de Cómputo'
            ] ,
            [
                'name' => 'Nivel 3: Pozo de Datos P5'
            ] ,
            [
                'name' => 'Nivel 3: Pozo Eléctrico P7'
            ] ,
            [
                'name' => 'Nivel 3: Pasillo Sur'
            ] ,
            [
                'name' => 'Nivel 4: Recepción'
            ] ,
            [
                'name' => 'Nivel 4: Centro de Computo Capacitaciones'
            ] ,
            [
                'name' => 'Nivel 4: Centro de Computo Uso Público'
            ] ,
            [
                'name' => 'Nivel 4: Pozo de Datos P5'
            ] ,
            [
                'name' => 'Nivel 4: Pasillo Sur'
            ] ,
            [
                'name' => 'Nivel 4: Gerencia de Gestión Bibliotecaria'
            ],
            [
                'name' => 'Nivel 4: Digitalización'
            ] ,
            [
                'name' => 'Nivel 4: Desarrollo de Colecciones'
            ] ,
            [
                'name' => 'Nivel 4: Unidad de Procesamiento Bibliotecario'
            ] ,
            [
                'name' => 'Nivel 4: Unidad de Visitas Educativas'
            ] ,
            [
                'name' => 'Nivel 5: Recepción'
            ] ,
            [
                'name' => 'Nivel 5: Pozo de Datos P5'
            ] ,
            [
                'name' => 'Nivel 5: Pozo de Datos P9'
            ] ,
            [
                'name' => 'Nivel 5: Pozo Eléctrico P9'
            ] ,
            [
                'name' => 'Nivel 5: Pasillo Sur'
            ] ,
            [
                'name' => 'Nivel 5: Sala de Descanso'
            ],
            [
                'name' => 'Nivel 5: Mantenimiento'
            ] ,
            [
                'name' => 'Nivel 5: Dirección'
            ] ,
            [
                'name' => 'Nivel 5: Administración'
            ] ,
            [
                'name' => 'Nivel 5: Oficina Administrativa'
            ] ,
            [
                'name' => 'Nivel 5: Producción, Sonido y Luces'
            ] ,
            [
                'name' => 'Nivel 5: Comunicaciones BINAES'
            ] ,
            [
                'name' => 'Nivel 5: Comunicaciones MICULTURA'
            ] ,
            [
                'name' => 'Nivel 6: Recepción'
            ] ,
            [
                'name' => 'Nivel 6: Centro de Computo'
            ] ,
            [
                'name' => 'Nivel 6: Pozo de Datos P5'
            ],
            [
                'name' => 'Nivel 6: Sala de Lectura Digital'
            ] ,
            [
                'name' => 'Nivel 6: Zona Gamer'
            ] ,
            [
                'name' => 'Nivel 6: Area  Tecnológica'
            ] ,
            [
                'name' => 'Nivel 6: Sala de Reuniones VIP'
            ] ,
            [
                'name' => 'Nivel 6: Sala de Realidad Virtual'
            ] ,
            [
                'name' => 'Nivel 6: Sala de Robótica'
            ] ,
            [
                'name' => 'Nivel 6: Sala de Prototipado'
            ] ,
            [
                'name' => 'Nivel 6: Bodega de Informática'
            ] ,
            [
                'name' => 'Nivel 6: Oficina de Informática'
            ] ,
            [
                'name' => 'Nivel 7: Pozo Datos P2'
            ],
            [
                'name' => 'Archivo General de la Nación: Dirección'
            ] ,
            [
                'name' => 'Archivo General de la Nación: Administración'
            ] ,
            [
                'name' => 'Ministerio de Cultura A5: Unidad de Informática y Sistemas'
            ] ,
            [
                'name' => 'Ministerio de Cultura A5: Unidad de Talento Humano'
            ]
        ]); 
    }
}
