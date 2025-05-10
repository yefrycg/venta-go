<?php
    if ($peticionAjax) {
        require_once "../modelos/compraModelo.php";
    } else {
        require_once "./modelos/compraModelo.php";
    }

    class compraControlador extends compraModelo {

        //---------- Controlador para finalizar una compra -----------------//
        public function finalizar_compra_controlador() {
            session_start(['name' => 'VENTAGO']);
            try {
                $id_proveedor = $_POST['id_proveedor'];
                $detalles = json_decode($_POST['detalles'], true);
                $total_compra = $_POST['total'];

                if (!$id_proveedor || !$detalles) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Datos incompletos.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                // Insertar la compra
                $compra_id = compraModelo::agregar_compra_modelo([
                    "Id_proveedor" => $id_proveedor,
                    "Total_compra" => $total_compra,
                    "Nit_negocio" => $_SESSION['nit_negocio']
                ]);
                if (!$compra_id) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Error al registrar la compra.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                // Insertar los detalles de la compra
                foreach ($detalles as $detalle) {
                    $resultado = compraModelo::agregar_compra_producto_modelo([
                        "Id_compra" => $compra_id,
                        "Id_producto" => $detalle["productoId"],
                        "Cantidad" => $detalle["cantidad"],
                        "Precio_compra" => $detalle["precioCompra"],
                        "Precio_venta" => $detalle["precioVenta"]
                    ]);
                    if (!$resultado) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "Error al registrar un detalle de la compra.",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                }
                $detalles = json_encode($detalles);
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Compra registrada",
                    "Texto" => "La compra fue registrada exitosamente.",
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
        }

        //---------- Controlador paginar compras -----------------//
        public function paginar_compra_controlador($pagina, $registros, $url, $filtros) {
            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $nit_negocio = $_SESSION['nit_negocio'];

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE compra.nit_negocio = $nit_negocio"; // Condición base

            // Filtros básicos
            if (isset($filtros['id']) && $filtros['id'] != "") {
                $where .= " AND compra.id = " . (int)$filtros['id'];
            }
            if (isset($filtros['proveedor']) && $filtros['proveedor'] != "") {
                $where .= " AND proveedor.nombre LIKE '%" . $filtros['proveedor'] . "%'";
            }
            if (isset($filtros['fecha_desde']) && $filtros['fecha_desde'] != "") {
                $where .= " AND DATE(compra.fecha_hora) >= '" . $filtros['fecha_desde'] . "'";
            }
            if (isset($filtros['fecha_hasta']) && $filtros['fecha_hasta'] != "") {
                $where .= " AND DATE(compra.fecha_hora) <= '" . $filtros['fecha_hasta'] . "'";
            }
            if (isset($filtros['total_desde']) && $filtros['total_desde'] != "") {
                $where .= " AND (compra.total) >= " . (float)$filtros['total_desde'];
            }
            if (isset($filtros['total_hasta']) && $filtros['total_hasta'] != "") {
                $where .= " AND (compra.total) <= " . (float)$filtros['total_hasta'];
            }
            
            // Filtros avanzados para totales
            if (isset($filtros['total']) && $filtros['total'] != "") {
                $subWhere = str_replace("compra.", "c.", $where); // Adaptar alias para la subconsulta
                $subWhere = str_replace("proveedor.", "p.", $subWhere); // Adaptar alias para la subconsulta
                if ($filtros['total'] == "Mayor") {
                    $where .= " AND (compra.total) = (SELECT MAX(c.total) FROM compra AS c 
                    INNER JOIN proveedor AS p ON c.id_proveedor = p.id " . $subWhere . ")";
                } else {
                    $where .= " AND (compra.total) = (SELECT MIN(c.total) FROM compra AS c 
                    INNER JOIN proveedor AS p ON c.id_proveedor = p.id " . $subWhere . ")";
                }
            }
            
            // Orden
            $orderBy = "ORDER BY compra.id DESC";
            if (isset($filtros['orden']) && $filtros['orden'] != "") {
                $orden = ($filtros['orden'] === 'ASC') ? 'ASC' : 'DESC';
                $orderBy = "ORDER BY proveedor.nombre $orden";
            }
            
            // Consulta principal
            $consulta = "SELECT compra.*, 
                proveedor.nombre AS nombre_proveedor
            FROM compra
            INNER JOIN proveedor ON compra.id_proveedor = proveedor.id
            $where
            $orderBy
            LIMIT $inicio, $registros;";
            
            // Consulta para el total
            $consulta_total = "SELECT COUNT(*) AS total
            FROM compra
            INNER JOIN proveedor ON compra.id_proveedor = proveedor.id
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
                            <th scope="col">Proveedor</th>
                            <th scope="col">Fecha y Hora</th>
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
                            <td>' . $rows['nombre_proveedor'] . '</td>
                            <td>' . $rows['fecha_hora'] . '</td>
                            <td>' . $rows['total'] . '</td>
                            <td>
                            <button type="button" class="btn btn-small btn-info btn-ver-detalles" data-id-compra="' . $rows['id'] . '">
                            <i class="fas fa-eye"></i> Ver Detalles
                            </button>
                            </td>
                        </tr>';
                    $contador++;
                }
                $reg_final = $contador - 1;
            } else {
                $tabla .= '<tr class="text-center"><td colspan="6">No hay registros en el sistema</td></tr>';
            }

            $tabla .= '</tbody></table></div>';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $tabla .= '<p class="text-left">Mostrando compra ' . $reg_inicio . ' a la ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        }

        //---------- Ver detalles de compra -------------//
        public function ver_detalle_compra_controlador() { // Changed: Removed parameter, will get from POST
            // Check if it's an AJAX request and ID is provided
            if (isset($_POST['id_compra'])) {
                $id_compra = $_POST['id_compra']; // Assuming you might encrypt IDs
    
                // Fetch data using the model
                $datos_compra = compraModelo::ver_detalle_compra_modelo($id_compra);
    
                if ($datos_compra && isset($datos_compra['compra']) && isset($datos_compra['detalles'])) {
                    // Prepare data for JSON response
                    $response = [
                        'status' => 'success',
                        'compra' => $datos_compra['compra'], // Contains provider name, date, total
                        'detalles' => $datos_compra['detalles'] // Contains product details
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'No se pudieron obtener los detalles de la compra.'
                    ];
                }
                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit(); // Stop script execution after sending JSON
            } else {
                // Handle non-AJAX or missing ID case if necessary
                // For example, redirect or show an error page
                // Or return a simple error JSON
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'ID de compra no proporcionado.']);
                exit();
            }
        } // Fin
        
        //---------- Controlador para obtener compras -------------//
        public function obtener_compras_controlador() {
            return compraModelo::obtener_compras_modelo();
        } //Fin

        //---------- Controlador para obtener cantidad de compras -------------//
        public function obtener_cantidad_compras_controlador() {
            return compraModelo::obtener_cantidad_compras_modelo();
        }
    }