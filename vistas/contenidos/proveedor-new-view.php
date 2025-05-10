<?php
if ($_SESSION['rol_usuario'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>
<!-- Registro de productos -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registrar Proveedor</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/proveedorAjax.php" method="POST" data-form="save">
                
                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" maxlength="100" required>
                </div>

                <!-- Contacto -->
                <div class="mb-3">
                    <label for="contacto" class="form-label">Contacto</label>
                    <input type="text" name="contacto" id="contacto" class="form-control" maxlength="10" required>
                </div>

                <!-- Correo -->
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" name="correo" id="correo" class="form-control" maxlength="100" required>
                </div>

                <!-- Boton añadir -->
                <button type="submit" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Añadir</span>
                </button>
            </form>
        </div>
    </div>
</div>