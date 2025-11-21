<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Plan;
use Carbon\Carbon;

class UsuariosSuscripcionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Este seeder relaciona usuarios existentes con planes de suscripción
     */
    public function run(): void
    {
        // Obtener usuarios con rol de cliente
        $usuarios = Usuario::where('rol_id', 2)->get();
        
        // Obtener todos los planes disponibles
        $planes = Plan::all();
        
        if ($usuarios->isEmpty() || $planes->isEmpty()) {
            $this->command->info('No hay usuarios o planes disponibles para crear suscripciones.');
            return;
        }
        
        // Asignar suscripciones a usuarios específicos
        $this->asignarSuscripcion('ale@gmail.com', 'Mensual');
        $this->asignarSuscripcion('prueba1@gmail.com', 'Semestral');
        $this->asignarSuscripcion('prueba2@gmail.com', 'Anual');
        
        // También podemos asignar a los usuarios del SuscripcionSeeder
        $this->asignarSuscripcion('consumidor1@gmail.com', 'Mensual');
        $this->asignarSuscripcion('consumidor2@gmail.com', 'Semestral');
        
        $this->command->info('Suscripciones asignadas correctamente a los usuarios.');
    }
    
    /**
     * Asigna una suscripción a un usuario por su correo y nombre del plan
     */
    private function asignarSuscripcion($correoUsuario, $nombrePlan)
    {
        $usuario = Usuario::where('correo', $correoUsuario)->first();
        $plan = Plan::where('nombre', $nombrePlan)->first();
        
        if (!$usuario || !$plan) {
            $this->command->info("No se encontró el usuario con correo {$correoUsuario} o el plan {$nombrePlan}");
            return;
        }
        
        // Verificar si ya existe una suscripción activa para este usuario
        $suscripcionExistente = DB::table('suscripcions')
            ->where('consumidor_id', $usuario->id)
            ->where('fecha_fin', '>', Carbon::now())
            ->where('estado', true)
            ->first();
            
        if ($suscripcionExistente) {
            $this->command->info("El usuario {$correoUsuario} ya tiene una suscripción activa.");
            return;
        }
        
        // Crear la suscripción
        DB::table('suscripcions')->insert([
            'consumidor_id' => $usuario->id,
            'plan_id' => $plan->id,
            'fecha_inicio' => Carbon::now(),
            'fecha_fin' => Carbon::now()->addDays($plan->dias),
            'estado' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        $this->command->info("Suscripción {$nombrePlan} asignada a {$correoUsuario}");
    }
}
