<?php
    if ($_SESSION['rol_usuario'] != "Administrador") {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>

<!-- Actualizar alerta -->
<div class="container-fluid">
    <div class="card shadow mb-4 mx-auto" style="max-width: 600px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Editar Alerta</h6>

            <!-- Boton para regresar -->
            <a href="<?php echo SERVER_URL; ?>alerta-config/" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Regresar</span>
            </a>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/alertaControlador.php";
                $ins_alerta = new alertaControlador();
                $datos_alerta = $ins_alerta->obtener_alerta_controlador($pagina[1]);

                if($datos_alerta->rowCount() == 1) {
                    $campos = $datos_alerta->fetch();
            ?>
            <!-- Formulario para actualizar alerta -->
            <form class="FormularioAjax" action="<?php echo SERVER_URL ?>ajax/alertaAjax.php" method="POST" data-form="update">

                <!-- Id -->
                <div class="mb-3">
                    <label for="id_actualizar" class="form-label">Id</label>
                    <input type="number" name="id_actualizar" id="id_actualizar" class="form-control" value="<?php echo $pagina[1]; ?>" readonly>
                </div>

                <!-- Unidad -->
                <div class="mb-3">
                    <label for="unidad_actualizar" class="form-label">Unidad</label>
                    <input type="text" name="unidad_actualizar" id="unidad_actualizar" class="form-control" value="<?php echo $campos['unidad']; ?>" readonly>
                </div>

                <!-- valor -->
                <div class="mb-3">
                    <label for="valor_actualizar" class="form-label">Valor</label>
                    <input type="number" min="0" name="valor_actualizar" id="valor_actualizar" class="form-control" value="<?php echo $campos['valor']; ?>" required>
                </div>

                <!-- Actualizar -->
                <button type="submit" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Actualizar</span>
                </button>
            </form>
            <?php }?>
        </div>
    </div>
</div>