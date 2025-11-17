<div class="modal fade" id="crearUsuariosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal de Ejemplo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              
                 <!-- Formulario de registro -->
              <form id="registerForm" action="{{ route('usuarios.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-sm-6">
                            <div class="position-relative mb-4">
                                <label for="registerNombre" class="form-label fs-base">First Name</label>
                                <input type="text" id="registerNombre" name="nombre" class="form-control form-control-lg" required>
                                <div class="invalid-feedback position-absolute start-0 top-100">Please enter your name!</div>
                            </div>
                        </div>
                        <!-- Apellido -->
                        <div class="col-sm-6">
                            <div class="position-relative mb-4">
                                <label for="registerApellido" class="form-label fs-base">Last Name</label>
                                <input type="text" id="registerApellido" name="apellido" class="form-control form-control-lg" required>
                                <div class="invalid-feedback position-absolute start-0 top-100">Please enter your last name!</div>
                            </div>
                        </div>
                        <!-- Fecha de Nacimiento -->
                        <div class="col-sm-6">
                            <div class="position-relative mb-4">
                                <label for="registerFechaNacimiento" class="form-label fs-base">Date of Birth</label>
                                <input type="date" id="registerFechaNacimiento" name="fecha_nacimiento" class="form-control form-control-lg" required>
                                <div class="invalid-feedback position-absolute start-0 top-100">Please enter your date of birth!</div>
                            </div>
                        </div>
                        <!-- Correo -->
                        <div class="col-sm-6">
                            <div class="position-relative mb-4">
                                <label for="registerCorreo" class="form-label fs-base">Email</label>
                                <input type="email" id="registerCorreo" name="correo" class="form-control form-control-lg" required>
                                <div class="invalid-feedback position-absolute start-0 top-100">Please enter a valid email address!</div>
                            </div>
                        </div>
                        <!-- Contraseña -->
                        <div class="col-12 mb-4">
                            <label for="registerContrasena" class="form-label fs-base">Password</label>
                            <div class="password-toggle">
                                <input type="password" id="registerContrasena" name="contrasena" class="form-control form-control-lg" required autocomplete="new-password">
                                <label class="password-toggle-btn" aria-label="Show/hide password">
                                    <input class="password-toggle-check" type="checkbox">
                                    <span class="password-toggle-indicator"></span>
                                </label>
                                <div class="invalid-feedback position-absolute start-0 top-100">Please enter a password!</div>
                            </div>
                        </div>
                        <!-- Confirmar Contraseña -->
                        <div class="col-12 mb-4">
                            <label for="registerContrasenaConfirmation" class="form-label fs-base">Confirm Password</label>
                            <div class="password-toggle">
                                <input type="password" id="registerContrasenaConfirmation" name="contrasena_confirmation" class="form-control form-control-lg" required autocomplete="new-password">
                                <label class="password-toggle-btn" aria-label="Show/hide password">
                                    <input class="password-toggle-check" type="checkbox">
                                    <span class="password-toggle-indicator"></span>
                                </label>
                                <div class="invalid-feedback position-absolute start-0 top-100">Please confirm your password!</div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Botón de registro -->
                    <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
                </form>           
            </div>
       
        </div>
    </div>
</div>
