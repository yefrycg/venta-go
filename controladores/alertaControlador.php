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

        } // Fin

        //---------- Controlador para paginar alertas -------------//
        public function paginar_alerta_controlador($pagina, $registros, $url, $filtros) {
            $url = SERVER_URL . $url . "/";
            $tabla = "";
            $nit_negocio = $_SESSION['nit_negocio'];

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            // Construcción del WHERE y ORDER BY
            $where = "WHERE producto.stock_actual < alerta.valor AND alerta.nit_negocio = $nit_negocio "; // 'a' es un alias para la tabla alerta
            
            $orderBy = "";
            if (!empty($filtros['orden'])) {
                $orderBy = " ORDER BY stock " . $filtros['orden'] . " ";
            }
        
            // Ajusta la consulta SELECT y JOINs si son necesarios para obtener 'cantidad_productos'
            // Ejemplo básico (deberás adaptarlo):
            $consulta = "SELECT * FROM producto INNER JOIN alerta ON producto.unidad = alerta.unidad
            $where $orderBy LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(producto.id) FROM producto INNER JOIN alerta ON producto.unidad = alerta.unidad
            $where;";

            $conexion = mainModel::conectar();
            
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            $total = $conexion->query($consulta_total);
            $total = (int) $total->fetchColumn();

            $n_paginas = ceil($total / $registros);

            // Columnas de la tabla de alertas (ajusta según tus necesidades)
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
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 7); // 7 es un ejemplo para el número de enlaces del paginador
            }

            return $tabla;
        } // Fin

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
                        <a href="' . SERVER_URL . 'alerta-update/' . $rows['id'] . '/" class="btn btn-small btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        <form class="FormularioAjax d-inline-block" action="' . SERVER_URL . 'ajax/alertaAjax.php" method="POST" data-form="delete">
                                <input type="hidden" name="id_eliminar" value="' . $rows['id'] . '">
                                <button type="submit" class="btn btn-small btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </form>
                    </tr>
                    ';
                }

            $tabla .= '</tbody></table></div>';


            return $tabla;
        } // Fin

        //---------- Controlador para actualizar una alerta) -------------//
        public function actualizar_estado_alerta_controlador() {

        } //Fin
        //---------- Controlador para obtener alertas -------------//
        public function obtener_alertas_controlador() {
            return alertaModelo::obtener_alertas_productos_modelo();
        } // Fin
    }
?>
    