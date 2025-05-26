<!--
<?php
    if ($_SESSION['rol_usuario'] != "Administrador") {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>
-->

<!-- Alertas -->
<div class="container-fluid">

    <!-- Cabecera de página principal -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Alertas Productos con bajo Stock</h1>
    </div>

    <!-- Filtro de Categorías -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>

        <div class="card-body">
            <form id="filtroalertas" method="POST" action="<?php echo SERVER_URL; ?>alertas/">
                <div class="row">

                    <!-- Id -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id">Id</label>
                            <select name="id" id="filtro_id" class="form-control">
                                <?php
                                require_once "./controladores/alertaControlador.php";
                                $ins_alerta = new alertaControlador();
                                $alertas = $ins_alerta->obtener_alertas_controlador();

                                if ($alertas) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($alertas as $alerta) {
                                        $selected = ($alerta->id == $_POST['id']) ? 'selected' : '';
                                        echo '<option value="' . $alerta->id . '" ' . $selected . '>' . $alerta->id . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay categorías disponibles</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_nombre">Nombre</label>
                            <select name="nombre" id="filtro_nombre" class="form-control">
                                <?php
                                require_once "./controladores/alertaControlador.php";
                                $ins_alerta = new alertaControlador();
                                $alertas = $ins_alerta->obtener_alertas_controlador();

                                if ($alertas) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($alertas as $alerta) {
                                        $selected = ($alerta->nombre == $_POST['nombre']) ? 'selected' : '';
                                        echo '<option value="' . $alerta->nombre . '" ' . $selected . '>' . $alerta->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay categorías disponibles</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Ordenar por:(0-9, 9-0) -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_orden">Ordenar alertas de:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="ASC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'ASC') ? 'selected' : ''; ?> >1-9</option>
                                <option value="DESC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'DESC') ? 'selected' : ''; ?> >9-1</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -> Buscar | Reestablecer -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>alertas/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
                            
    <!-- Tabla de productos con alerta -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Productos con alerta</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/alertaControlador.php";
                $ins_alerta = new alertaControlador();

                $filtros = [
                    'id' => isset($_POST['id']) ? trim($_POST['id']) : '',
                    'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_alerta->paginar_alerta_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>