@extends('Layouts.admin')

@section('content')
<div class="container">
    <h1>Dashboard de Administrador</h1>
    <p>Bienvenido al panel de administración.</p>

    <!-- Botón para abrir el modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Abrir Modal
    </button>

    <!-- Incluir el modal -->
    @include('admin.modals.example_modal')
</div>
@endsection
