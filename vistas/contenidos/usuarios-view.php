<?php
if ($_SESSION['rol_usuario'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>


<!-- Usuarios -->
<div class="container-fluid">

    <!-- Cabecera de página -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Usuarios</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de usuarios</h6>
        </div>

        <div class="card-body">

            <!-- Formulario de filtros -->
            <form id="filtroUsuarios" method="POST" action="<?php echo SERVER_URL; ?>usuarios/">
                <div class="row">

                    <!-- Id -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id">Id</label>
                            <select name="id" id="filtro_id" class="form-control">
                            <?php
                                require_once "./controladores/usuarioControlador.php";
                                $ins_usuario = new usuarioControlador();
                                $usuarios = $ins_usuario->obtener_usuarios_controlador();

                                if ($usuarios) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($usuarios as $usuario) {
                                        $selected = ($usuario->id == $_POST['id']) ? 'selected' : '';
                                        echo '<option value="' . $usuario->id . '" ' . $selected . '>' . $usuario->id . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay id´s disponibles</option>';
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
                                require_once "./controladores/usuarioControlador.php";
                                $ins_usuario = new usuarioControlador();
                                $usuarios = $ins_usuario->obtener_usuarios_controlador();

                                if ($usuarios) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($usuarios as $usuario) {
                                        $selected = ($usuario->nombre == $_POST['nombre']) ? 'selected' : '';
                                        echo '<option value="' . $usuario->nombre . '" ' . $selected . '>' . $usuario->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay nombres disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_correo">Correo</label>
                            <select name="correo" id="filtro_correo" class="form-control">
                            <?php
                                require_once "./controladores/usuarioControlador.php";
                                $ins_usuario = new usuarioControlador();
                                $usuarios = $ins_usuario->obtener_usuarios_controlador();

                                if ($usuarios) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($usuarios as $usuario) {
                                        $selected = ($usuario->correo == $_POST['correo']) ? 'selected' : '';
                                        echo '<option value="' . $usuario->correo . '" ' . $selected . '>' . $usuario->correo . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay correos disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Botones (Buscar, Reestablecer) -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>usuarios/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Usuarios -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Usuarios</h6>
        </div>

        <div class="card-body">
            <?php
            require_once "./controladores/usuarioControlador.php";
            $ins_usuario = new usuarioControlador();
            $filtros = [
                'id' => isset($_POST['id']) ? trim($_POST['id']) : '',
                'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                'correo' => isset($_POST['correo']) ? trim($_POST['correo']) : ''
            ];
            
            echo $ins_usuario->paginar_usuario_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>