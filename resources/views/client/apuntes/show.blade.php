@extends('Layouts.client')

@section('content')
<div class="container py-4">
  <div class="mb-4">
    <a href="{{ route('client.apuntes.index') }}" class="btn btn-outline-secondary mb-3">
      <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
    <h2 class="mb-2">{{ $media->titulo }}</h2>
    <p class="text-muted">{{ $media->descripcion }}</p>
  </div>

  <!-- Tabs de navegación -->
  <ul class="nav nav-pills mb-4" id="apuntesTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="resumen-tab" data-bs-toggle="pill" data-bs-target="#resumen" type="button" role="tab" aria-controls="resumen" aria-selected="true">
        <i class="bi bi-file-text me-2"></i>Resumen
        <span class="badge bg-light text-dark ms-2">{{ count($apuntes['resumen']) }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="explicacion-tab" data-bs-toggle="pill" data-bs-target="#explicacion" type="button" role="tab" aria-controls="explicacion" aria-selected="false">
        <i class="bi bi-book me-2"></i>Explicación
        <span class="badge bg-light text-dark ms-2">{{ count($apuntes['explicacion']) }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="flashcards-tab" data-bs-toggle="pill" data-bs-target="#flashcards" type="button" role="tab" aria-controls="flashcards" aria-selected="false">
        <i class="bi bi-card-list me-2"></i>Flashcards
        <span class="badge bg-light text-dark ms-2">{{ count($apuntes['flashcard']) }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="temas-tab" data-bs-toggle="pill" data-bs-target="#temas" type="button" role="tab" aria-controls="temas" aria-selected="false">
        <i class="bi bi-diagram-3 me-2"></i>Temas
        <span class="badge bg-light text-dark ms-2">{{ isset($temas) ? count($temas) : 0 }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="evaluacion-tab" data-bs-toggle="pill" data-bs-target="#evaluacion" type="button" role="tab" aria-controls="evaluacion" aria-selected="false">
        <i class="bi bi-clipboard-check me-2"></i>Evaluación
        <span class="badge bg-light text-dark ms-2" id="badgeQuizzesCount">{{ isset($quizzes) ? count($quizzes) : 0 }}</span>
      </button>
    </li>
  </ul>

  <!-- Contenido de los tabs -->
  <div class="tab-content" id="apuntesTabContent">
    <x-apuntes.resumen :apuntes="$apuntes['resumen']" />
    <x-apuntes.explicacion :apuntes="$apuntes['explicacion']" />
    <x-apuntes.flashcards :apuntes="$apuntes['flashcard']" />
    <x-apuntes.temas :temas="$temas" :mediaId="$media->id_media" :apunteResumenId="$apunteResumenId" />
    <div class="tab-pane fade" id="evaluacion" role="tabpanel" aria-labelledby="evaluacion-tab">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Quizzes del apunte</h5>
        @if($apunteResumenId)
          <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm w-auto" id="selTipoQuiz">
              <option value="multiple" selected>multiple</option>
              <option value="abierta">abierta</option>
              <option value="mixto">mixto</option>
            </select>
            <select class="form-select form-select-sm w-auto" id="selDificultadQuiz">
              <option value="facil">facil</option>
              <option value="media" selected>media</option>
              <option value="dificil">dificil</option>
            </select>
            <button type="button" class="btn btn-primary" id="btnGenerarQuiz" data-apunte-id="{{ (int)$apunteResumenId }}" data-url="{{ route('client.apuntes.quizzes.generar', ['apunteId' => $apunteResumenId]) }}">
              <i class="bi bi-plus-lg me-2"></i>Generar nuevo quiz
            </button>
          </div>
        @endif
      </div>
      @if(isset($quizzes) && count($quizzes) > 0)
        <div class="list-group" id="quizzesContainer">
          @foreach($quizzes as $q)
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
            <span class="fw-semibold">Quiz {{ $loop->iteration }}</span>
                <span class="badge bg-secondary ms-2">{{ $q->tipo }}</span>
                <span class="badge bg-info ms-2 text-dark">{{ $q->dificultad }}</span>
              </div>
              <div class="d-flex gap-2">
                <form method="POST" action="{{ route('client.apuntes.quizzes.sesion', ['quizId' => $q->id_quiz]) }}">
                  @csrf
                  <button type="submit" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-play-circle me-1"></i>Crear sesión de estudio
                  </button>
                </form>
                <button type="button" class="btn btn-outline-primary btn-sm" data-reco-url="{{ route('client.apuntes.quizzes.recomendaciones', ['quizId' => $q->id_quiz]) }}" data-reco-target="reco-quiz-{{ $q->id_quiz }}">
                  <i class="bi bi-lightbulb me-1"></i>Recomendaciones
                </button>
                @php $sesionUrl = route('client.apuntes.sesion.ver', ['sesionId' => '__ID__']); @endphp
              </div>
            </div>
            <div class="list-group-item" id="reco-quiz-{{ $q->id_quiz }}" style="display:none"></div>
            @php $sesiones = $sesionesByQuiz[$q->id_quiz] ?? []; @endphp
            @if(!empty($sesiones))
              <div class="px-3 py-2 text-muted">Sesiones recientes</div>
              <ul class="list-group list-group-flush">
                @foreach($sesiones as $s)
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <span class="small">Sesión</span>
                      <span class="small ms-2 text-muted">{{ $s->fecha_inicio }}</span>
                      <span class="badge {{ $s->estado==='completada' ? 'bg-success' : ($s->estado==='en_progreso' ? 'bg-warning text-dark' : 'bg-secondary') }} ms-2">{{ $s->estado }}</span>
                      <span class="small ms-2">Progreso {{ number_format($s->progreso ?? 0, 0) }}%</span>
                      <span class="small ms-2">Resultado {{ number_format($s->resultado_evaluacion ?? 0, 0) }}%</span>
                    </div>
                    <a href="{{ route('client.apuntes.sesion.ver', ['sesionId' => $s->id_sesion]) }}" class="btn btn-sm btn-outline-primary">
                      {{ $s->estado==='completada' ? 'Ver' : 'Continuar' }}
                    </a>
                  </li>
                @endforeach
              </ul>
            @endif
          @endforeach
        </div>
      @else
        <div class="alert alert-secondary" id="quizzesEmpty">Aún no hay quizzes para este apunte.</div>
      @endif
    </div>
  </div>
</div>

<x-apuntes.styles />
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const btnGenerarQuiz = document.getElementById('btnGenerarQuiz');
    const quizzesContainer = document.getElementById('quizzesContainer');
    const quizzesEmpty = document.getElementById('quizzesEmpty');
    const badgeQuizzesCount = document.getElementById('badgeQuizzesCount');
    const selTipoQuiz = document.getElementById('selTipoQuiz');
    const selDificultadQuiz = document.getElementById('selDificultadQuiz');
    if (!btnGenerarQuiz) return;
    btnGenerarQuiz.addEventListener('click', async () => {
      const tipo = selTipoQuiz ? selTipoQuiz.value : 'multiple';
      const dificultad = selDificultadQuiz ? selDificultadQuiz.value : 'media';
      const url = btnGenerarQuiz.getAttribute('data-url');
      btnGenerarQuiz.disabled = true;
      btnGenerarQuiz.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generando...';
      try {
        const res = await fetch(url, { 
          method: 'POST', 
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/json' },
          body: JSON.stringify({ tipo, dificultad })
        });
        if (!res.ok) throw new Error('error');
        const quiz = await res.json();
        const qid = quiz.id || quiz.id_quiz;
        const tipoResp = quiz.tipo || tipo;
        const difResp = quiz.dificultad || dificultad;
        const index = (function() {
          try {
            if (!quizzesContainer) return 1;
            const count = quizzesContainer.querySelectorAll(':scope > .list-group-item').length;
            return count + 1;
          } catch (_) {
            return (parseInt(badgeQuizzesCount?.textContent||'0',10)||0) + 1;
          }
        })();
        const formHtml = `
          <form method="POST" action="{{ route('client.apuntes.quizzes.sesion', ['quizId' => '__QID__']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-outline-success btn-sm">
              <i class="bi bi-play-circle me-1"></i>Crear sesión de estudio
            </button>
          </form>`;
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center';
        item.innerHTML = `
          <div>
            <span class="fw-semibold">Quiz ${index}</span>
            <span class="badge bg-secondary ms-2">${tipoResp}</span>
            <span class="badge bg-info ms-2 text-dark">${difResp}</span>
          </div>
          <div class="d-flex gap-2">${formHtml.replace('__QID__', qid)}
            <button type="button" class="btn btn-outline-primary btn-sm" data-reco-url="{{ route('client.apuntes.quizzes.recomendaciones', ['quizId' => '__QID__']) }}" data-reco-target="reco-quiz-${qid}">
              <i class="bi bi-lightbulb me-1"></i>Recomendaciones
            </button>
          </div>
        `;
        if (quizzesContainer) {
          quizzesContainer.prepend(item);
        }
        const recoDiv = document.createElement('div');
        recoDiv.className = 'list-group-item';
        recoDiv.id = `reco-quiz-${qid}`;
        recoDiv.style.display = 'none';
        quizzesContainer.prepend(recoDiv);
        if (quizzesEmpty) quizzesEmpty.remove();
        if (badgeQuizzesCount) {
          const n = parseInt(badgeQuizzesCount.textContent || '0', 10) || 0;
          badgeQuizzesCount.textContent = (n + 1).toString();
        }
      } catch (_) {
      } finally {
        btnGenerarQuiz.disabled = false;
        btnGenerarQuiz.innerHTML = '<i class="bi bi-plus-lg me-2"></i>Generar nuevo quiz';
      }
    });
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest('[data-reco-url]');
      if (!btn) return;
      const url = btn.getAttribute('data-reco-url').replace('__QID__', btn.getAttribute('data-reco-target').replace('reco-quiz-', ''));
      const targetId = btn.getAttribute('data-reco-target');
      const panel = document.getElementById(targetId);
      if (!panel) return;
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Cargando';
      try {
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('error');
        const data = await res.json();
        const recs = data.recomendaciones || [];
        const stats = data.estadisticas || {};
        const explicacion = data.explicacion || '';
        const det = data.sesionesDetalle || [];
        const detRows = det.map(d => `<tr><td>Sesión (${new Date(d.fechaInicio).toLocaleString()})</td><td>${d.errores}</td><td>${Math.round(d.resultadoEvaluacion||0)}%</td></tr>`).join('');
        const items = recs.map(r => `<li class="list-group-item"><div><strong>${r.titulo}</strong><div class="small text-muted">${r.descripcion||''}</div></div></li>`).join('');
        const header = `<div class="d-flex justify-content-between align-items-center"><div class="text-muted small">Sesiones: ${stats.sesionesAnalizadas||0} • Incorrectas: ${stats.totalRespuestasIncorrectas||0} • Éxito: ${Math.round((stats.porcentajeExito||0))}%</div><button class="btn btn-sm btn-outline-secondary" data-close-reco="${targetId}">Cerrar</button></div>`;
        const detalleTabla = det.length ? `<div class="mt-2"><table class="table table-sm"><thead><tr><th>Sesión</th><th>Errores</th><th>Resultado</th></tr></thead><tbody>${detRows}</tbody></table></div>` : '';
        const parrafo = explicacion ? `<p class="mt-2">${explicacion}</p>` : '';
        panel.innerHTML = `${header}${parrafo}${detalleTabla}${items?`<ul class="list-group mt-2">${items}</ul>`:'<div class="text-muted">Sin recomendaciones</div>'}`;
        panel.style.display = '';
      } catch(_) {
        panel.innerHTML = '<div class="text-danger">Error obteniendo recomendaciones</div>';
        panel.style.display = '';
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-lightbulb me-1"></i>Recomendaciones';
      }
    });
    document.addEventListener('click', (e) => {
      const c = e.target.closest('[data-close-reco]');
      if (c) {
        const id = c.getAttribute('data-close-reco');
        const panel = document.getElementById(id);
        if (panel) panel.style.display = 'none';
      }
    });
  });
</script>
@endsection