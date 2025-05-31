<?php

    if (isset($peticionAjax) && $peticionAjax) { // Comprobar si $peticionAjax está definida antes de usarla
        require_once "../modelos/alertaModelo.php";
    } else {
        require_once "./modelos/alertaModelo.php";
    }

    class alertaControlador extends alertaModelo {
        
        //---------- Controlador para agregar alerta (ejemplo básico) -------------//
        public function agregar_alerta_controlador() {
            session_start(['name' => 'VENTAGO']);
            $nit_negocio = $_SESSION['nit_negocio'];

            // validar que la unidad no se repita
                $unidad = $_POST['unidad'];
                $valor_minimo = $_POST['valor_minimo'];
                $conexion = mainModel::conectar();
                $consulta = $conexion->prepare("SELECT * FROM alerta WHERE unidad = :unidad AND nit_negocio = :nit_negocio");
                $consulta->bindParam(":unidad", $unidad);
                $consulta->bindParam(":nit_negocio", $nit_negocio);
                $consulta->execute();

            if ($consulta->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Alerta ya registrada',
                    "Texto" => 'La unidad de medida ya tiene una alerta registrada',
                    "Tipo" => 'warning'
                ];
                echo json_encode($alerta);
                return;
            }
            if (isset($_POST['unidad']) && isset($_POST['valor_minimo'])) {
                $unidad = $_POST['unidad'];
                $valor_minimo = $_POST['valor_minimo'];

                $datos = [
                    "Nit_negocio" => $nit_negocio,
                    "Unidad" => $unidad,
                    "Valor" => $valor_minimo
                ];

                $agregar = alertaModelo::agregar_alerta_modelo($datos);

                if ($agregar->rowCount() == 1) {
                    $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Alerta registrada',
                    "Texto" => 'La alerta ha sido registrada exitosamente',
                    "Tipo" => 'success'
                ];
                } else {
                    $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'ocurió un error',
                    "Texto" => 'La alerta no se pudo registrar exitosamente',
                    "Tipo" => 'error'
                ];
                }
            } else {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'ocurió un error',
                    "Texto" => 'Los datos no se han enviado correctamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        } // Fin

        //---------- Controlador para paginar productos con alerta -------------//
        public function paginar_alerta_controlador($pagina, $registros, $url, $filtros) {
            $url = SERVER_URL . $url . "/";
            $tabla = "";
            $nit_negocio = $_SESSION['nit_negocio'];

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE producto.stock_actual < alerta.valor AND alerta.nit_negocio = $nit_negocio "; // 'a' es un alias para la tabla alerta
            
            // Filtros
            if (!empty($filtros['id'])) {
                $where .= "AND producto.id = " . $filtros['id'] . " ";
            }
            if (!empty($filtros['nombre'])) {
                $where .= "AND producto.nombre LIKE '%" . $filtros['nombre'] . "%' ";
            }
            $orderBy = "";
            if (!empty($filtros['orden'])) {
                $orderBy = " ORDER BY producto.stock_actual " . $filtros['orden'] . " ";
            }
        
            $consulta = "SELECT producto.* FROM producto INNER JOIN alerta ON producto.unidad = alerta.unidad
            $where $orderBy LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(producto.id) FROM producto INNER JOIN alerta ON producto.unidad = alerta.unidad
            $where;";

            $conexion = mainModel::conectar();
            
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            $total = $conexion->query($consulta_total);
            $total = (int) $total->fetchColumn();

            $n_paginas = ceil($total / $registros);

            $tabla .= '
            <div class="table-responsive mb-2">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    // Adapta los campos a los de tu tabla 'alerta'
                    $tabla .= '
                    <tr>
                        <td>' . $rows['id'] . '</td>
                        <td>' . $rows['nombre'] . '</td>
                        <td>' . $rows['stock_actual'] . '</td>
                    </tr>
                    ';
                    $contador++;
                }
                $reg_final = $contador - 1;
            } else {
                // Mensajes si no hay datos
                $tabla .= '
                    <tr class="text-center">
                        <td colspan="7">'; // Ajusta el colspan al número de columnas
                if ($total >= 1) {
                    $tabla .= '<a href="' . $url . '1/" class="btn btn-primary">Haga clic acá para recargar el listado</a>';
                } else {
                    $tabla .= 'No hay alertas registradas en el sistema con los filtros actuales.';
                }
                $tabla .= '</td></tr>';
            }

            $tabla .= '</tbody></table></div>';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $tabla .= '<p class="text-left small">Mostrando alertas ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 7);
            }

            return $tabla;
        } // Fin

        //---------- Controlador para paginar alertas -------------//
        public function paginar_alerta_controlador2() {
            $tabla = "";
            $nit_negocio = $_SESSION['nit_negocio'];

            // Construcción del WHERE y ORDER BY
            $where = "WHERE nit_negocio = $nit_negocio "; // 'a' es un alias para la tabla alerta
            $consulta = "SELECT * FROM alerta 
            $where;";

            $conexion = mainModel::conectar();
            
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            // Columnas de la tabla de alertas (ajusta según tus necesidades)
            $tabla .= '
            <div class="table-responsive mb-2">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Unidad de medida</th>
                            <th scope="col">Valor minimo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ';


                foreach ($datos as $rows) {
                    // Adapta los campos a los de tu tabla 'alerta'
                    $tabla .= '
                    <tr>
                        <td>' . $rows['id'] . '</td>
                        <td>' . $rows['unidad'] . '</td>
                        <td>' . $rows['valor'] . '</td>
                        <td>
                        <a href="' . SERVER_URL . 'alerta-update/' . $rows['id'] . '/" class="btn btn-small btn-warning">
                                <i class="fas fa-edit"></i>
                        </a>
                        <form class="FormularioAjax d-inline-block" action="' . SERVER_URL . 'ajax/alertaAjax.php" method="POST" data-form="delete">
                                <input type="hidden" name="id_eliminar" value="' . $rows['id'] . '">
                                <button type="submit" class="btn btn-small btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>

                        </form>
                        </td>
                    </tr>
                    ';
                }

            $tabla .= '</tbody></table></div>';


            return $tabla;
        } // Fin

        //---------- Controlador para eliminar una alerta -------------//
        public function eliminar_alerta_controlador() {
            
            // Recibiendo id de la categoria
            $id = $_POST['id_eliminar'];

            //------------ Comprobando la alerta en BD ------------//
            $check_alerta = mainModel::ejecutar_consulta_simple("SELECT id FROM alerta WHERE id = '$id';");
            if ($check_alerta->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'La alerta que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando rol -----------------//
            session_start(['name' => 'VENTAGO']);
            if ($_SESSION['rol_usuario'] != "Administrador") {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No tienes los permisos necesarios para realizar esta acción',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_alerta = alertaModelo::eliminar_alerta_modelo($id);

            if ($eliminar_alerta->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Alerta eliminada',
                    "Texto" => 'La alerta ha sido eliminada del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar la alerta, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        }

        //---------- Controlador para actualizar una alerta) -------------//
        public function actualizar_estado_alerta_controlador() {
            // Recibiendo datos del formulario
            $id = $_POST['id_actualizar'];
            $valor = $_POST['valor_actualizar'];

            //------------ Comprobando la alerta en BD ------------//
            $check_alerta = mainModel::ejecutar_consulta_simple("SELECT id FROM alerta WHERE id = $id;");
            if ($check_alerta->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'La alerta que intenta actualizar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando rol -----------------//
            session_start(['name' => 'VENTAGO']);
            if ($_SESSION['rol_usuario'] != "Administrador") {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No tienes los permisos necesarios para realizar esta acción',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_alerta = [
                "Id" => $id,
                "Valor" => $valor
            ];

            $actualizar_alerta = alertaModelo::actualizar_alerta_modelo($datos_alerta);

            if ($actualizar_alerta->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Alerta actualizada',
                    "Texto" => 'La alerta ha sido actualizada exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido actualizar la alerta, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        } //Fin

        //---------- Controlador para obtener alertas -------------//
        public function obtener_alertas_controlador() {
            return alertaModelo::obtener_alertas_productos_modelo();
        } // Fin

        //---------- Obtener alerta por id -------------//
        public function obtener_alerta_controlador($id) {
            return alertaModelo::obtener_alerta_modelo($id);
        } // Fin
        //---------- Actualizar alerta -------------//

        //---------- Contar alertas -------------//
        public function contar_alertas_controlador() {
            return alertaModelo::contar_alertas_modelo();
        } // Fin
    }
?>
    