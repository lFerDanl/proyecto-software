@extends('Layouts.admin')

@section('content')



    <div class="container my-4">
        <h1 class="text-center">Bitácora de Actividades</h1>
        <p class="text-muted text-center">Registros detallados de las acciones realizadas en el sistema</p>
    </div>

    <div class="container">

        {{-- <link rel="stylesheet" href="{{ asset('admin/bitacora.css') }}"> --}}

        <div class="table-responsive shadow rounded">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark text-center">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">ID Usuario</th>
                        <th scope="col">IP</th>
                        <th scope="col">Navegador</th>
                        <th scope="col">Tabla Afectada</th>
                        <th scope="col">Registro ID</th>
                        <th scope="col">Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bitacora as $bitacoras)
                        <tr>
                            <td class="text-center">{{ $bitacoras->id }}</td>
                            <td>{{ $bitacoras->descripcion }}</td>
                            <td>{{ $bitacoras->usuario }}</td>
                            <td class="text-center">{{ $bitacoras->usuario_id }}</td>
                            <td>{{ $bitacoras->direccion_ip }}</td>
                            <td>{{ $bitacoras->navegador }}</td>
                            <td>{{ $bitacoras->tabla }}</td>
                            <td class="text-center">{{ $bitacoras->registro_id }}</td>
                            <td class="text-center">{{ $bitacoras->fecha_hora }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay registros disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
