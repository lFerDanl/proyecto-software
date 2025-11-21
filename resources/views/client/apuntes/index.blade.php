@extends('Layouts.client')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1">Mis contenidos</h2>
      <p class="text-muted mb-0">Gestiona tus clases y material de estudio</p>
    </div>
    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalUpload">
      <i class="bi bi-plus-circle me-2"></i>Añadir contenido
    </button>
  </div>

  @if(session('upload_ok'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-2"></i>{{ session('message') ?? 'Media subida y encolada' }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-4" id="mediasContainer">
    @forelse($medias as $m)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm hover-lift">
          <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-start mb-3">
              <div class="flex-grow-1">
                <h5 class="card-title mb-2">{{ $m['titulo'] }}</h5>
                <p class="card-text text-muted small">{{ Str::limit($m['descripcion'], 100) }}</p>
              </div>
              @if($m['tipo'] === 'VIDEO')
                <div class="ms-2">
                  <span class="badge bg-primary-subtle text-primary rounded-pill">
                    <i class="bi bi-camera-video"></i>
                  </span>
                </div>
              @else
                <div class="ms-2">
                  <span class="badge bg-info-subtle text-info rounded-pill">
                    <i class="bi bi-music-note"></i>
                  </span>
                </div>
              @endif
            </div>

            <div class="mb-3">
              <div class="d-flex flex-wrap gap-2">
                <span class="badge {{ $m['transcripcion_status']==='completado' ? 'bg-success' : ($m['transcripcion_status']==='procesando' ? 'bg-warning text-dark' : ($m['transcripcion_status']==='error' ? 'bg-danger':'bg-secondary')) }}">
                  @if($m['transcripcion_status']==='completado')
                    <i class="bi bi-check-circle me-1"></i>
                  @elseif($m['transcripcion_status']==='procesando')
                    <i class="bi bi-hourglass-split me-1"></i>
                  @elseif($m['transcripcion_status']==='error')
                    <i class="bi bi-exclamation-triangle me-1"></i>
                  @else
                    <i class="bi bi-circle me-1"></i>
                  @endif
                  Transcripción: {{ $m['transcripcion_status'] }}
                </span>
              </div>
            </div>

            <div class="mt-auto">
              @if($m['apuntes_ready'])
                <a href="{{ route('client.apuntes.show', ['id' => $m['id_media']]) }}" class="btn btn-success w-100">
                  <i class="bi bi-eye me-2"></i>Ver apuntes
                </a>
              @else
                <button class="btn btn-outline-secondary w-100" disabled>
                  <span class="spinner-border spinner-border-sm me-2"></span>
                  Generando apuntes...
                </button>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12" id="emptyState">
        <div class="text-center py-5">
          <div class="mb-4">
            <i class="bi bi-folder-x" style="font-size: 4rem; color: var(--bs-secondary);"></i>
          </div>
          <h4 class="text-muted mb-3">Aún no tienes contenidos</h4>
          <p class="text-muted mb-4">¡Sube tu primera clase y comienza a generar apuntes automáticamente!</p>
          <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalUpload">
            <i class="bi bi-plus-circle me-2"></i>Subir primera clase
          </button>
        </div>
      </div>
    @endforelse
  </div>

  <!-- Modal Upload -->
  <div class="modal fade" id="modalUpload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-cloud-upload me-2"></i>Subir media</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="uploadForm" action="{{ route('client.ia.media.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold">Archivo (video/audio)</label>
              <input type="file" name="file" class="form-control" accept="video/*,audio/*" required>
              <div class="form-text">Formatos soportados: MP4, MP3, WAV, etc.</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Título</label>
              <input type="text" name="titulo" class="form-control" maxlength="200" placeholder="Ej: Clase 1 - Introducción a Laravel" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Descripción</label>
              <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe brevemente el contenido de la clase..."></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Tipo</label>
              <select name="tipo" class="form-select" required>
                <option value="VIDEO">VIDEO</option>
                <option value="AUDIO">AUDIO</option>
              </select>
            </div>
            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-primary" type="submit">
                <i class="bi bi-upload me-2"></i>Subir y procesar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.hover-lift {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
<script>
  const uploadForm = document.getElementById('uploadForm');
  const modalEl = document.getElementById('modalUpload');
  if (uploadForm && modalEl) {
    const submitBtn = uploadForm.querySelector('button[type="submit"]');
    uploadForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (submitBtn.disabled) return;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Enviando...';
      const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modal.hide();
      setTimeout(() => {
        try {
          document.body.classList.remove('modal-open');
          document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
          modalEl.classList.remove('show');
        } catch (e) {}
      }, 100);

      const fd = new FormData(uploadForm);
      try {
        const res = await fetch("{{ route('client.ia.media.upload') }}", { method: 'POST', headers: { 'Accept': 'application/json' }, body: fd });
        if (!res.ok) throw new Error('error');
        const data = await res.json();
        const mid = data.media_id;
        const titulo = fd.get('titulo');
        const descripcion = fd.get('descripcion');
        const tipo = fd.get('tipo');
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4';
        col.innerHTML = `
          <div class="card h-100 shadow-sm hover-lift" data-media-id="${mid}">
            <div class="card-body d-flex flex-column">
              <div class="d-flex align-items-start mb-3">
                <div class="flex-grow-1">
                  <h5 class="card-title mb-2">${titulo}</h5>
                  <p class="card-text text-muted small">${(descripcion||'')}</p>
                </div>
                ${tipo==='VIDEO' ? '<div class="ms-2"><span class="badge bg-primary-subtle text-primary rounded-pill"><i class="bi bi-camera-video"></i></span></div>' : '<div class="ms-2"><span class="badge bg-info-subtle text-info rounded-pill"><i class="bi bi-music-note"></i></span></div>'}
              </div>
              <div class="mb-3">
                <div class="d-flex flex-wrap gap-2">
                  <span class="badge bg-warning text-dark" data-trans="status"><i class="bi bi-hourglass-split me-1"></i>Transcripción: procesando</span>
                </div>
              </div>
              <div class="mt-auto" data-actions>
                <button class="btn btn-outline-secondary w-100" disabled>
                  <span class="spinner-border spinner-border-sm me-2"></span>
                  Generando apuntes...
                </button>
              </div>
            </div>
          </div>`;
        document.getElementById('mediasContainer').prepend(col);
        const empty = document.getElementById('emptyState');
        if (empty) empty.remove();
        startPolling(mid, col.querySelector('[data-trans="status"]'), col.querySelector('[data-actions]'));
      } catch (err) {
        alert('Error subiendo media');
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-upload me-2"></i>Subir y procesar';
        uploadForm.reset();
      }
    });
  }

  function startPolling(mediaId, transEl, actionsEl) {
    const interval = setInterval(async () => {
      try {
        const res = await fetch("{{ route('client.ia.media.status', ['id' => 'MEDIA_ID']) }}".replace('MEDIA_ID', mediaId));
        const data = await res.json();
        const estados = (data.transcripciones||[]).map(t => t.estadoIA || t.estado_ia);
        let status = 'pendiente';
        if (estados.includes('error')) status = 'error';
        else if (estados.includes('procesando')) status = 'procesando';
        else if (estados.length>0 && estados.every(s => s==='completado')) status = 'completado';
        transEl.className = 'badge ' + (status==='completado' ? 'bg-success' : (status==='procesando' ? 'bg-warning text-dark' : (status==='error' ? 'bg-danger':'bg-secondary')));
        transEl.innerHTML = (status==='completado' ? '<i class="bi bi-check-circle me-1"></i>' : status==='procesando' ? '<i class="bi bi-hourglass-split me-1"></i>' : status==='error' ? '<i class="bi bi-exclamation-triangle me-1"></i>' : '<i class="bi bi-circle me-1"></i>') + 'Transcripción: ' + status;
        const tipos = ['resumen','explicacion','flashcard'];
        const byTipo = { resumen:false, explicacion:false, flashcard:false };
        (data.apuntes||[]).forEach(a => { if (tipos.includes(a.tipo) && a.estadoIA==='completado') byTipo[a.tipo]=true; });
        const ready = tipos.every(t => byTipo[t]);
        if (ready) {
          actionsEl.innerHTML = `<a href="{{ route('client.apuntes.show', ['id' => 'MEDIA_ID']) }}" class="btn btn-success w-100"><i class="bi bi-eye me-2"></i>Ver apuntes</a>`
            .replace('MEDIA_ID', mediaId);
          clearInterval(interval);
        }
      } catch (e) {}
    }, 5000);
  }
</script>
@endsection