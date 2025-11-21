@extends('layouts.client')

@section('content')

@if (Session::has('success'))
<div class="alert alert-success text-center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    <p>{{ Session::get('success') }}</p>
</div>
@endif


@if (Session::has('error'))
<div class="alert alert-danger text-center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    <p>{{ Session::get('error') }}</p>
</div>
@endif

<div class="container">
    <h1>Mis Cursos</h1>
    @foreach ($cursos->groupBy('categoria.nombre') as $categoria => $cursosPorCategoria)
        <h2>{{ $categoria }}</h2>
        <div class="row">
            @foreach ($cursosPorCategoria as $curso)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $curso->nombre }}</h5>
                            <p class="card-text">{{ $curso->descripcion }}</p>
                            <p><strong>Estado:</strong> {{ $curso->estado }}</p>

                            <!-- Condición para materiales didácticos -->
                          
                            <a href="{{ route('curso.detalles', ['id' => $curso->id, 'modo' => 'autor']) }}" class="btn btn-success">
                                Ver Detalles
                            </a>


                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
