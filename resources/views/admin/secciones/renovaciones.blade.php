@extends('Layouts.admin')

@section('content')
<div class="container">
    <h1>Renovaciones</h1>
   

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subscripción</th>
                <th>Fecha de Renovación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($renovaciones as $renovacion)
                <tr>
                    <td>{{ $renovacion->id }}</td>
                    <td>{{ $renovacion->subscripcion->nombre }}</td>
                    <td>{{ $renovacion->fecha_renovacion }}</td>
                    <td>{{ $renovacion->estado }}</td>
                    <td>
                    
                       
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
