<div class="modal fade" id="editarUsuarioModal{{ $usuario->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal de Ejemplo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
                </div>

                <!-- Apellido -->
                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $usuario->apellido) }}" required>
                </div>

                <!-- Fecha de Nacimiento -->
                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento) }}" required>
                </div>

                <!-- Correo -->
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo" class="form-control" value="{{ old('correo', $usuario->correo) }}" required>
                </div>

                <!-- Contraseña (opcional, solo si el usuario quiere cambiarla) -->
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña (dejar en blanco para mantener la actual)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <!-- Confirmación de Contraseña -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <!-- Botón para actualizar el usuario -->
                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </form>
            </div>
            <div class="modal-footer">
               
                
            </div>
        </div>
    </div>
</div>
