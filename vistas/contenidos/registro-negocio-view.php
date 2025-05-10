<!-- Registro de negcio -->

<div class="d-flex flex-column min-vh-100">
    <div class="container my-auto">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-lg-6">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Registre su negocio!</h1>
                        </div>

                        <!-- Formulario de registro de negocio y usuario -->
                        <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/negocioAjax.php" method="POST" data-form="save">

                            <div class="row">
                                <!-- Datos del negocio -->
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <legend>Datos del negocio</legend>

                                        <!-- Nombre Negocio -->
                                        <div class="mb-3">
                                            <label for="nombre_negocio" class="form-label">Nombre del negocio</label>
                                            <input type="text" name="nombre_negocio" id="nombre_negocio" class="form-control" maxlength="30" required>
                                        </div>

                                        <!-- Contacto -->
                                        <div class="mb-3">
                                            <label for="contacto" class="form-label">Contacto</label>
                                            <input type="text" name="contacto" id="contacto" class="form-control" maxlength="20" required>
                                        </div>

                                        <!-- Dirección -->
                                        <div class="mb-3">
                                            <label for="direccion" class="form-label">Dirección</label>
                                            <input type="text" name="direccion" id="direccion" class="form-control" maxlength="50" required>
                                        </div>
                                    </fieldset>
                                </div>

                                <!-- Datos del usuario -->
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <legend>Datos del usuario</legend>

                                        <!-- Nombre -->
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="20" required>
                                        </div>

                                        <!-- Contraseña -->
                                        <div class="mb-3">
                                            <label for="contrasena" class="form-label">Contraseña</label>
                                            <input type="password" name="contrasena" id="contrasena" class="form-control" maxlength="20" required>
                                        </div>

                                        <!-- Correo -->
                                        <div class="mb-3">
                                            <label for="correo" class="form-label">Correo</label>
                                            <input type="email" name="correo" id="correo" class="form-control" maxlength="50" required>
                                        </div>

                                        <!-- Rol -->
                                        <div class="mb-3">
                                            <label for="rol" class="form-label">Rol</label>
                                            <input type="text" name="rol" id="rol" class="form-control" value="Administrador" readonly>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <!-- Botón registrar -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Registrar</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-3 mt-auto">
        <p class="mb-0">© 2024 VentaGo. Todos los derechos reservados.</p>
    </footer>
</div>