{{-- resources/views/components/apuntes/flashcards.blade.php --}}
@props(['apuntes'])

<div class="tab-pane fade" id="flashcards" role="tabpanel" aria-labelledby="flashcards-tab">
  @php
    // Recopilamos todas las flashcards de todos los apuntes de tipo flashcard
    $allFlashcards = [];
    foreach($apuntes as $a) {
      if($a->estadoIA === 'completado') {
        $lines = explode("\n", trim($a->contenido));
        $currentQ = '';
        $currentA = '';
        
        foreach($lines as $line) {
          $line = trim($line);
          if(empty($line)) continue;
          
          // Detectar preguntas (pueden empezar con Q:, Pregunta:, **, etc.)
          if(preg_match('/^(Q:|Pregunta:|P:|\*\*Q|\*\*Pregunta)/i', $line)) {
            if($currentQ && $currentA) {
              $allFlashcards[] = ['q' => $currentQ, 'a' => $currentA];
            }
            $currentQ = preg_replace('/^(Q:|Pregunta:|P:|\*\*Q|\*\*Pregunta)[:\*\s]*/i', '', $line);
            $currentA = '';
          }
          // Detectar respuestas
          elseif(preg_match('/^(A:|Respuesta:|R:|\*\*A|\*\*Respuesta)/i', $line)) {
            $currentA = preg_replace('/^(A:|Respuesta:|R:|\*\*A|\*\*Respuesta)[:\*\s]*/i', '', $line);
          }
          // Si ya tenemos pregunta, agregar a respuesta
          elseif($currentQ && !$currentA) {
            $currentA .= ' ' . $line;
          }
          elseif($currentQ && $currentA) {
            $currentA .= ' ' . $line;
          }
        }
        
        if($currentQ && $currentA) {
          $allFlashcards[] = ['q' => $currentQ, 'a' => $currentA];
        }
      }
    }
  @endphp

  @if(count($allFlashcards) > 0)
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1">
          <i class="bi bi-card-list text-success me-2"></i>Flashcards de estudio
        </h4>
        <p class="text-muted mb-0">{{ count($allFlashcards) }} tarjetas disponibles</p>
      </div>
      <button class="btn btn-outline-success" onclick="alert('Función próximamente disponible')">
        <i class="bi bi-plus-circle me-2"></i>Generar nuevas cards
      </button>
    </div>

    <div class="flashcards-grid">
      @foreach($allFlashcards as $idx => $card)
        <div class="flashcard" onclick="this.classList.toggle('flipped')">
          <div class="flashcard-inner">
            <div class="flashcard-front">
              <div class="flashcard-label">Pregunta {{ $idx + 1 }}</div>
              <div class="flashcard-content">{{ $card['q'] }}</div>
              <div class="flashcard-hint">
                <i class="bi bi-hand-index"></i> Click para ver respuesta
              </div>
            </div>
            <div class="flashcard-back">
              <div class="flashcard-label">Respuesta {{ $idx + 1 }}</div>
              <div class="flashcard-content">{!! \Illuminate\Support\Str::markdown($card['a']) !!}</div>
              <div class="flashcard-hint">
                <i class="bi bi-hand-index"></i> Click para ver pregunta
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="text-center py-5">
      <i class="bi bi-inbox" style="font-size: 3rem; color: var(--bs-secondary);"></i>
      <h5 class="text-muted mt-3 mb-2">No hay flashcards disponibles</h5>
      <p class="text-muted mb-4">Las flashcards se están generando o aún no están listas</p>
      <button class="btn btn-success" onclick="alert('Función próximamente disponible')">
        <i class="bi bi-plus-circle me-2"></i>Generar nuevas cards
      </button>
    </div>
  @endif
</div>