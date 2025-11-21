<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Curso;
use Carbon\Carbon;

class ComprasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el ID del usuario ale@gmail.com
        $usuario = Usuario::where('correo', 'ale@gmail.com')->first();
        
        if ($usuario) {
            // Obtener algunos cursos para asignar al usuario
            $cursos = Curso::take(3)->get();
            
            foreach ($cursos as $curso) {
                DB::table('compras')->insert([
                    'usuario_id' => $usuario->id,
                    'curso_id' => $curso->id,
                    'fecha' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
