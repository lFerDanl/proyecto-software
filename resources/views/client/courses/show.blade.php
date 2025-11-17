@extends('layouts.client')

@section('content')
<div class="container">
    <h1>{{ $curso->nombre }}</h1>

    <!-- Imagen del curso -->
    <div class="mb-4">
        <img src="{{ $curso->imagen ? asset($curso->imagen) : 'assets/img/portfolio/courses/default.jpg' }}"
             alt="{{ $curso->nombre }}"
             class="img-fluid"
             style="max-width: 500px;">
    </div>

    <!-- Descripción del curso -->
    <p>{{ $curso->descripcion }}</p>

    @if ($haCompradoCurso || $esSuscriptor)
        <!-- Mostrar materiales si el usuario tiene acceso -->
        <h2>Materiales Didácticos</h2>
        <ul>
             @foreach ($curso->materiales as $material)
            <li>
                <strong>{{ $material->descripcion }}</strong>
                <br>
                @if (str_contains($material->tipo, 'image/'))
                    <!-- Mostrar imágenes de material didáctico -->
                    <img src="{{ asset($material->archivo) }}" alt="Material Didáctico" class="img-fluid" style="max-width: 300px;">
                @elseif (str_contains($material->tipo, 'application/pdf'))
                    <!-- Mostrar visor de PDF -->
                    <iframe src="{{ asset($material->archivo) }}" width="100%" height="500px"></iframe>
                @elseif (str_contains($material->tipo, 'video/'))
                    <!-- Mostrar videos -->
                    <video width="100%" controls>
                        <source src="{{ asset($material->archivo) }}" type="{{ $material->tipo }}">
                        Tu navegador no soporta videos.
                    </video>
                @else
                    <!-- Otros archivos (mostrar nombre sin permitir descarga directa) -->
                    <p>Archivo: {{ $material->archivo }}</p>
                @endif
            </li>
        @endforeach
        </ul>
    @else
    <a href="{{ route('curso.comprar', $curso->id) }}" class="btn btn-primary">Comprar Curso</a>
        <!-- Mostrar botón para comprar curso -->
        <div class="alert alert-warning">
            Debes comprar este curso o suscribirte para acceder a los materiales didácticos.
        </div>

    @endif
</div>
@endsection
