<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Curso;

class LimpiarComprasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el ID del usuario ale@gmail.com
        $usuario = Usuario::where('correo', 'ale@gmail.com')->first();
        
        if ($usuario) {
            // Eliminar todas las compras del usuario
            DB::table('compras')->where('usuario_id', $usuario->id)->delete();
            
            // Obtener 3 cursos para asignar al usuario
            $cursos = Curso::take(3)->get();
            
            // Crear exactamente 3 compras para el usuario
            foreach ($cursos as $curso) {
                DB::table('compras')->insert([
                    'usuario_id' => $usuario->id,
                    'curso_id' => $curso->id,
                    'fecha' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            $this->command->info('Se han limpiado y recreado las compras para el usuario ale@gmail.com');
        }
    }
}
