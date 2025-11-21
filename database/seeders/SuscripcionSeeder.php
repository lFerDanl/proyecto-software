<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// ------------------------
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Plan;

class SuscripcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Serginho',
                'apellido' => 'Cambara',
                'correo' => 'admin@gmail.com',
                'contrasena' => Hash::make('12345678'),
                'rol_id' => 1, // ID de 'admin'
            ],
            [
                'nombre' => 'consumnidor1',
                'apellido' => 'User',
                'correo' => 'consumidor1@gmail.com',
                'contrasena' => Hash::make('12345678'),
                'rol_id' => 2, // ID de 'admin'
            ],
            [
                'nombre' => 'consumnidor2',
                'apellido' => 'User',
                'correo' => 'consumidor2@gmail.com',
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
        Plan::create([             
            'nombre' => 'Mensual',          
            'precio' => 50.00,   
            'dias' => 30,   
        ]);
        Plan::create([    
            
            'nombre' => 'Semestral',          
            'precio' => 300.00,   
            'dias' => 180, 
        ]);
        Plan::create([    
            
            'nombre' => 'Anual',          
            'precio' => 700.00,   
            'dias' => 365, 
        ]);
    }
}
