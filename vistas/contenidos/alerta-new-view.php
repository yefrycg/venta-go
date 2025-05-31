<?php
    if ($_SESSION['rol_usuario'] != "Administrador") {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>

<!-- Registro de alertas -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registrar Alerta</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de registro -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/alertaAjax.php" method="POST" data-form="save">

                <!-- Unidad -->
                <div class="mb-3">
                    <label for="unidad" class="form-label">Unidad</label>
                    <select name="unidad" id="unidad" class="form-control" required>
                        <option value="">Seleccione una unidad</option>
                        <option value="unidad">Unidad</option>
                        <option value="kilogramo">Kilogramo</option>
                        <option value="libra">Libra</option>
                    </select>
                </div>

                <!-- Valor minimo para la unidad -->
                <div class="mb-3">
                    <label for="valor_minimo" class="form-label">Valor mínimo</label>
                    <input type="number" min="0" name="valor_minimo" id="valor_minimo" class="form-control" value="" required>
                </div>

                <!-- Botón añadir alerta -->
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