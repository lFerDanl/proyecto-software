<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear cursos de ejemplo
        Curso::create([
            'nombre' => 'Introducción a Laravel',
            'descripcion' => 'Curso básico para aprender Laravel desde cero.',
            'autor' => 2, // ID de un usuario existente
            'categoria_id' => 1, // ID de una categoría existente
            'precio' => 49.99,
            'tiempo' => '10 horas',
        
            'estado' => 'publicado', // publicado/borrador
            'fecha_creacion' => now(),
            'imagen' => 'https://talently.tech/blog/que-es-laravel/',
        ]);

        Curso::create([
            'nombre' => 'Desarrollo Web con PHP',
            'descripcion' => 'Aprende a crear aplicaciones web con PHP y MySQL.',
            'autor' => 2,
            'categoria_id' => 2,
            'precio' => 39.99,
            'tiempo' => '15 horas',

            'estado' => 'publicado',
            'fecha_creacion' => now(),
            'imagen' => 'https://jrgonzalez.es/string-contain-php',
        ]);

        Curso::create([
            'nombre' => 'JavaScript Avanzado',
            'descripcion' => 'Domina JavaScript con ejemplos prácticos.',
            'autor' => 3,
            'categoria_id' => 3,
            'precio' => 59.99,
            'tiempo' => '12 horas',

            'estado' => 'publicado',
            'fecha_creacion' => now(),
            'imagen' => 'https://escuela.it/cursos/curso-avanzado-javascript',
        ]);
    }
}
