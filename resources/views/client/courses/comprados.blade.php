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
        
        @if(isset($suscripcion) && $suscripcion->consumidor_id == Auth::id())
            <div class="alert alert-success">
                <h4><i class="fa fa-star"></i> Suscripción Activa</h4>
                <p>Tienes una suscripción activa hasta: <strong>{{ \Carbon\Carbon::parse($suscripcion->fecha_fin)->format('d/m/Y') }}</strong></p>
                <p>Disfruta de acceso completo a todos nuestros cursos mientras tu suscripción esté activa.</p>
                <p class="text-right">
                    <a href="{{ route('suscripcion.cancelar', $suscripcion->id) }}" class="btn btn-sm btn-warning" onclick="return confirm('¿Estás seguro de que deseas cancelar tu suscripción?')">
                        <i class="fa fa-times-circle"></i> Cancelar Suscripción
                    </a>
                </p>
            </div>
        @endif

        @if ($curso->isEmpty())
            <div class="alert alert-info text-center p-5">
                <i class="fa fa-info-circle fa-3x mb-3"></i>
                <h3>No tienes cursos disponibles</h3>
                @if(isset($sinCursos) && $sinCursos)
                    <p>Aún no has comprado ningún curso ni tienes una suscripción activa.</p>
                    <div class="mt-4">
                        <a href="{{ route('courses.index') }}" class="btn btn-primary mr-2">
                            <i class="fa fa-search"></i> Explorar cursos
                        </a>
                        <a href="{{ route('plan') }}" class="btn btn-success">
                            <i class="fa fa-star"></i> Ver planes de suscripción
                        </a>
                    </div>
                @else
                    <p>No se encontraron cursos asociados a tu cuenta.</p>
                    <a href="{{ route('courses.index') }}" class="btn btn-primary mt-3">
                        <i class="fa fa-search"></i> Explorar cursos disponibles
                    </a>
                @endif
            </div>
        @else
            <div class="container">
                @php
                    $cursosPorCategoria = $curso->groupBy(function($item) {
                        return $item->categoria ? $item->categoria->nombre : 'Sin categoría';
                    });
                @endphp
                
                @foreach ($cursosPorCategoria as $categoria => $cursos)
                    <h2>{{ $categoria }}</h2>
                    <div class="row">
                        @foreach ($cursos as $cursoItem)
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    @if($cursoItem->imagen)
                                        <img src="{{ asset($cursoItem->imagen) }}" class="card-img-top" alt="{{ $cursoItem->nombre }}">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $cursoItem->nombre }}</h5>
                                        <p class="card-text">{{ Str::limit($cursoItem->descripcion, 100) }}</p>
                                        <p><strong>Estado:</strong> {{ $cursoItem->estado }}</p>
                                        
                                        @if(isset($tieneSuscripcion) && $tieneSuscripcion)
                                            <div class="badge badge-success" style="background-color: #28a745; color: white; padding: 5px; margin-bottom: 10px; display: inline-block;">
                                                Incluido en tu suscripción
                                            </div>
                                        @else
                                            <div class="badge badge-info" style="background-color: #17a2b8; color: white; padding: 5px; margin-bottom: 10px; display: inline-block;">
                                                Comprado individualmente
                                            </div>
                                        @endif
                                        <br>
                                        <a href="{{ route('curso.detalles', $cursoItem->id) }}" class="btn btn-success">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
