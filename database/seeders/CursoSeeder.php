<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Compra;
use App\Models\Usuario;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear cursos de ejemplo
        $curso1 = Curso::create([
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

        $curso2 = Curso::create([
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

        $curso3 = Curso::create([
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

        $curso4 = Curso::create([
            'nombre' => 'Python para Ciencia de Datos',
            'descripcion' => 'Fundamentos de análisis de datos con Python.',
            'autor' => 2,
            'categoria_id' => 1,
            'precio' => 69.99,
            'tiempo' => '14 horas',
            'estado' => 'publicado',
            'fecha_creacion' => now(),
            'imagen' => 'https://www.python.org/',
        ]);

        $curso5 = Curso::create([
            'nombre' => 'React desde Cero',
            'descripcion' => 'Crea interfaces modernas con React.',
            'autor' => 3,
            'categoria_id' => 3,
            'precio' => 54.99,
            'tiempo' => '11 horas',
            'estado' => 'publicado',
            'fecha_creacion' => now(),
            'imagen' => 'https://react.dev/',
        ]);

        $curso6 = Curso::create([
            'nombre' => 'Bases de Datos con PostgreSQL',
            'descripcion' => 'Diseño y consultas avanzadas en PostgreSQL.',
            'autor' => 2,
            'categoria_id' => 2,
            'precio' => 44.99,
            'tiempo' => '9 horas',
            'estado' => 'publicado',
            'fecha_creacion' => now(),
            'imagen' => 'https://www.postgresql.org/',
        ]);

        $usuarioAle = Usuario::where('correo', 'ale@gmail.com')->first();
        if ($usuarioAle) {
            foreach ([$curso4, $curso5, $curso6] as $curso) {
                Compra::create([
                    'usuario_id' => $usuarioAle->id,
                    'curso_id' => $curso->id,
                    'fecha' => now(),
                ]);
            }
        }
    }
}
