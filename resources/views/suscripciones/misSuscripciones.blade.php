@extends('layouts.client')


@section('content')
  
<div class="container my-4">
    <h1 class="text-center">Mis Suscripciones</h1>
    
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
</div>

<div class="container">
    <!-- Suscripciones Activas -->
    <h3 class="mb-3">Suscripciones Activas</h3>
    <div class="table-responsive shadow rounded mb-4">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark text-center">
                <tr>
                    <th scope="col">Plan</th>
                    <th scope="col">Fecha Inicio</th>
                    <th scope="col">Fecha Fin</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suscripcionesActivas as $item)
                    <tr>
                        <td class="align-middle">{{ $item->plan }}</td> 
                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m/Y H:i:s') }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->fecha_fin)->format('d/m/Y H:i:s') }}</td>
                        <td class="align-middle text-center">
                            <span class="badge badge-success" style="background-color: #28a745; color: white; padding: 5px 10px;">
                                Activa
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('suscripcion.cancelar', $item->id) }}" 
                               class="btn btn-warning btn-sm" 
                               onclick="return confirm('¿Estás seguro de que deseas cancelar esta suscripción?')">
                                <i class="fa fa-times-circle"></i> Cancelar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <div class="alert alert-info mb-0">
                                <p class="mb-0">No tienes suscripciones activas actualmente.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Suscripciones Canceladas -->
    <h3 class="mb-3 mt-5">Suscripciones Canceladas</h3>
    <div class="table-responsive shadow rounded mb-4">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark text-center">
                <tr>
                    <th scope="col">Plan</th>
                    <th scope="col">Fecha Inicio</th>
                    <th scope="col">Fecha Fin</th>
                    <th scope="col">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suscripcionesCanceladas as $item)
                    <tr>
                        <td class="align-middle">{{ $item->plan }}</td> 
                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m/Y H:i:s') }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->fecha_fin)->format('d/m/Y H:i:s') }}</td>
                        <td class="align-middle text-center">
                            <span class="badge badge-danger" style="background-color: #dc3545; color: white; padding: 5px 10px;">
                                Cancelada
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-3">
                            <div class="alert alert-secondary mb-0">
                                <p class="mb-0">No tienes suscripciones canceladas.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Suscripciones Expiradas -->
    <h3 class="mb-3 mt-5">Suscripciones Expiradas</h3>
    <div class="table-responsive shadow rounded mb-4">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark text-center">
                <tr>
                    <th scope="col">Plan</th>
                    <th scope="col">Fecha Inicio</th>
                    <th scope="col">Fecha Fin</th>
                    <th scope="col">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suscripcionesExpiradas as $item)
                    <tr>
                        <td class="align-middle">{{ $item->plan }}</td> 
                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m/Y H:i:s') }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->fecha_fin)->format('d/m/Y H:i:s') }}</td>
                        <td class="align-middle text-center">
                            <span class="badge badge-secondary" style="background-color: #6c757d; color: white; padding: 5px 10px;">
                                Expirada
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-3">
                            <div class="alert alert-secondary mb-0">
                                <p class="mb-0">No tienes suscripciones expiradas.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($suscripcionesActivas->count() > 0)
        <div class="alert alert-info mt-4">
            <p><strong>Nota:</strong> Al cancelar una suscripción, perderás acceso a todos los cursos incluidos en ella al finalizar el período actual.</p>
        </div>
    @endif
    
    @if($suscripciones->count() == 0)
        <div class="text-center mt-4">
            <a href="{{ route('plan') }}" class="btn btn-primary btn-lg">Ver planes disponibles</a>
        </div>
    @endif
</div>


@endsection
