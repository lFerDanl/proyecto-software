<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Suscripcion;
use App\Models\Curso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stripe;
use Carbon\Carbon;
use Session;
use App\Models\Plan;
use App\Models\Usuario;

// 4242 4242 4242 4242

class CompraController extends Controller
{
    public function compra(){
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('singin')->with('error', 'Debes iniciar sesión para ver tus cursos comprados.');
        }

        $usuario = Auth::user();
        
        // Obtener los cursos comprados por el usuario a través de la relación
        // y eliminar duplicados
        $compras = $usuario->compras()->with('curso.categoria')->get();
        $cursos = $compras->pluck('curso')->unique('id');
        
        // Verificar si el usuario tiene una suscripción activa
        $suscripcionActiva = $usuario->tieneSuscripcionActiva() ? $usuario->getSuscripcionActiva() : null;
        
        // Registrar información para debugging
        \Log::info('Verificando suscripción para usuario', [
            'usuario_id' => $usuario->id,
            'usuario_correo' => $usuario->correo,
            'tiene_suscripcion' => $suscripcionActiva ? 'Sí' : 'No',
            'suscripcion_id' => $suscripcionActiva ? $suscripcionActiva->id : null
        ]);
        
        // Preparar los datos para la vista
        $viewData = [
            'usuario_id' => $usuario->id
        ];
        
        // Añadir la suscripción activa a los datos si existe
        if ($suscripcionActiva) {
            $viewData['suscripcion'] = $suscripcionActiva;
        }
        
        // Determinar qué cursos mostrar
        if ($suscripcionActiva) {
            // Si tiene suscripción activa, mostrar todos los cursos disponibles
            $viewData['curso'] = Curso::all();
            $viewData['tieneSuscripcion'] = true;
        } else if ($cursos->count() > 0) {
            // Si no tiene suscripción pero ha comprado cursos individuales, mostrar solo esos cursos
            $viewData['curso'] = $cursos;
            $viewData['tieneSuscripcion'] = false;
        } else {
            // Si no tiene ni suscripción ni cursos comprados, mostrar una colección vacía
            $viewData['curso'] = collect([]);
            $viewData['tieneSuscripcion'] = false;
            $viewData['sinCursos'] = true; // Indicador para mostrar mensaje especial
        }
        
        return view('client.courses.comprados', $viewData);
    }

    
    
}
