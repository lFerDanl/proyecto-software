<?php

namespace App\Http\Controllers;


use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Compra;
use App\Models\PlanEstudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class PlanEstudioController extends Controller
{

    // Método para mostrar la vista del formulario
    public function create()
    {
        $usuarioId = Auth::id();
        if (!$usuarioId) {
            return redirect()->route('singin')->with('error', 'Debes iniciar sesión para generar un plan de estudio.');
        }
        
        $usuario = Usuario::find($usuarioId);
        $cursosComprados = collect();
        $tieneSuscripcionActiva = false;
        
        // Verificar si el usuario tiene una suscripción activa
        if ($usuario->tieneSuscripcionActiva()) {
            // Si tiene suscripción activa, mostrar todos los cursos disponibles
            // Obtener todos los cursos sin filtrar por estado
            $cursosComprados = Curso::all();
            $tieneSuscripcionActiva = true;
            
            // Registrar en log para debugging
            \Log::info('Usuario con suscripción activa accediendo a planes de estudio', [
                'usuario_id' => $usuarioId,
                'correo' => $usuario->correo,
                'cantidad_cursos' => $cursosComprados->count(),
                'cursos_ids' => $cursosComprados->pluck('id')->toArray()
            ]);
        } else {
            // Si no tiene suscripción, mostrar solo los cursos comprados
            $cursosComprados = Compra::with('curso')
                ->where('usuario_id', $usuario->id)
                ->get()
                ->pluck('curso')
                ->filter()
                ->unique('id')
                ->values();
                
            // Registrar en log para debugging
            \Log::info('Usuario sin suscripción activa accediendo a planes de estudio', [
                'usuario_id' => $usuarioId,
                'correo' => $usuario->correo,
                'cursos_comprados' => $cursosComprados->count()
            ]);
        }
        
        return view('client.plan_estudio.create', compact('cursosComprados', 'tieneSuscripcionActiva'));
    }

    public function index()
    {
        $usuarioId = Auth::id();
        if (!$usuarioId) {
            return redirect()->route('singin')->with('error', 'Debes iniciar sesión para ver tus planes de estudio.');
        }

        $planes = PlanEstudio::with('curso')
            ->where('usuario_id', $usuarioId)
            ->latest()
            ->get();

        return view('client.plan_estudio.index', compact('planes'));
    }

    public function show(PlanEstudio $plan)
    {
        $usuarioId = Auth::id();
        if (!$usuarioId) {
            return redirect()->route('singin')->with('error', 'Debes iniciar sesión para ver un plan de estudio.');
        }

        if ($plan->usuario_id !== $usuarioId) {
            abort(403, 'No tienes acceso a este plan de estudio.');
        }

        $cursos = collect($plan->contenido ?? []);

        return view('client.plan_estudio.show', [
            'cursos' => $cursos,
            'plan' => $plan,
            'cursoRelacionado' => $plan->curso,
        ]);
    }

    public function generarPlandeestudio(Request $request)
    {
        try {
            $validated = $request->validate([
                'curso_id' => 'required|exists:cursos,id',
                'nivel' => 'required|in:principiante,intermedio,avanzado',
            ]);
            
            // Verificar acceso según nivel
            if ($validated['nivel'] !== 'principiante') {
                $usuario = Auth::user();
                if (!$usuario || !$usuario->tieneSuscripcionActiva()) {
                    return redirect()->route('plan_estudio.create')
                        ->with('error', 'Necesitas una suscripción activa para acceder a planes de nivel ' . $validated['nivel'] . '.');
                }
            }
            
            $curso = Curso::findOrFail($validated['curso_id']);
            $usuario = Auth::user();
            $nivel = $validated['nivel'];
            $prompt = 'Genera un plan de estudio en formato JSON para el curso "' . $curso->nombre . '" con nivel "' . $nivel . '".

            Para cada elemento del plan, proporciona:
            * "nombre"
            * "descripcion"
            * "link"
            * "nivel" ("' . $nivel . '")

            La respuesta debe ser exclusivamente un JSON válido con la estructura:
            {
              "' . $nivel . '": []
            }
            
            Enfócate SOLO en generar contenido para el nivel "' . $nivel . '", con una profundidad y complejidad apropiada para ese nivel.';

            // Configuración de Gemini
            $apiKey = config('services.gemini.api_key');
            if (!$apiKey) {
                throw new \Exception('La clave API de Gemini no está configurada correctamente. Verifica GEMINI_API_KEY en tu archivo .env.');
            }

            \Log::info('Generando plan de estudio con Gemini', [
                'nivel' => $nivel,
                'curso' => $curso->nombre,
            ]);

            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";
            $payload = [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 5000,
                    'temperature' => 0.5,
                    'responseMimeType' => 'application/json',
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($endpoint . "?key={$apiKey}", $payload);

            // Registrar la respuesta completa para depuración
            \Log::info('Respuesta de Gemini', [
                'status' => $response->status(),
                'body_preview' => substr($response->body(), 0, 500) . '...'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $message = data_get($data, 'candidates.0.content.parts.0.text');
                
                if (!$message) {
                    // Registrar el error y la respuesta completa
                    \Log::error('La respuesta de Gemini no contiene texto', [
                        'response' => $data
                    ]);
                    throw new \Exception('La respuesta de Gemini no contiene texto. Por favor, verifica los logs para más detalles.');
                }
                
                // Intenta decodificar el JSON directamente
                $cleanData = json_decode($message, true);

                // Si falla la decodificación, intenta extraer el JSON con una expresión regular
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Registrar el mensaje completo para depuración
                    \Log::warning('Respuesta JSON inválida, intentando extraer JSON válido', [
                        'message' => $message
                    ]);
                    
                    // Extrae el JSON válido
                    if (preg_match('/\{.*\}/s', $message, $matches)) {
                        $message = $matches[0];
                        $cleanData = json_decode($message, true);

                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception('El JSON no es válido después de la extracción. Error: ' . json_last_error_msg());
                        }
                    } else {
                        throw new \Exception('No se encontró un JSON válido en la respuesta.');
                    }
                }

                // Guardar el plan de estudio
                $plan = new PlanEstudio();
                $plan->curso_id = $curso->id;
                $plan->usuario_id = $usuario ? $usuario->id : null;
                $plan->nombre = 'Plan ' . ucfirst($nivel) . ' de ' . $curso->nombre;
                $plan->contenido = $cleanData;
                $plan->nivel = $nivel; // Guardamos el nivel del plan
                $plan->save();

                return redirect()
                    ->route('plan_estudio.show', $plan)
                    ->with('success', 'Plan de estudio generado y guardado correctamente.');
            } else {
                // Registrar el error detallado
                \Log::error('Error en la respuesta de Gemini', [
                    'status' => $response->status(),
                    'error' => $response->body()
                ]);
                $errorJson = $response->json();
                $errorMsg = data_get($errorJson, 'error.message', $response->body());
                throw new \Exception("Error al comunicarse con la API de Gemini: {$response->status()} - {$errorMsg}");
            }
        } catch (\Exception $e) {
            return back()->withErrors(['plan_estudio' => $e->getMessage()]);
        }
    }
}

