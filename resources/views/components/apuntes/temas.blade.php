<div class="tab-pane fade" id="temas" role="tabpanel" aria-labelledby="temas-tab">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Temas estructurados</h5>
    <div>
      @if(empty($temas) || count($temas)===0)
        <button class="btn btn-primary" id="btnGenerarTemas" @if(!$apunteResumenId) disabled @endif>
          <i class="bi bi-magic me-2"></i>Generar temas
        </button>
      @else
        <button class="btn btn-outline-primary" id="btnRegenerarTemas">
          <i class="bi bi-arrow-repeat me-2"></i>Regenerar
        </button>
      @endif
    </div>
  </div>

  <div id="temasContainer" class="row g-3">
    @forelse($temas as $t)
      <div class="col-12">
        <div class="card h-100">
          <div class="card-body">
            <span class="badge bg-secondary">Tema</span>
            <h6 class="mt-2">{{ $t->titulo_tema ?? $t->tituloTema }}</h6>
            <p class="text-muted">{{ $t->descripcion }}</p>
            @php $estructura = is_string($t->estructura) ? json_decode($t->estructura, true) : $t->estructura; @endphp
            @if(!empty($estructura['secciones']))
              @php $secs = collect($estructura['secciones'])->sortBy('orden')->all(); @endphp
              <ul class="list-group list-group-flush">
                @foreach($secs as $s)
                  <li class="list-group-item">
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge bg-secondary text-capitalize">{{ $s['tipoSeccion'] }}</span>
                      <strong>{{ $s['titulo'] }}</strong>
                    </div>
                    @php
                      $htmlContenido = isset($s['contenido_html'])
                        ? $s['contenido_html']
                        : \Illuminate\Support\Str::markdown($s['contenido'] ?? '');
                    @endphp
                    <div class="mt-1 markdown-content">{!! $htmlContenido !!}</div>
                  </li>
                @endforeach
              </ul>
            @endif
          </div>
          <div class="card-footer">
            <div class="d-flex align-items-center gap-2 mb-2">
              <select class="form-select form-select-sm w-auto" data-expansion-select data-tema-id="{{ $t->id_tema ?? $t->id }}">
                <option value="profundizar">Profundizar</option>
                <option value="ejemplos">Ejemplos</option>
                <option value="ejercicios">Ejercicios</option>
              </select>
              <button class="btn btn-sm btn-outline-primary" data-profundizar data-tema-id="{{ $t->id_tema ?? $t->id }}">
                <i class="bi bi-arrow-up-right-circle me-1"></i>Profundizar
              </button>
            </div>
            <div class="border rounded p-2">
              <div class="row g-2 align-items-center">
                <div class="col-auto">
                  <select class="form-select form-select-sm" data-seccion-tipo data-tema-id="{{ $t->id_tema ?? $t->id }}">
                    <option>introduccion</option>
                    <option>concepto</option>
                    <option>ejemplo</option>
                    <option>ejercicio</option>
                    <option>aplicacion</option>
                    <option>conclusion</option>
                    <option>referencia</option>
                  </select>
                </div>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" placeholder="Título de sección" data-seccion-titulo data-tema-id="{{ $t->id_tema ?? $t->id }}">
                </div>
                <div class="col-12 mt-2">
                  <textarea class="form-control form-control-sm" rows="2" placeholder="Contenido" data-seccion-contenido data-tema-id="{{ $t->id_tema ?? $t->id }}"></textarea>
                </div>
                <div class="col-12 mt-2 d-flex justify-content-end">
                  <button class="btn btn-sm btn-success" data-agregar-seccion data-tema-id="{{ $t->id_tema ?? $t->id }}">
                    <i class="bi bi-plus-circle me-1"></i>Agregar sección
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info">No hay temas aún</div>
      </div>
    @endforelse
  </div>

  <script>
    const mediaId = {{ (int)$mediaId }};
    const apunteResumenId = {{ $apunteResumenId ? (int)$apunteResumenId : 'null' }};
    const btnGenerar = document.getElementById('btnGenerarTemas');
    const btnRegenerar = document.getElementById('btnRegenerarTemas');
    const container = document.getElementById('temasContainer');

    async function cargarTemas() {
      try {
        const res = await fetch(`{{ route('client.apuntes.temas', ['id' => 'MEDIA_ID']) }}`.replace('MEDIA_ID', mediaId));
        const data = await res.json();
        const temas = data.temas || [];
        container.innerHTML = '';
        if (temas.length === 0) {
          container.innerHTML = '<div class="col-12"><div class="alert alert-info">No hay temas aún</div></div>';
          if (btnGenerar) btnGenerar.disabled = !data.apunteResumenId;
          return false;
        }
        temas.forEach(t => {
          const estructura = typeof t.estructura === 'string' ? JSON.parse(t.estructura) : (t.estructura||{});
          const sorted = (estructura.secciones||[]).sort((a,b)=> (a.orden||0)-(b.orden||0));
          const secciones = sorted.map(s => `<li class="list-group-item"><div class="d-flex align-items-center gap-2"><span class="badge bg-secondary text-capitalize">${s.tipoSeccion}</span><strong>${s.titulo}</strong></div><div class="mt-1 markdown-content">${s.contenido_html || s.contenido || ''}</div></li>`).join('');
          const col = document.createElement('div');
          col.className = 'col-12';
          const temaId = t.id_tema || t.id;
          col.innerHTML = `
            <div class="card h-100">
              <div class="card-body">
                <span class="badge bg-secondary">Tema</span>
                <h6 class="mt-2">${t.titulo_tema || t.tituloTema}</h6>
                <p class="text-muted">${t.descripcion||''}</p>
                ${secciones ? `<ul class="list-group list-group-flush">${secciones}</ul>` : ''}
              </div>
              <div class="card-footer">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <select class="form-select form-select-sm w-auto" data-expansion-select data-tema-id="${temaId}">
                    <option value="profundizar">Profundizar</option>
                    <option value="ejemplos">Ejemplos</option>
                    <option value="ejercicios">Ejercicios</option>
                  </select>
                  <button class="btn btn-sm btn-outline-primary" data-profundizar data-tema-id="${temaId}">
                    <i class="bi bi-arrow-up-right-circle me-1"></i>Profundizar
                  </button>
                </div>
                <div class="border rounded p-2">
                  <div class="row g-2 align-items-center">
                    <div class="col-auto">
                      <select class="form-select form-select-sm" data-seccion-tipo data-tema-id="${temaId}">
                        <option>introduccion</option>
                        <option>concepto</option>
                        <option>ejemplo</option>
                        <option>ejercicio</option>
                        <option>aplicacion</option>
                        <option>conclusion</option>
                        <option>referencia</option>
                      </select>
                    </div>
                    <div class="col">
                      <input type="text" class="form-control form-control-sm" placeholder="Título de sección" data-seccion-titulo data-tema-id="${temaId}">
                    </div>
                    <div class="col-12 mt-2">
                      <textarea class="form-control form-control-sm" rows="2" placeholder="Contenido" data-seccion-contenido data-tema-id="${temaId}"></textarea>
                    </div>
                    <div class="col-12 mt-2 d-flex justify-content-end">
                      <button class="btn btn-sm btn-success" data-agregar-seccion data-tema-id="${temaId}">
                        <i class="bi bi-plus-circle me-1"></i>Agregar sección
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>`;
          container.appendChild(col);
        });
        return true;
      } catch (e) {
        return false;
      }
    }

    async function generarTemas() {
      if (!apunteResumenId && btnGenerar) return;
      if (btnGenerar) {
        btnGenerar.disabled = true;
        btnGenerar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generando...';
      }
      try {
        const res = await fetch(`{{ route('client.apuntes.temas.generar', ['id' => 'MEDIA_ID']) }}`.replace('MEDIA_ID', mediaId), {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('error');
        const interval = setInterval(async () => {
          const ok = await cargarTemas();
          if (ok) {
            clearInterval(interval);
            if (btnGenerar) {
              btnGenerar.disabled = false;
              btnGenerar.innerHTML = '<i class="bi bi-magic me-2"></i>Generar temas';
            }
          }
        }, 5000);
      } catch (e) {
        if (btnGenerar) {
          btnGenerar.disabled = false;
          btnGenerar.innerHTML = '<i class="bi bi-magic me-2"></i>Generar temas';
        }
      }
    }

    if (btnGenerar) btnGenerar.addEventListener('click', generarTemas);
    if (btnRegenerar) btnRegenerar.addEventListener('click', generarTemas);

    document.addEventListener('click', async (e) => {
      const profundizarBtn = e.target.closest('[data-profundizar]');
      const agregarBtn = e.target.closest('[data-agregar-seccion]');
      if (profundizarBtn) {
        const temaId = profundizarBtn.getAttribute('data-tema-id');
        const select = document.querySelector(`[data-expansion-select][data-tema-id="${temaId}"]`);
        const tipo = select ? select.value : 'profundizar';
        profundizarBtn.disabled = true;
        profundizarBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Procesando';
        try {
          const res = await fetch(`{{ route('client.apuntes.temas.profundizar', ['temaId' => 'TEMA_ID']) }}`.replace('TEMA_ID', temaId), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ tipoExpansion: tipo })
          });
          if (!res.ok) throw new Error('error');
          await cargarTemas();
        } catch (err) {}
        profundizarBtn.disabled = false;
        profundizarBtn.innerHTML = '<i class="bi bi-arrow-up-right-circle me-1"></i>Profundizar';
      }
      if (agregarBtn) {
        const temaId = agregarBtn.getAttribute('data-tema-id');
        const tipoSel = document.querySelector(`[data-seccion-tipo][data-tema-id="${temaId}"]`);
        const tituloEl = document.querySelector(`[data-seccion-titulo][data-tema-id="${temaId}"]`);
        const contenidoEl = document.querySelector(`[data-seccion-contenido][data-tema-id="${temaId}"]`);
        const payload = { tipoSeccion: tipoSel.value, titulo: tituloEl.value, contenido: contenidoEl.value };
        agregarBtn.disabled = true;
        agregarBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Agregando';
        try {
          const res = await fetch(`{{ route('client.apuntes.temas.secciones', ['temaId' => 'TEMA_ID']) }}`.replace('TEMA_ID', temaId), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          });
          if (!res.ok) throw new Error('error');
          tituloEl.value = '';
          contenidoEl.value = '';
          await cargarTemas();
        } catch (err) {}
        agregarBtn.disabled = false;
        agregarBtn.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Agregar sección';
      }
    });
  </script>
</div>