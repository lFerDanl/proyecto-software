<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscripcionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscripciones')->insert([
            [
                'nombre' => 'Subscripción Básica',
                'descripcion' => 'Acceso a cursos básicos',
                'precio' => 10.99,
                'duracion' => '1 mes',
            ],
            [
                'nombre' => 'Subscripción Premium',
                'descripcion' => 'Acceso a todos los cursos y beneficios adicionales',
                'precio' => 29.99,
                'duracion' => '6 meses',
            ],
        ]);
    }
}
