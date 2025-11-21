<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Categoria;
use App\Models\Usuario;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos= Curso::All();
        $categorias = Categoria::all();
        $userId=session('usuario_id');
        $userNombre=session('usuario_nombre');
        return view('client.courses.create', compact('cursos','categorias','userId','userNombre'));
    }


    public function cursosshow(){
        $cursos= Curso::all();
        return view('client.courses.index', compact('cursos'));
    }



    /*mis cursos creados por mi */
    public function misCursos()
    {
        // Obtener el usuario autenticado con el guard 'usuarios'
        $user = Auth::user();
             // Obtener los cursos creados por el usuario
                $cursos = Curso::where('autor', $user->id)
                ->with('categoria', 'materialesDidacticos')
                ->get();

            return view('client.courses.mis-cursos', compact('cursos'));
    }

    public function detalles(Request $request, $id)
    {
        // Obtener el curso por ID, incluyendo los usuarios y materiales didácticos
        $curso = Curso::with(['materialesDidacticos', 'usuarios'])->findOrFail($id);
        $usuario = Auth::user();
        $modo = $request->query('modo');
        $puedeGestionar = $modo === 'autor'
            && $usuario
            && ($usuario->rol_id == 1 || $curso->autor == $usuario->id);

        // Materiales didácticos asociados al curso
        $materiales = $curso->materialesDidacticos;

        // Usuarios inscritos (para autores/admin)
        $usuarios = $curso->usuarios;

        return view('client.courses.detalles', compact('curso', 'materiales', 'usuarios', 'puedeGestionar'));
    }

    public function show($id)
    {
        $curso = Curso::with('materiales')->findOrFail($id);

        $usuarioId = Auth::id();
        $usuario = $usuarioId ? Usuario::find($usuarioId) : null;
        if (!$usuario) {
            return redirect()->route('singin')->with('error', 'Debes iniciar sesión para acceder a los cursos.');
        }
         

        // // Verificar si el usuario ha comprado el curso
        $haCompradoCurso = $usuario->compras()->where('curso_id', $curso->id)->exists();

        // Verificar si el usuario es suscriptor
        $esSuscriptor = $usuario->es_suscriptor; // Cambia esto según tu lógica de suscripciones

        // // Pasar datos a la vista
        return view('client.courses.show', [
            'curso' => $curso,
            'haCompradoCurso' => $haCompradoCurso,
            'esSuscriptor' => $esSuscriptor,
        ]);

        // return redirect()->route('client.courses.show');
    }

    public function comprar($id)
    {
        if (!Auth::check()) {
            return redirect()->route('singin')->with('error', 'Debes iniciar sesión para comprar un curso.');
        }

        $usuario = Auth::user();
        $curso = Curso::findOrFail($id);

        if ($redirect = $this->verificarAccesoCurso($usuario, $curso)) {
            return $redirect;
        }

        $stripePublicKey = env('STRIPE_KEY');

        return view('client.courses.comprar', [
            'curso' => $curso,
            'stripePublicKey' => $stripePublicKey,
        ]);
    }

    public function procesarCompra(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('singin')->with('error', 'Debes iniciar sesión para comprar un curso.');
        }

        $usuario = Auth::user();
        $curso = Curso::findOrFail($id);

        if ($redirect = $this->verificarAccesoCurso($usuario, $curso)) {
            return $redirect;
        }

        $validated = $request->validate([
            'cardholder_name' => 'required|string|max:255',
            'stripeToken' => 'required|string',
        ]);

        try {
            $stripeSecret = env('STRIPE_SECRET');
            if (empty($stripeSecret)) {
                throw new \Exception('La clave secreta de Stripe no está configurada correctamente en el archivo .env.');
            }

            \Stripe\Stripe::setApiKey($stripeSecret);

            $charge = \Stripe\Charge::create([
                'amount' => (int) round($curso->precio * 100),
                'currency' => 'usd',
                'source' => $validated['stripeToken'],
                'description' => 'Compra del curso: ' . $curso->nombre,
                'metadata' => [
                    'usuario_id' => $usuario->id,
                    'curso_id' => $curso->id,
                    'cardholder' => $validated['cardholder_name'],
                ],
            ]);

            Compra::create([
                'usuario_id' => $usuario->id,
                'curso_id' => $curso->id,
                'fecha' => Carbon::now(),
            ]);

            \Log::info('Compra de curso completada', [
                'usuario_id' => $usuario->id,
                'curso_id' => $curso->id,
                'charge_id' => $charge->id ?? null,
            ]);

            return redirect()->route('curso.detalles', $curso->id)
                ->with('success', '¡Pago realizado correctamente! Ya puedes acceder a este curso.');
        } catch (\Exception $e) {
            \Log::error('Error al procesar pago de curso', [
                'message' => $e->getMessage(),
                'usuario_id' => $usuario->id,
                'curso_id' => $curso->id,
            ]);

            return back()->withErrors([
                'pago' => 'Ha ocurrido un error al procesar el pago: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    private function verificarAccesoCurso(Usuario $usuario, Curso $curso)
    {
        if ($usuario->compras()->where('curso_id', $curso->id)->exists()) {
            return redirect()->route('curso.detalles', $curso->id)
                ->with('success', 'Ya tienes acceso a este curso.');
        }

        if ($usuario->tieneSuscripcionActiva()) {
            return redirect()->route('curso.detalles', $curso->id)
                ->with('info', 'Tu suscripción activa ya te permite acceder a este curso sin comprarlo individualmente.');
        }

        return null;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // dd($request->all());
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'autor' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'precio' => 'required|numeric',
            'tiempo' => 'required|integer',
            'estado' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $cursoData = $request->all();

        // Manejar la carga de la imagen
        if ($request->hasFile('imagen')) {
            $imageName = time() . '.' . $request->imagen->extension();
            $request->imagen->move(public_path('imagenes/cursos'), $imageName);
            $cursoData['imagen'] = 'imagenes/cursos/' . $imageName;
        }
       // dd($cursoData);
        Curso::create($cursoData);

        return redirect()->route('client.courses.create')->with('success', 'Curso creado exitosamente');
    }


    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /* Parte Administrativa*/
    public function indexAdmin()
{
    // Obtener los 10 cursos más vendidos
    $cursosMasVendidos = Curso::with(['categoria', 'autornombre'])
        ->withCount('compras') // Contar el número de compras
        ->orderByDesc('compras_count') // Ordenar por cantidad de compras
        ->take(10) // Limitar a 10 resultados
        ->get();

    // Obtener el curso mejor calificado
   // Curso mejor calificado
   $cursoMejorCalificado = Curso::with('categoria', 'autornombre', 'calificaciones')
   ->withAvg('calificaciones', 'estrellas') // Promedio de calificaciones
   ->orderByDesc('calificaciones_avg_estrellas') // Ordenamos por promedio de calificación
   ->first();

    return view('admin.secciones.CursoCrud', compact('cursosMasVendidos', 'cursoMejorCalificado'));
}


}
