@extends('Layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Cursos Destacados</h1>

    <!-- Curso Mejor Calificado -->
    <div class="mb-5">
        <h2>⭐ Curso Mejor Calificado</h2>
        @if ($cursoMejorCalificado)
        <div class="card">
            <img src="{{ $cursoMejorCalificado->imagen }}" class="card-img-top" alt="Imagen del curso" style="height: 200px; object-fit: cover;">
            <div class="card-body">
                <h3>{{ $cursoMejorCalificado->nombre }}</h3>
                <p><strong>Autor:</strong> {{ $cursoMejorCalificado->autornombre->nombre }}</p>
                <p><strong>Categoría:</strong> {{ $cursoMejorCalificado->categoria->nombre }}</p>
                <p><strong>Calificación Promedio:</strong> {{ number_format($cursoMejorCalificado->calificaciones_avg_estrellas, 2) }}/5</p>
                <p><strong>Precio:</strong> ${{ number_format($cursoMejorCalificado->precio, 2) }}</p>
            </div>
        </div>
        @else
        <p>No hay cursos disponibles.</p>
        @endif
    </div>

    <!-- 10 Cursos Más Vendidos -->
    <h2>🔥 Los 10 Cursos Más Vendidos</h2>
    <div class="d-flex overflow-auto">
        @foreach ($cursosMasVendidos as $curso)
        <div class="card me-3" style="min-width: 250px; border: 1px solid #ddd; border-radius: 10px;">
            <img src="{{ $curso->imagen }}" class="card-img-top" alt="Imagen del curso" style="height: 150px; object-fit: cover;">
            <div class="card-body">
                <h4>{{ $curso->nombre }}</h4>
                <p><strong>Autor:</strong> {{ $curso->autornombre->nombre }}</p>
                <p><strong>Categoría:</strong> {{ $curso->categoria->nombre }}</p>
                <p><strong>Compras:</strong> {{ $curso->compras_count }}</p>
                <p><strong>Precio:</strong> ${{ number_format($curso->precio, 2) }}</p>
                <p><strong>Calificación Promedio:</strong>
                    @if ($curso->calificaciones_avg_estrellas)
                        {{ number_format($curso->calificaciones_avg_estrellas, 2) }}/5
                    @else
                        Sin calificar
                    @endif
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
