<?php

    if ($peticionAjax) {
        require_once "../modelos/ventaModelo.php";
    } else {
        require_once "./modelos/ventaModelo.php";
    }

    class ventaControlador extends ventaModelo {
        
        //---------- Controlador para finalizar una venta -----------------//
        public function finalizar_venta_controlador() {
            try {
                session_start(['name' => 'VENTAGO']);
                $cedula_cliente = $_POST['cliente_id'];
                $nombre_cliente = $_POST['nombre'];
                $nit_negocio = $_SESSION['nit_negocio'];
                $cod_vendedor = $_POST['vendedor_id']; // Suponiendo que es el ID del vendedor logueado
                $detalles = json_decode($_POST['detalles'], true);
                $total_venta = $_POST['total'];
                
                if (!$cedula_cliente || !$cod_vendedor || !$detalles) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Datos incompletos o mal formateados",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                // Insertar la venta
                $venta_id = ventaModelo::agregar_venta_modelo([
                    "Nit_negocio" => $nit_negocio,
                    "Id_vendedor" => $cod_vendedor,
                    "Cedula_cliente" => $cedula_cliente,
                    "Nombre_cliente" => $nombre_cliente,
                    "Total_venta" => $total_venta
                ]);
                if (!$venta_id) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Error al registrar la venta.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                // Insertar los productos de la venta
                foreach ($detalles as $detalle) {
                    $resultado = ventaModelo::agregar_venta_producto_modelo([
                        "Id_venta" => $venta_id,
                        "Id_producto" => $detalle["productoId"],
                        "Cantidad" => $detalle["cantidad"],
                        "Precio" => $detalle["precioVenta"]
                    ]);

                    if (!$resultado) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "Error al registrar un detalle de la venta.",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                }
                $detalles = json_encode($detalles);
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Venta registrada",
                    "Texto" => "La venta fue registrada exitosamente.",
                    "Tipo" => "success"
                ];
                echo json_encode($alerta);
            } catch (Exception $e) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => $e->getMessage(),
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
            }
        } //Fin

        //---------- Controlador para paginar ventas -----------------//
        public function paginar_venta_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";
            $nit_negocio = $_SESSION['nit_negocio'];

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE venta.nit_negocio = $nit_negocio"; // Condición base

            // Filtros básicos
            if (isset($filtros['id']) && $filtros['id'] != "") {
                $where .= " AND venta.id = " . (int)$filtros['id'];
            }
            if (isset($filtros['cliente']) && $filtros['cliente'] != "") {
                $where .= " AND ( venta.nombre_cliente LIKE '%" . $filtros['cliente'] . "%')";
            }
            if (isset($filtros['fecha_desde']) && $filtros['fecha_desde'] != "") {
                $where .= " AND DATE(venta.fecha_hora) >= '" . $filtros['fecha_desde'] . "'";
            }
            if (isset($filtros['fecha_hasta']) && $filtros['fecha_hasta'] != "") {
                $where .= " AND DATE(venta.fecha_hora) <= '" . $filtros['fecha_hasta'] . "'";
            }
            //verificar que el usaurio sea administrador
            if ($_SESSION['rol_usuario'] == "Administrador") {
                if (isset($filtros['vendedor']) && $filtros['vendedor'] != "") {
                $where .= " AND usuario.nombre LIKE '%" . $filtros['vendedor'] . "%'";
                }
            } else {
                // Si no es administrador, filtrar por el vendedor logueado
                $where .= " AND usuario.nombre =  '" . $_SESSION['nombre_usuario'] . "'";
            }
            if (isset($filtros['total_desde']) && $filtros['total_desde'] != "") {
                $where .= " AND (venta.total) >= " . (float)$filtros['total_desde'];
            }
            if (isset($filtros['total_hasta']) && $filtros['total_hasta'] != "") {
                $where .= " AND (venta.total) <= " . (float)$filtros['total_hasta'];
            }

            // Filtros avanzados para totales
            if (isset($filtros['total']) && $filtros['total'] != "") {
                $subWhere = str_replace("venta.", "v.", $where); // Adaptar alias para la subconsulta
                $subWhere = str_replace("usuario.", "u.", $subWhere); // Adaptar alias para la subconsulta
                if ($filtros['total'] == "Mayor") {
                    $where .= " AND (venta.total) = (SELECT MAX(v.total) FROM venta AS v 
                    INNER JOIN usuario AS u  ON v.id_vendedor = u.id " . $subWhere . ")";
                } else {
                    $where .= " AND (venta.total) = (SELECT MIN(v.total) FROM venta AS v 
                    INNER JOIN usuario AS u  ON v.id_vendedor = u.id " . $subWhere . ")";
                }
            }

            // Orden
            $orderBy = "ORDER BY venta.id DESC";
            if (isset($filtros['orden']) && $filtros['orden'] != "") {
                $orden = ($filtros['orden'] === 'ASC') ? 'ASC' : 'DESC';
                $orderBy = "ORDER BY venta.nombre_cliente $orden";
            }

            // Consulta principal
            $consulta = "SELECT venta.*,usuario.nombre AS nombre_vendedor
            FROM venta
            INNER JOIN usuario ON venta.id_vendedor = usuario.id
            $where
            $orderBy
            LIMIT $inicio, $registros;";

            // Consulta para el total
            $consulta_total = "SELECT COUNT(*) AS total
            FROM venta
            INNER JOIN usuario ON venta.id_vendedor = usuario.id
            $where;";

            // Conexión y ejecución
            $conexion = mainModel::conectar();

            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            $total = $conexion->query($consulta_total);
            $total = (int)$total->fetchColumn();

            $n_paginas = ceil($total / $registros);

            // Construir tabla
            $tabla .= '<div class="table-responsive mb-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Fecha y Hora</th>
                            <th scope="col">Vendedor</th>
                            <th scope="col">Total</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                        <tr>
                            <td>' . $rows['id'] . '</td>
                            <td>' . $rows['nombre_cliente'] . '</td>
                            <td>' . $rows['fecha_hora'] . '</td>
                            <td>' . $rows['nombre_vendedor'] . '</td>
                            <td>' . $rows['total'] . '</td>
                            <td>
                            <button type="button" class="btn btn-small btn-info btn-ver-detalles-venta" data-id-venta="' . $rows['id'] . '">
                            <i class="fas fa-eye"></i> Ver Detalles
                            </button>
                            </td>
                        </tr>';
                    $contador++;
                }
                $reg_final = $contador - 1;
            } else {
                $tabla .= '<tr class="text-center"><td colspan="7">No hay registros en el sistema</td></tr>';
            }

            $tabla .= '</tbody></table></div>';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $tabla .= '<p class="text-left">Mostrando venta ' . $reg_inicio . ' a la ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        } //Fin
        
        //-------- Controlador para ver detalle de venta -----------//
        public function ver_detalle_venta_controlador($id_venta) {
    
                $datos_venta = ventaModelo::ver_detalle_venta_modelo($id_venta);
    
                if ($datos_venta && isset($datos_venta['factura']) && isset($datos_venta['detalles'])) {
                    // Prepare data for JSON response
                    $response = [
                        'status' => 'success',
                        'factura' => $datos_venta['factura'], // Contains provider name, date, total
                        'detalles' => $datos_venta['detalles'] // Contains product details
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'No se pudieron obtener los detalles de la venta.'
                    ];
                }
                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit(); // Stop script execution after sending JSON
        } //Fin

        //---------- Controlador para obtener ventas -------------//
        public function obtener_ventas_controlador() {
            return ventaModelo::obtener_ventas_modelo();
        } //Fin

        //---------- Controlador para obtener cantidad de ventas -------------//
        public function obtener_cantidad_ventas_controlador() {
            return ventaModelo::obtener_cantidad_ventas_modelo();
        } //Fin
    }