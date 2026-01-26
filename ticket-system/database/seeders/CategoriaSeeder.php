<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Hardware',
                'descripcion' => 'Problemas relacionados con equipos físicos',
            ],
            [
                'nombre' => 'Software',
                'descripcion' => 'Problemas con aplicaciones y programas',
            ],
            [
                'nombre' => 'Red',
                'descripcion' => 'Problemas de conectividad y red',
            ],
            [
                'nombre' => 'Base de Datos',
                'descripcion' => 'Problemas con bases de datos',
            ],
            [
                'nombre' => 'Seguridad',
                'descripcion' => 'Incidentes de seguridad',
            ],
            [
                'nombre' => 'Otro',
                'descripcion' => 'Otros tipos de incidentes',
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
