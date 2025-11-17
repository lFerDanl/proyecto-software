<?php

namespace App\Http\Controllers;


use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Compra;
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
        $usuario = $usuarioId ? Usuario::find($usuarioId) : null;
        $cursosComprados = collect();
        if ($usuario) {
            $cursosComprados = Compra::with('curso')
                ->where('usuario_id', $usuario->id)
                ->get()
                ->pluck('curso')
                ->filter()
                ->unique('id')
                ->values();
        }
        return view('client.plan_estudio.create', compact('cursosComprados'));
    }

    public function generarPlandeestudio(Request $request)
    {
        try {
            $validated = $request->validate([
                'curso_id' => 'required|exists:cursos,id',
            ]);
            $curso = Curso::findOrFail($validated['curso_id']);
            $prompt = 'Genera un plan de estudio en formato JSON para el curso "' . $curso->nombre . '".

            Para cada elemento del plan, proporciona:
            * "nombre"
            * "descripcion"
            * "link"
            * "nivel" ("principiante", "intermedio" o "avanzado")

            La respuesta debe ser exclusivamente un JSON válido con la estructura:
            {
              "principiante": [],
              "intermedio": [],
              "avanzado": []
            }';

            $apiKey = env("GEMINI_API_KEY");
            if (!$apiKey) {
                throw new \Exception('GEMINI_API_KEY no está configurado.');
            }
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


            if ($response->successful()) {
                $data = $response->json();
                $message = data_get($data, 'candidates.0.content.parts.0.text');
                if (!$message) {
                    throw new \Exception('La respuesta de Gemini no contiene texto.');
                }

                // Intenta decodificar el JSON directamente
                $cleanData = json_decode($message, true);

                // Si falla la decodificación, intenta extraer el JSON con una expresión regular
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Imprime la respuesta completa para depurar
                    echo "<pre>";
                    print_r($message);
                    echo "</pre>";

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


                $plan = new \App\Models\PlanEstudio();
                $plan->curso_id = $curso->id;
                $plan->nombre = 'Plan de ' . $curso->nombre;
                $plan->contenido = $cleanData;
                $plan->save();

                $cursos = collect($cleanData);
                return view('client.plan_estudio.show', compact('cursos'));
            } else {
                $errorJson = $response->json();
                $errorMsg = data_get($errorJson, 'error.message');
                if (!$errorMsg) {
                    $errorMsg = $response->body();
                }
                throw new \Exception("Gemini API error: {$response->status()} - {$errorMsg}");
            }
        } catch (\Exception $e) {
            return back()->withErrors(['plan_estudio' => $e->getMessage()]);
        }
    }
}

