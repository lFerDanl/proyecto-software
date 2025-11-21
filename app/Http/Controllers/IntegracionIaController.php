<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class IntegracionIaController extends Controller
{
    public function apuntesIndex(Request $request)
    {
        $userId = Auth::user()->id ?? null;
        if (!$userId) {
            return redirect()->route('home');
        }

        $medias = DB::table('media')
            ->where('usuarioId', $userId)
            ->orderBy('createdAt', 'desc')
            ->get()
            ->map(function ($m) {
                $transcripciones = DB::table('transcripcion')
                    ->where('videoId', $m->id_media)
                    ->get();

                $apuntesTodos = [];
                foreach ($transcripciones as $t) {
                    $rows = DB::table('apunte_ia')
                        ->whereRaw('"transcripcionId" = ?', [$t->id_transcripcion])
                        ->get();
                    foreach ($rows as $row) {
                        $apuntesTodos[] = $row;
                    }
                }

                $tiposRequeridos = ['resumen','explicacion','flashcard'];
                $tiposCompletados = [];
                foreach ($apuntesTodos as $a) {
                    if (in_array($a->tipo, $tiposRequeridos) && $a->estadoIA === 'completado') {
                        $tiposCompletados[$a->tipo] = true;
                    }
                }
                $apuntes_ready = count($tiposCompletados) === count($tiposRequeridos);

                $tEstados = collect($transcripciones)->pluck('estado_ia')->all();
                $transcripcion_status = 'pendiente';
                if (in_array('error', $tEstados, true)) $transcripcion_status = 'error';
                elseif (in_array('procesando', $tEstados, true)) $transcripcion_status = 'procesando';
                elseif (!empty($tEstados) && count(array_unique($tEstados)) === 1 && $tEstados[0] === 'completado') $transcripcion_status = 'completado';

                return [
                    'id_media' => $m->id_media,
                    'titulo' => $m->titulo,
                    'descripcion' => $m->descripcion,
                    'tipo' => $m->tipo,
                    'estado_procesamiento' => $m->estado_procesamiento,
                    'transcripcion_status' => $transcripcion_status,
                    'apuntes_ready' => $apuntes_ready,
                ];
            });

        return view('client.apuntes.index', [ 'medias' => $medias ]);
    }

    public function apuntesShow($id)
    {
        $media = DB::table('media')->where('id_media', $id)->first();
        if (!$media) return redirect()->route('client.apuntes.index');
        $userId = Auth::user()->id ?? null;

        $transcripciones = DB::table('transcripcion')->where('videoId', $id)->get();
        $apuntes = [ 'resumen' => [], 'explicacion' => [], 'flashcard' => [] ];
        foreach ($transcripciones as $t) {
            $rows = DB::table('apunte_ia')->whereRaw('"transcripcionId" = ?', [$t->id_transcripcion])->get();
            foreach ($rows as $row) {
                if (isset($apuntes[$row->tipo])) {
                    $apuntes[$row->tipo][] = $row;
                }
            }
        }

        $apunteResumen = null;
        if (!empty($apuntes['resumen'])) {
            $apunteResumen = collect($apuntes['resumen'])->sortByDesc('createdAt')->first();
        }
        $temas = [];
        $quizzes = collect();
        if ($apunteResumen) {
            $temas = DB::table('tema_ia')
                ->where('id_apunte', $apunteResumen->id_apunte)
                ->whereNull('id_tema_padre')
                ->orderBy('orden')
                ->get();

            $quizzes = DB::table('quiz_ia')
                ->where('id_apunte', $apunteResumen->id_apunte)
                ->orderBy('createdAt', 'desc')
                ->get();

            // Sesiones por quiz para el usuario actual
            $sesionesByQuiz = collect([]);
            if ($userId && $quizzes->count() > 0) {
                $quizIds = $quizzes->pluck('id_quiz')->all();
                $sesiones = DB::table('sesion_estudio')
                    ->whereIn('quizId', $quizIds)
                    ->where('usuarioId', $userId)
                    ->orderBy('createdAt', 'desc')
                    ->get();
                $sesionesByQuiz = $sesiones->groupBy('quizId');
            }
        }

        return view('client.apuntes.show', [
            'media' => $media,
            'apuntes' => $apuntes,
            'temas' => $temas,
            'apunteResumenId' => $apunteResumen->id_apunte ?? null,
            'quizzes' => $quizzes,
            'sesionesByQuiz' => ($sesionesByQuiz ?? collect())->toArray(),
        ]);
    }

    public function quizzesGenerate($apunteId, Request $request)
    {
        $nestBase = env('NEST_API_URL', 'http://localhost:3001/api');
        $tipo = $request->input('tipo');
        $dificultad = $request->input('dificultad');
        $payload = [ 'apunteId' => (int)$apunteId ];
        if (is_string($tipo)) $payload['tipo'] = $tipo; // 'multiple' | 'abierta' | 'mixto'
        if (is_string($dificultad)) $payload['dificultad'] = $dificultad; // 'facil' | 'media' | 'dificil'
        $resp = Http::asJson()->post(rtrim($nestBase, '/') . '/quiz-ia/apunte', $payload);
        if (!$resp->successful()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Error generando quiz', 'detail' => $resp->body()], 500);
            }
            return redirect()->back()->with('error', 'Error generando quiz');
        }
        if ($request->expectsJson()) {
            return response()->json($resp->json());
        }
        return redirect()->back();
    }

    public function sesionCrear($quizId)
    {
        $userId = Auth::user()->id ?? null;
        if (!$userId) return response()->json(['error' => 'No autenticado'], 401);
        $nestBase = env('NEST_API_URL', 'http://localhost:3001/api');
        $payload = [ 'usuarioId' => (int)$userId, 'quizId' => (int)$quizId ];
        $resp = Http::asJson()->post(rtrim($nestBase, '/') . '/sesion-estudio', $payload);
        if (!$resp->successful()) {
            return response()->json(['error' => 'Error creando sesión', 'detail' => $resp->body()], 500);
        }
        $sesion = $resp->json();
        $sesionId = $sesion['id'] ?? null;
        if ($sesionId) {
            return redirect()->route('client.apuntes.sesion.ver', ['sesionId' => $sesionId]);
        }
        return redirect()->back();
    }

    public function sesionVer($sesionId)
    {
        $nestBase = env('NEST_API_URL', 'http://localhost:3001/api');

        $sesionResp = Http::get(rtrim($nestBase, '/') . '/sesion-estudio/' . $sesionId);
        if (!$sesionResp->successful()) {
            return redirect()->back()->with('error', 'No se pudo obtener la sesión');
        }
        $sesion = $sesionResp->json();

        $quizId = $sesion['quiz']['id'] ?? null;
        $quiz = null;
        $backUrl = null;
        if ($quizId) {
            $quizResp = Http::get(rtrim($nestBase, '/') . '/quiz-ia/' . $quizId);
            $quiz = $quizResp->successful() ? $quizResp->json() : null;

            // Construir backUrl a /apuntes/media/{videoId}
            if ($quiz && isset($quiz['apunte']['id'])) {
                $apunteRow = DB::table('apunte_ia')->where('id_apunte', $quiz['apunte']['id'])->first();
                if ($apunteRow) {
                    $transRow = DB::table('transcripcion')->where('id_transcripcion', $apunteRow->transcripcionId)->first();
                    if ($transRow && isset($transRow->videoId)) {
                        $backUrl = route('client.apuntes.show', ['id' => $transRow->videoId]);
                    }
                }
            }
        }

        $respuestasResp = Http::get(rtrim($nestBase, '/') . '/respuesta/sesion/' . $sesionId);
        $respuestas = $respuestasResp->successful() ? $respuestasResp->json() : [];

        // Determinar siguiente pregunta
        $respondidasIds = collect($respuestas)->pluck('pregunta.id')->all();
        $siguientePregunta = null;
        if ($quiz && isset($quiz['preguntas'])) {
            foreach ($quiz['preguntas'] as $p) {
                if (!in_array($p['id'], $respondidasIds)) {
                    $siguientePregunta = $p;
                    break;
                }
            }
        }

        return view('client.apuntes.sesion', [
            'sesion' => $sesion,
            'quiz' => $quiz,
            'respuestas' => $respuestas,
            'siguientePregunta' => $siguientePregunta,
            'backUrl' => $backUrl,
        ]);
    }

    public function sesionResponder($sesionId, Request $request)
    {
        $userId = Auth::user()->id ?? null;
        if (!$userId) return redirect()->back()->with('error', 'No autenticado');
        $payload = $request->validate([
            'idPregunta' => 'required|integer',
            'respuestaUsuario' => 'required|string',
        ]);

        $nestBase = env('NEST_API_URL', 'http://localhost:3001/api');
        $body = [
            'idPregunta' => (int)$payload['idPregunta'],
            'idUsuario' => (int)$userId,
            'idSesion' => (int)$sesionId,
            'respuestaUsuario' => (string)$payload['respuestaUsuario'],
        ];
        $resp = Http::asJson()->post(rtrim($nestBase, '/') . '/respuesta', $body);
        if (!$resp->successful()) {
            return redirect()->back()->with('error', 'No se pudo registrar la respuesta');
        }
        return redirect()->route('client.apuntes.sesion.ver', ['sesionId' => $sesionId]);
    }
    public function vistaUpload()
    {
        return view('client.apuntes.ia-media');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:512000',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:VIDEO,AUDIO',
        ]);

        $userId = Auth::user()->id ?? null;
        if (!$userId) {
            return back()->withErrors(['auth' => 'Usuario no autenticado']);
        }

        $file = $request->file('file');
        $nestBase = env('NEST_API_URL', 'http://localhost:3001/api');

        $response = Http::attach(
                'file',
                fopen($file->getRealPath(), 'r'),
                $file->getClientOriginalName()
            )
            ->post(rtrim($nestBase, '/') . '/media/upload', [
                'idUsuario' => (string)$userId,
                'titulo' => $request->input('titulo'),
                'descripcion' => $request->input('descripcion'),
                'tipo' => $request->input('tipo'),
            ]);

        if (!$response->successful()) {
            return back()->withErrors(['upload' => 'Error subiendo media: ' . $response->body()])->withInput();
        }

        $payload = $response->json();
        $media = $payload['media'] ?? null;

        if ($request->expectsJson()) {
            return response()->json([
                'upload_ok' => true,
                'media_id' => $media['id'] ?? null,
                'message' => $payload['message'] ?? '',
            ]);
        }

        return redirect()->route('client.apuntes.index')
            ->with('upload_ok', true)
            ->with('media_id', $media['id'] ?? null)
            ->with('message', $payload['message'] ?? '');
    }

    public function status($id)
    {
        $nestBase = env('NEST_API_URL', 'http://localhost:3001');

        $mediaResp = Http::get(rtrim($nestBase, '/') . '/media/' . $id);
        if (!$mediaResp->successful()) {
            return response()->json(['error' => 'No se pudo obtener media'], 500);
        }
        $media = $mediaResp->json();

        $transResp = Http::get(rtrim($nestBase, '/') . '/transcripciones/video/' . $id);
        $transcripciones = $transResp->successful() ? $transResp->json() : [];

        $apuntes = [];
        foreach ($transcripciones as $t) {
            $tid = $t['id'] ?? null;
            if ($tid) {
                $rows = DB::table('apunte_ia')->whereRaw('"transcripcionId" = ?', [$tid])->get();
                foreach ($rows as $row) {
                    $apuntes[] = [
                        'id' => $row->id_apunte,
                        'tipo' => $row->tipo,
                        'titulo' => $row->titulo,
                        'contenido' => $row->contenido,
                        'estadoIA' => $row->estadoIA,
                        'transcripcionId' => $row->transcripcionId,
                    ];
                }
            }
        }

        return response()->json([
            'media' => $media,
            'transcripciones' => $transcripciones,
            'apuntes' => $apuntes,
        ]);
    }

    public function temasList($mediaId)
    {
        $transcripciones = DB::table('transcripcion')->where('videoId', $mediaId)->pluck('id_transcripcion');
        if ($transcripciones->isEmpty()) return response()->json(['temas' => [], 'apunteResumenId' => null]);
        $apunteResumen = DB::table('apunte_ia')
            ->whereIn('transcripcionId', $transcripciones->all())
            ->where('tipo', 'resumen')
            ->orderBy('createdAt', 'desc')
            ->first();
        if (!$apunteResumen) return response()->json(['temas' => [], 'apunteResumenId' => null]);
        $temas = DB::table('tema_ia')
            ->where('id_apunte', $apunteResumen->id_apunte)
            ->whereNull('id_tema_padre')
            ->orderBy('orden')
            ->get();
        $temasProcesados = $temas->map(function ($t) {
            $estructura = is_string($t->estructura) ? json_decode($t->estructura, true) : (array)($t->estructura ?? []);
            if (!empty($estructura['secciones']) && is_array($estructura['secciones'])) {
                foreach ($estructura['secciones'] as $idx => $sec) {
                    $contenido = is_array($sec) ? ($sec['contenido'] ?? '') : (isset($sec->contenido) ? $sec->contenido : '');
                    $estructura['secciones'][$idx]['contenido_html'] = \Illuminate\Support\Str::markdown($contenido);
                }
            }
            $t->estructura = $estructura;
            return $t;
        });
        return response()->json(['temas' => $temasProcesados, 'apunteResumenId' => $apunteResumen->id_apunte]);
    }

    public function temasGenerar($mediaId)
    {
        $transcripciones = DB::table('transcripcion')->where('videoId', $mediaId)->pluck('id_transcripcion');
        if ($transcripciones->isEmpty()) return response()->json(['error' => 'Sin transcripciones'], 400);
        $apunteResumen = DB::table('apunte_ia')
            ->whereIn('transcripcionId', $transcripciones->all())
            ->where('tipo', 'resumen')
            ->orderBy('createdAt', 'desc')
            ->first();
        if (!$apunteResumen) return response()->json(['error' => 'Sin apunte resumen'], 400);

        $nestBase = env('NEST_API_URL', 'http://localhost:3001/api');
        $resp = Http::post(rtrim($nestBase, '/') . '/tema-ia/' . $apunteResumen->id_apunte . '/generate');
        if (!$resp->successful()) {
            return response()->json(['error' => 'Error generando temas', 'detail' => $resp->body()], 500);
        }
        return response()->json(['ok' => true]);
    }

    public function temasProfundizar($temaId, Request $request)
    {
        $tipo = $request->input('tipoExpansion', 'profundizar');
        $nestBase = env('NEST_API_URL', 'http://localhost:3001/api');
        $resp = Http::post(rtrim($nestBase, '/') . '/tema-ia/' . $temaId . '/profundizar', [
            'tipoExpansion' => $tipo,
        ]);
        if (!$resp->successful()) {
            return response()->json(['error' => 'Error profundizando tema', 'detail' => $resp->body()], 500);
        }
        return response()->json(['ok' => true]);
    }

    public function temasAddSeccion($temaId, Request $request)
    {
        $payload = $request->validate([
            'tipoSeccion' => 'required|string',
            'titulo' => 'required|string|max:200',
            'contenido' => 'required|string',
        ]);
        $nestBase = env('NEST_API_URL', 'http://localhost:3001/api');
        $resp = Http::post(rtrim($nestBase, '/') . '/tema-ia/' . $temaId . '/secciones', $payload);
        if (!$resp->successful()) {
            return response()->json(['error' => 'Error agregando sección', 'detail' => $resp->body()], 500);
        }
        return response()->json(['ok' => true]);
    }

    public function recomendacionesQuiz($quizId)
    {
        $userId = Auth::user()->id ?? null;
        if (!$userId) return response()->json(['error' => 'No autenticado'], 401);
        $nestBase = env('NEST_API_URL', 'http://localhost:3001/api');
        $resp = Http::get(rtrim($nestBase, '/') . '/sesion-estudio/recomendaciones/' . (int)$userId . '/quiz/' . (int)$quizId);
        if (!$resp->successful()) {
            return response()->json(['error' => 'Error obteniendo recomendaciones', 'detail' => $resp->body()], 500);
        }
        return response()->json($resp->json());
    }
}