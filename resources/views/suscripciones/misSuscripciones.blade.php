@extends('layouts.client')


@section('content')
  
<div class="container my-4">
    <h1 class="text-center">Mi suscripcion</h1>
</div>

<div class="container">

    {{-- <link rel="stylesheet" href="{{ asset('admin/bitacora.css') }}"> --}}

    <div class="table-responsive shadow rounded">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark text-center">
                <tr>
                    <th scope="col">Plan</th>
                    <th scope="col">Fecha Inicio</th>
                    <th scope="col">Fecha Fin</th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse ($suscripcion as $suscripcion)
                    <tr>
                        {{-- <td>{{ $suscripcion->user }}</td> --}}
                        <td>{{ $suscripcion->plan }}</td> 
                        <td>{{ $suscripcion->fecha_inicio }}</td>
                        <td>{{ $suscripcion->fecha_fin }}</td>                             
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No se ha suscrito a ningun plan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@endsection
