@extends('Layouts.client')

@section('content')
<div class="container py-4">
  <div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
      <a href="{{ $backUrl ?? url()->previous() }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-2"></i>Volver
      </a>
      <h3 class="mb-0">Sesión</h3>
      <small class="text-muted">Quiz-{{ $quiz['id'] ?? '-' }} • Progreso {{ $sesion['progreso'] ?? 0 }}% • Resultado {{ $sesion['resultadoEvaluacion'] ?? 0 }}%</small>
    </div>
    <div>
      @php $estado = $sesion['estado'] ?? 'en_progreso'; @endphp
      <span class="badge {{ $estado==='completada' ? 'bg-success' : ($estado==='en_progreso' ? 'bg-warning text-dark':'bg-secondary') }}">{{ $estado }}</span>
    </div>
  </div>

  <div class="mb-4">
    <div class="progress" style="height: 8px;">
      <div class="progress-bar" role="progressbar" style="width: {{ $sesion['progreso'] ?? 0 }}%;" aria-valuenow="{{ $sesion['progreso'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
  </div>

  @if(($sesion['estado'] ?? 'en_progreso') === 'completada')
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Resultados</h5>
        @if(isset($quiz['preguntas']))
          <ol class="list-group list-group-numbered">
            @foreach($quiz['preguntas'] as $p)
              @php $resp = collect($respuestas)->firstWhere('pregunta.id', $p['id']); @endphp
              <li class="list-group-item">
                <div class="d-flex justify-content-between">
                  <div>
                    <div class="fw-semibold">{{ $p['enunciado'] }}</div>
                    @if($p['tipo'] === 'opcion_multiple' && is_array($p['opciones']))
                      <ul class="mt-2">
                        @foreach($p['opciones'] as $idx => $opt)
                          <li class="{{ $idx === ($p['respuesta_correcta'] ?? -1) ? 'text-success' : '' }}">{{ $opt }}</li>
                        @endforeach
                      </ul>
                    @endif
                  </div>
                  <div class="text-end">
                    @if($resp)
                      <span class="badge {{ $resp['correcta'] ? 'bg-success' : 'bg-danger' }}">{{ $resp['correcta'] ? 'Correcta' : 'Incorrecta' }}</span>
                      <div class="small text-muted mt-1">Tu respuesta: {{ $resp['respuestaUsuario'] + 1}}</div>
                    @else
                      <span class="badge bg-secondary">Sin respuesta</span>
                    @endif
                  </div>
                </div>
              </li>
            @endforeach
          </ol>
        @endif
      </div>
    </div>
  @else
    @if($siguientePregunta)
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Pregunta</h5>
          <p class="mb-3">{{ $siguientePregunta['enunciado'] }}</p>
          <form method="POST" action="{{ route('client.apuntes.sesion.responder', ['sesionId' => $sesion['id']]) }}">
            @csrf
            <input type="hidden" name="idPregunta" value="{{ $siguientePregunta['id'] }}" />
            @if($siguientePregunta['tipo'] === 'opcion_multiple' && is_array($siguientePregunta['opciones']))
              <div class="list-group mb-3">
                @foreach($siguientePregunta['opciones'] as $idx => $opt)
                  <label class="list-group-item">
                    <input class="form-check-input me-1" type="radio" name="respuestaUsuario" value="{{ $idx }}" required>
                    {{ $opt }}
                  </label>
                @endforeach
              </div>
            @else
              <div class="mb-3">
                <textarea class="form-control" name="respuestaUsuario" rows="4" placeholder="Escribe tu respuesta" required></textarea>
              </div>
            @endif
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-arrow-right-circle me-2"></i>Enviar y continuar
            </button>
          </form>
        </div>
      </div>
    @else
      <div class="alert alert-info">No hay más preguntas. Finaliza la sesión.</div>
    @endif
  @endif
</div>
@endsection