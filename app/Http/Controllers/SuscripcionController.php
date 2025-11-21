<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Plan;
use App\Models\Suscripcion;
use Stripe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\Bitacora;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Curso;

class SuscripcionController extends Controller
{
    public function plan(){
        $plan = Plan::all();
        return view('suscripciones/plan', compact('plan'));
    } 

    public function stripe($precio){
        try {
            if (Auth::id()) {

                return view('suscripciones.stripe', compact('precio'));

            } else {
                return redirect()->route('singin'); // Redirige a la ruta del formulario de login
            }
        } catch (\Exception $e) {
            // Manejo del error, por ejemplo, registrar el error y redirigir al usuario a una página de error
            \Log::error('Error en la autenticación: ' . $e->getMessage());
            return redirect()->route('error')->with('error', 'Ha ocurrido un problema. Intente nuevamente.');
        }
    }

    /**
     * Tarjetas de prueba para Stripe:
     * - Tarjeta exitosa: 4242 4242 4242 4242
     * - Tarjeta que requiere autenticación: 4000 0025 0000 3155
     * - Tarjeta declinada: 4000 0000 0000 0002
     * 
     * Para cualquier tarjeta de prueba:
     * - Cualquier fecha futura para expiración
     * - Cualquier CVC de 3 dígitos
     * - Cualquier código postal
     */
    public function stripePost(Request $request, $precio)
    {
        try {
            // Verificar si el usuario está autenticado
            if (!Auth::check()) {
                return redirect()->route('singin')->with('error', 'Debes iniciar sesión para realizar una suscripción.');
            }
            
            // Configurar Stripe con la clave secreta desde el archivo .env
            $stripeSecret = env('STRIPE_SECRET');
            
            if (empty($stripeSecret)) {
                \Log::error('La clave secreta de Stripe no está configurada en el archivo .env');
                return back()->with('error', 'Error de configuración: La clave secreta de Stripe no está configurada.');
            }
            
            \Log::info('Usando clave secreta de Stripe desde .env');
            \Stripe\Stripe::setApiKey($stripeSecret);

            // Crear el cargo en Stripe
            $charge = \Stripe\Charge::create([
                "amount" => $precio * 100, // Stripe maneja cantidades en centavos
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "SUSCRIPCIÓN PLAN: $precio USD"
            ]);
            
            // Registrar información del pago para debugging
            \Log::info('Pago procesado correctamente', [
                'charge_id' => $charge->id,
                'amount' => $precio,
                'status' => $charge->status
            ]);

            // Obtener el usuario actual
            $usuario = Auth::User();
            
            // Registrar información del usuario para debugging
            \Log::info('Usuario realizando suscripción', [
                'usuario_id' => $usuario->id,
                'usuario_correo' => $usuario->correo,
                'usuario_nombre' => $usuario->nombre
            ]);

            // Verificar si el usuario tiene suscripciones activas y desactivarlas
            $suscripcionesActivas = Suscripcion::where('consumidor_id', $usuario->id)
                ->where('fecha_fin', '>', Carbon::now()) // Verificar si no han expirado
                ->where('estado', true) // Verificar que estén activas
                ->get();

            // Desactivar todas las suscripciones activas existentes
            if ($suscripcionesActivas->count() > 0) {
                \Log::info('Desactivando suscripciones activas existentes', [
                    'usuario_id' => $usuario->id,
                    'cantidad_suscripciones' => $suscripcionesActivas->count()
                ]);
                
                foreach ($suscripcionesActivas as $suscripcionExistente) {
                    $suscripcionExistente->estado = false;
                    $suscripcionExistente->save();
                    
                    // Registrar en bitácora la cancelación automática
                    $bitacora = new Bitacora();
                    $bitacora->descripcion = "Cancelación automática de suscripción por nueva compra";
                    $bitacora->usuario_id = $usuario->id;
                    $bitacora->usuario = $usuario->nombre;
                    $bitacora->direccion_ip = $request->ip();
                    $bitacora->navegador = $request->header('user-agent');
                    $bitacora->tabla = "Suscripcions";
                    $bitacora->registro_id = $suscripcionExistente->id;
                    $bitacora->fecha_hora = Carbon::now();
                    $bitacora->save();
                }
            }

            // Obtener el plan correspondiente
            $plan = Plan::where('precio', $precio)->first();

            if (!$plan) {
                return redirect()->route('courses.index')->with('error', 'El plan no existe.');
            }

            // Crear la nueva suscripción
            $suscripcion = new Suscripcion();
            $suscripcion->consumidor_id = $usuario->id;
            $suscripcion->plan_id = $plan->id;
            $suscripcion->fecha_inicio = Carbon::now();
            $suscripcion->fecha_fin = Carbon::now()->addDays($plan->dias); // Duración del plan
            $suscripcion->estado = true;
            $suscripcion->save();
            
            // Registrar información de la suscripción creada
            \Log::info('Suscripción creada exitosamente', [
                'suscripcion_id' => $suscripcion->id,
                'usuario_id' => $usuario->id,
                'plan_id' => $plan->id,
                'fecha_inicio' => $suscripcion->fecha_inicio,
                'fecha_fin' => $suscripcion->fecha_fin
            ]);

            $bitacora = new Bitacora();
            $bitacora->descripcion = "Creación de Suscripcion exitosa";
            $bitacora->usuario_id = $usuario->id;
            $bitacora->usuario = $usuario->nombre;
            $bitacora->direccion_ip = $request->ip();
            $bitacora->navegador = $request->header( 'user-agent');
            $bitacora->tabla = "Suscripcions";
            $bitacora->registro_id = $suscripcion->id;
            $bitacora->fecha_hora = $suscripcion->fecha_inicio;
            $bitacora->save();

            // Mensaje de éxito
            Session::flash('success', '¡PAGO CON ÉXITO!');            
            return redirect()->route('compra')->with('success', 'Pago realizado exitosamente.');
        } catch (\Exception $e) {
            // Registrar el error para debugging
            \Log::error('Error al procesar el pago con Stripe: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            // Mostrar mensaje de error al usuario
            Session::flash('error', 'Error en el pago: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function suscripciones(){
        if(Auth::id()){
            $usuario = Auth::User();     
            $suscripciones = DB::table('usuarios')
            ->join('suscripcions', 'usuarios.id', '=', 'suscripcions.consumidor_id')
            ->join('plans', 'suscripcions.plan_id', '=', 'plans.id')
            ->select(
                'usuarios.nombre as user', 
                'plans.nombre as plan', 
                'suscripcions.fecha_inicio', 
                'suscripcions.fecha_fin', 
                'suscripcions.id', 
                'suscripcions.estado'
            )
            ->where('suscripcions.consumidor_id', '=', $usuario->id)
            ->orderBy('suscripcions.estado', 'desc') // Mostrar activas primero
            ->orderBy('suscripcions.fecha_fin', 'desc') // Ordenar por fecha de fin descendente
            ->get();
            
            // Identificar suscripciones activas y expiradas
            $suscripcionesActivas = $suscripciones->where('estado', true)
                ->where('fecha_fin', '>', Carbon::now()->toDateTimeString());
                
            $suscripcionesCanceladas = $suscripciones->where('estado', false);
            
            $suscripcionesExpiradas = $suscripciones->where('estado', true)
                ->where('fecha_fin', '<=', Carbon::now()->toDateTimeString());
            
            // Registrar información para debugging
            \Log::info('Consultando suscripciones para usuario', [
                'usuario_id' => $usuario->id,
                'usuario_correo' => $usuario->correo,
                'cantidad_suscripciones_totales' => $suscripciones->count(),
                'cantidad_suscripciones_activas' => $suscripcionesActivas->count(),
                'cantidad_suscripciones_canceladas' => $suscripcionesCanceladas->count(),
                'cantidad_suscripciones_expiradas' => $suscripcionesExpiradas->count()
            ]);
                
            return view('suscripciones.misSuscripciones', [
                'suscripciones' => $suscripciones,
                'suscripcionesActivas' => $suscripcionesActivas,
                'suscripcionesCanceladas' => $suscripcionesCanceladas,
                'suscripcionesExpiradas' => $suscripcionesExpiradas,
                'usuario' => $usuario
            ]);
        }
        return redirect()->route('singin');
    }

    public function bitacora()
    {
        $bitacora = Bitacora::all();
        $suscripcion = Suscripcion::all();
        return view('suscripciones.bitacora', compact('bitacora', 'suscripcion'));
    }

    public function estadistica()
    {
        // Obtener el número de suscripciones por plan
        $suscripcionesPorPlan = Suscripcion::select('plan_id', \DB::raw('COUNT(*) as total'))
            ->groupBy('plan_id')
            ->with('plan') // Relación con la tabla de planes(Es el Modelo de laravel)
            ->get();
    
        // Obtener ingresos generados por cada plan
        $ingresosPorPlan = Suscripcion::select('plan_id', \DB::raw('SUM(plans.precio) as ingresos'))
            ->join('plans', 'suscripcions.plan_id', '=', 'plans.id') // Unir con la tabla de planes
            ->groupBy('plan_id')
            ->get();
    
        // Consolidar datos en un formato adecuado para el gráfico
        $estadisticas = $suscripcionesPorPlan->map(function ($suscripcion) use ($ingresosPorPlan) {
            $ingreso = $ingresosPorPlan->firstWhere('plan_id', $suscripcion->plan_id);
        
            return [
                'plan' => $suscripcion->plan->nombre ?? 'Sin Nombre',
                'total' => $suscripcion->total ?? 0,
                'ingresos' => isset($ingreso->ingresos) ? (float) $ingreso->ingresos : 0, // Convertir a número
            ];
        });
        
        // @dd($estadisticas);
        
        // Devolver los datos a la vista
        return view('suscripciones.estadistica', [          
            'estadisticas' => $estadisticas, // Datos consolidados
        ]);
    }     

    /**
     * Cancelar una suscripción activa
     */
    public function cancelarSuscripcion($id)
    {
        try {
            // Verificar si el usuario está autenticado
            if (!Auth::check()) {
                return redirect()->route('singin')->with('error', 'Debes iniciar sesión para cancelar una suscripción.');
            }
            
            $usuario = Auth::user();
            
            // Buscar la suscripción
            $suscripcion = Suscripcion::find($id);
            
            // Verificar si la suscripción existe
            if (!$suscripcion) {
                return redirect()->route('compra')->with('error', 'La suscripción no existe.');
            }
            
            // Verificar que la suscripción pertenezca al usuario autenticado
            if ($suscripcion->consumidor_id != $usuario->id) {
                \Log::warning('Intento de cancelar suscripción de otro usuario', [
                    'usuario_id' => $usuario->id,
                    'suscripcion_id' => $suscripcion->id,
                    'consumidor_id' => $suscripcion->consumidor_id
                ]);
                return redirect()->route('compra')->with('error', 'No tienes permiso para cancelar esta suscripción.');
            }
            
            // Cancelar la suscripción (cambiar estado a false)
            $suscripcion->estado = false;
            $suscripcion->save();
            
            // Registrar la cancelación en la bitácora
            $bitacora = new Bitacora();
            $bitacora->descripcion = "Cancelación de Suscripción";
            $bitacora->usuario_id = $usuario->id;
            $bitacora->usuario = $usuario->nombre;
            $bitacora->direccion_ip = request()->ip();
            $bitacora->navegador = request()->header('user-agent');
            $bitacora->tabla = "Suscripcions";
            $bitacora->registro_id = $suscripcion->id;
            $bitacora->fecha_hora = Carbon::now();
            $bitacora->save();
            
            // Registrar información de la cancelación
            \Log::info('Suscripción cancelada exitosamente', [
                'suscripcion_id' => $suscripcion->id,
                'usuario_id' => $usuario->id,
                'plan_id' => $suscripcion->plan_id,
                'fecha_cancelacion' => Carbon::now()
            ]);
            
            return redirect()->route('compra')->with('success', 'Tu suscripción ha sido cancelada exitosamente.');
            
        } catch (\Exception $e) {
            // Registrar el error
            \Log::error('Error al cancelar suscripción: ' . $e->getMessage(), [
                'exception' => $e,
                'suscripcion_id' => $id
            ]);
            
            return redirect()->route('compra')->with('error', 'Ha ocurrido un error al cancelar tu suscripción. Por favor, inténtalo de nuevo.');
        }
    }
}
