<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckPlanAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $level
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $level = null)
    {
        // Si no hay usuario autenticado, redirigir al login
        if (!Auth::check()) {
            return redirect()->route('singin')->with('error', 'Debes iniciar sesión para acceder a esta función.');
        }

        $usuario = Auth::user();
        
        // Si el nivel es principiante, permitir acceso a todos
        if ($level === 'principiante') {
            return $next($request);
        }
        
        // Si el nivel es intermedio o avanzado, verificar suscripción activa
        if ($level === 'intermedio' || $level === 'avanzado') {
            // Verificar si el usuario tiene una suscripción activa usando el nuevo método
            if (!$usuario->tieneSuscripcionActiva()) {
                return redirect()->route('plan_estudio.create')
                    ->with('error', 'Necesitas una suscripción activa para acceder a planes de nivel ' . $level . '.');
            }
        }
        
        return $next($request);
    }
}
