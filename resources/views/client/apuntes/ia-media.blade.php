@extends('Layouts.client')

@section('content')
<div class="container py-4">
  <h2 class="mb-3">Subir media para IA</h2>

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  @if(session('upload_ok'))
    <div class="alert alert-success">{{ session('message') ?? 'Media subida y encolada' }}</div>
  @endif

  <form id="uploadForm" action="{{ route('client.ia.media.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
    @csrf
    <div class="mb-3">
      <label class="form-label">Archivo (video/audio)</label>
      <input type="file" name="file" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Título</label>
      <input type="text" name="titulo" class="form-control" maxlength="200" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Descripción</label>
      <textarea name="descripcion" class="form-control"></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Tipo</label>
      <select name="tipo" class="form-select" required>
        <option value="VIDEO">VIDEO</option>
        <option value="AUDIO">AUDIO</option>
      </select>
    </div>
    <button class="btn btn-primary" type="submit">Subir y procesar</button>
  </form>

  @php $mid = session('media_id'); @endphp
  <div id="statusPanel" @if(!$mid) style="display:none" @endif>
    <h4>Estado de procesamiento</h4>
    <div id="estado" class="mb-2"></div>

    <h5>Transcripciones</h5>
    <ul id="listaTranscripciones" class="list-group mb-3"></ul>

    <h5>Apuntes generados</h5>
    <div id="apuntesContainer" class="row g-3"></div>
  </div>
</div>

<script>
const mediaId = {{ $mid ? (int)$mid : 'null' }};
const statusEl = document.getElementById('estado');
const transList = document.getElementById('listaTranscripciones');
const apuntesDiv = document.getElementById('apuntesContainer');

async function fetchStatus() {
  if (!mediaId) return;
  try {
    const res = await fetch(`{{ route('client.ia.media.status', ['id' => 'MEDIA_ID']) }}`.replace('MEDIA_ID', mediaId));
    const data = await res.json();
    const estado = data.media?.estadoProcesamiento || data.media?.estado_procesamiento;
    statusEl.textContent = `Estado: ${estado ?? 'desconocido'}`;

    transList.innerHTML = '';
    (data.transcripciones || []).forEach(t => {
      const li = document.createElement('li');
      li.className = 'list-group-item';
      li.textContent = `#${t.id} - ${t.idioma || 'es'} - ${t.estadoIA || t.estado_ia}`;
      transList.appendChild(li);
    });

    apuntesDiv.innerHTML = '';
    (data.apuntes || []).forEach(a => {
      const col = document.createElement('div');
      col.className = 'col-md-4';
      col.innerHTML = `
        <div class="card h-100">
          <div class="card-body">
            <span class="badge bg-secondary">${a.tipo}</span>
            <h6 class="mt-2">${a.titulo || '(sin título)'}</h6>
            <pre style="white-space: pre-wrap">${a.contenido}</pre>
          </div>
        </div>`;
      apuntesDiv.appendChild(col);
    });
  } catch (e) {
    statusEl.textContent = 'Error obteniendo estado';
  }
}

if (mediaId) {
  document.getElementById('statusPanel').style.display = '';
  fetchStatus();
  setInterval(fetchStatus, 5000);
}
</script>
@endsection