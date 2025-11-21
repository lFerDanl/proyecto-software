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
        Categoria::create(['nombre' => 'Tecnología', 'descripcion' => 'Cursos de tecnología']);
        Categoria::create(['nombre' => 'Arte', 'descripcion' => 'Cursos de arte y diseño']);
        Categoria::create(['nombre' => 'Ciencia', 'descripcion' => 'Cursos de ciencia']);
    }
}
