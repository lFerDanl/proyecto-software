<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CategoriaSeeder::class,
            RolesTableSeeder::class,
            UsuariosTableSeeder::class,
            SubscripcionesTableSeeder::class,
    //        MetodosPagoTableSeeder::class,
            RenovacionesTableSeeder::class,
   
            CursoSeeder::class,
            SuscripcionSeeder::class,
            ComprasTableSeeder::class, // Seeder de compras
            UsuariosSuscripcionesSeeder::class, // Relaciona usuarios con suscripciones
        ]);
    }
}
