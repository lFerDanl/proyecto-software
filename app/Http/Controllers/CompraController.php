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

        $curso = Curso::all();
        $usuario = Auth::User();
        $suscripcionActiva = Suscripcion::where('consumidor_id', $usuario->id)
                ->where('fecha_fin', '>', Carbon::now()) // Verificar si no ha expirado
                ->where('estado', true) // Verificar que esté activa
                ->first();
        if ($suscripcionActiva) {
            
            return view('client.courses.comprados', compact('curso'));
        }else{
            return view('suscripciones/plan');
        }
       
    }

    
    
}
