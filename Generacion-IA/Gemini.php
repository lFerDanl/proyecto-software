<?php

namespace App\Http\Controllers;


use App\Models\Categoria;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PlanEstudioController extends Controller
{

    // Método para mostrar la vista del formulario
    public function create()
    {
        $categorias = Categoria::all();
        return view('client.plan_estudio.create', compact('categorias'));
    }

    public function generarPlandeestudio(Request $request)
    {
        try {
            // Valida los datos recibidos del formulario
            $validated = $request->validate([
                'area_estudio' => 'required|exists:categorias,id',
            ]);

            // Obtén los cursos relacionados al área de estudio seleccionada
            $areaEstudioId = $validated['area_estudio'];
            $cursos = Curso::where('categoria_id', $areaEstudioId)->get();

            // Procesa los datos de los cursos para enviarlos al API
            $data = $cursos->map(function ($curso) {
                return ["id" => $curso->id, "nombre" => $curso->nombre];
            })->toArray();

            // Prompt para Gemini (modificado)
            $prompt = 'Genera un plan de estudio en formato JSON con cursos para la categoría "' . Categoria::find($areaEstudioId)->nombre . '", incluyendo cursos que no estén en la siguiente lista.

            Para cada curso, proporciona:
            * "nombre": Nombre del curso
            * "descripcion": Breve descripción del curso
            * "link": Link a un video de youtube del curso (si está disponible)
            * "nivel": Nivel del curso ("principiante", "intermedio" o "avanzado")

            Aquí está la lista de cursos que ya existen:
            ' . json_encode($data) . '

            La respuesta debe ser exclusivamente un JSON válido con la siguiente estructura:
            {
              "principiante": [ /* cursos de principiante */ ],
              "intermedio": [ /* cursos de intermedio */ ],
              "avanzado": [ /* cursos de avanzado */ ]
            }

            No incluyas explicaciones, comentarios ni texto adicional.';

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


                // No necesitamos aplanar la lista
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

