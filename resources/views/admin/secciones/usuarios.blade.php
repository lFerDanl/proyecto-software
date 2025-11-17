@extends('Layouts.admin')

@section('content')
<div class="container">
    <h1>Usuarios</h1>
    <p>Gestión de usuarios aquí.</p>

    <!-- Botón para abrir el modal de creación -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearUsuariosModal">
        Crear usuario
    </button>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tabla de usuarios -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        <!-- Botón para abrir el modal de edición -->
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal{{ $usuario->id }}">
                            Editar
                        </button>

                        <!-- Botón para eliminar con confirmación -->
                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal de edición para cada usuario -->
                @include('admin.modals.crud-usuario.editarUsuario', ['usuario' => $usuario])
            @endforeach
        </tbody>
    </table>

    <!-- Modal de creación de usuario -->
    @include('admin.modals.crud-usuario.crearUsuarios_modal')
</div>
@endsection
