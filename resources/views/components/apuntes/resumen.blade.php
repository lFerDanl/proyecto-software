{{-- resources/views/components/apuntes/resumen.blade.php --}}
@props(['apuntes'])

<div class="tab-pane fade show active" id="resumen" role="tabpanel" aria-labelledby="resumen-tab">
  @forelse($apuntes as $a)
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <h5 class="card-title mb-0">
            <i class="bi bi-file-text-fill text-primary me-2"></i>{{ $a->titulo }}
          </h5>
          <span class="badge {{ $a->estadoIA==='completado' ? 'bg-success' : ($a->estadoIA==='procesando' ? 'bg-warning text-dark' : 'bg-secondary') }}">
            @if($a->estadoIA==='completado')
              <i class="bi bi-check-circle me-1"></i>
            @elseif($a->estadoIA==='procesando')
              <i class="bi bi-hourglass-split me-1"></i>
            @endif
            {{ ucfirst($a->estadoIA) }}
          </span>
        </div>
        <div class="markdown-content">
          {!! \Illuminate\Support\Str::markdown($a->contenido) !!}
        </div>
      </div>
    </div>
  @empty
    <div class="text-center py-5">
      <i class="bi bi-inbox" style="font-size: 3rem; color: var(--bs-secondary);"></i>
      <p class="text-muted mt-3">Sin contenido aún</p>
    </div>
  @endforelse
</div>