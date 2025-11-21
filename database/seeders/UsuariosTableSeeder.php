<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'aldo',
                'apellido' => 'User',
                'correo' => 'aldo@gmail.com',
                'contrasena' => Hash::make('12345678'),
                'rol_id' => 1, // ID de 'admin'
            ],
            [
                'nombre' => 'ale',
                'apellido' => 'User',
                'correo' => 'ale@gmail.com',
                'contrasena' => Hash::make('12345678'),
                'rol_id' => 2, // ID de 'cliente'
            ],
            [
                'nombre' => 'prueba1',
                'apellido' => 'User',
                'correo' => 'prueba1@gmail.com',
                'contrasena' => Hash::make('12345678'),
                'rol_id' => 2, // ID de 'cliente'
            ],
            [
                'nombre' => 'prueba2',
                'apellido' => 'User',
                'correo' => 'prueba2@gmail.com',
                'contrasena' => Hash::make('12345678'),
                'rol_id' => 2, // ID de 'cliente'
            ],
           /* [
                'nombre' => 'Autor',
                'apellido' => 'User',
                'correo' => 'autor@example.com',
                'contrasena' => Hash::make('password'),
                'rol_id' => 3, // ID de 'autor'
            ],*/
        ]);
    }
}
