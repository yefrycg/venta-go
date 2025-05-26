<?php

// Ensure mainModel is included if not autoloaded
// require_once "../modelos/mainModel.php"; 

class reporteControlador extends mainModel {

    private function getDateConditions($date_column_alias, $filtros, &$params_array) {
        $conditions_sql_array = [];
        $fecha_filter = isset($filtros['fecha']) ? $filtros['fecha'] : '';

        if (!empty($date_column_alias)) {
            if ($fecha_filter == "Personalizado" && !empty($filtros['fecha_desde']) && !empty($filtros['fecha_hasta'])) {
                $conditions_sql_array[] = "$date_column_alias >= ?";
                $params_array[] = $filtros['fecha_desde'] . " 00:00:00";
                $conditions_sql_array[] = "$date_column_alias <= ?";
                $params_array[] = $filtros['fecha_hasta'] . " 23:59:59";
            } elseif ($fecha_filter == "Hoy") {
                $conditions_sql_array[] = "DATE($date_column_alias) = CURDATE()";
            } elseif ($fecha_filter == "Semana") {
                $conditions_sql_array[] = "YEARWEEK($date_column_alias, 1) = YEARWEEK(CURDATE(), 1)";
            } elseif ($fecha_filter == "Mes") {
                $conditions_sql_array[] = "YEAR($date_column_alias) = YEAR(CURDATE()) AND MONTH($date_column_alias) = MONTH(CURDATE())";
            }
        }
        return $conditions_sql_array;
    }

    //---------- Controlador para paginar reportes -------------//
    public function paginar_reporte_controlador($pagina_actual, $registros_por_pagina, $url_base_segment, $filtros) {
        
        $tabla = "";
        $nit_negocio_actual = isset($_SESSION['nit_negocio']) ? $_SESSION['nit_negocio'] : null;

        if (empty($nit_negocio_actual)) {
            return '<div class="alert alert-danger text-center" role="alert">Error: NIT del negocio no encontrado en la sesión.</div>';
        }
        
        $url_completa = SERVER_URL . $url_base_segment . "/";
        $tipo_reporte = isset($filtros['tipo_reporte']) ? $filtros['tipo_reporte'] : '';

        if (empty($tipo_reporte)) {
            $tabla = '<div class="alert alert-info text-center" role="alert">Seleccione un tipo de reporte y los filtros deseados, luego presione "Generar reporte".</div>';
            // You might still want to show the chart canvas structure here if it's always visible
            // For now, returning early if no report type is selected.
            // The Area Chart generation logic from the previous response could be integrated here
            // or called separately if it's always displayed.
            // For this response, we focus on the table.
            return $tabla;
        }

        $pagina_actual = (isset($pagina_actual) && $pagina_actual > 0) ? (int) $pagina_actual : 1;
        $inicio = ($pagina_actual > 0) ? (($pagina_actual * $registros_por_pagina) - $registros_por_pagina) : 0;

        $column_headers = [];
        $select_sql_parts = [];
        $from_join_sql = "";
        $where_conditions_sql = ["negocio.nit = ?"]; // Base condition for business
        $query_params = [$nit_negocio_actual];
        $group_by_sql = "";
        $order_by_sql = "";
        $date_column_for_filter = ""; // e.g., "v.fecha_hora"

        // --- Define structure based on report type ---
        switch ($tipo_reporte) {
            case "Ventas totales":
                $column_headers = ["Fecha", "Total Ventas ($)"];
                $select_sql_parts = ["DATE_FORMAT(v.fecha_hora, '%Y-%m-%d') as fecha_venta", "SUM(v.total) as total_ventas_dia"];
                $from_join_sql = "FROM venta v INNER JOIN negocio ON v.nit_negocio = negocio.nit";
                $date_column_for_filter = "v.fecha_hora";
                $group_by_sql = "GROUP BY DATE(v.fecha_hora)";
                $order_by_sql = "ORDER BY DATE(v.fecha_hora) DESC";
                break;

            case "Compras totales":
                $column_headers = ["Fecha", "Total Compras ($)"];
                $select_sql_parts = ["DATE_FORMAT(c.fecha_hora, '%Y-%m-%d') as fecha_compra", "SUM(c.total) as total_compras_dia"];
                $from_join_sql = "FROM compra c INNER JOIN negocio ON c.nit_negocio = negocio.nit";
                $date_column_for_filter = "c.fecha_hora";
                $group_by_sql = "GROUP BY DATE(c.fecha_hora)";
                $order_by_sql = "ORDER BY DATE(c.fecha_hora) DESC";
                break;

            case "Utilidades totales":
                $column_headers = ["Fecha", "Utilidad Total ($)"];
                // Assuming 'venta' and 'compra' tables have a 'total' column
                $select_sql_parts = ["DATE_FORMAT(v.fecha_hora, '%Y-%m-%d') as fecha_venta", "SUM(v.total - c.total) as utilidad_total"];
                $from_join_sql = "FROM venta v INNER JOIN compra c ON v.id_compra = c.id INNER JOIN negocio ON v.nit_negocio = negocio.nit";
                $date_column_for_filter = "v.fecha_hora";
                $group_by_sql = "GROUP BY DATE(v.fecha_hora)";
                $order_by_sql = "ORDER BY DATE(v.fecha_hora) DESC";
                break;
            
            case "Productos Más Vendidos":
                $column_headers = ["Código Producto", "Nombre Producto", "Cantidad Vendida", "Total Vendido ($)"];
                // Assuming 'producto' table has 'codigo' and 'nombre'
                // Assuming 'detalle_venta' has 'id_producto', 'cantidad', 'precio_venta_unitario'
                // Assuming 'venta' table links to 'negocio'
                $select_sql_parts = [
                    "p.id as codigo", 
                    "p.nombre as nombre_producto", 
                    "SUM(dv.cantidad) as cantidad_vendida", 
                    "SUM(dv.cantidad * dv.precio) as total_vendido"
                ];
                $from_join_sql = "FROM producto p 
                                  JOIN venta_producto dv ON p.id = dv.id_producto 
                                  JOIN venta v ON dv.id_venta = v.id
                                  JOIN negocio ON v.nit_negocio = negocio.nit";
                $date_column_for_filter = "v.fecha_hora"; // Filter sales by date
                $group_by_sql = "GROUP BY p.id, p.nombre";
                $order_by_sql = "ORDER BY cantidad_vendida DESC, total_vendido DESC";
                break;
            
            case "Produtos Menos Vendidos":
                $column_headers = ["Código Producto", "Nombre Producto", "Cantidad Vendida", "Total Vendido ($)"];
                // Similar to "Productos Más Vendidos" but ordered by ascending quantity
                $select_sql_parts = [
                    "p.id as codigo", 
                    "p.nombre as nombre_producto", 
                    "SUM(dv.cantidad) as cantidad_vendida", 
                    "SUM(dv.cantidad * dv.precio) as total_vendido"
                ];
                $from_join_sql = "FROM producto p 
                                  JOIN venta_producto dv ON p.id = dv.id_producto 
                                  JOIN venta v ON dv.id_venta = v.id
                                  JOIN negocio ON v.nit_negocio = negocio.nit";
                $date_column_for_filter = "v.fecha_hora"; // Filter sales by date
                $group_by_sql = "GROUP BY p.id, p.nombre";
                $order_by_sql = "ORDER BY cantidad_vendida ASC, total_vendido ASC";
                break;
            
            case "Productos Más comprados":
                $column_headers = ["Código Producto", "Nombre Producto", "Cantidad Comprada", "Total Comprado ($)"];
                // Assuming 'producto' table has 'codigo' and 'nombre'
                // Assuming 'detalle_compra' has 'id_producto', 'cantidad', 'precio_compra_unitario'
                // Assuming 'compra' table links to 'negocio'
                $select_sql_parts = [
                    "p.id as codigo", 
                    "p.nombre as nombre_producto", 
                    "SUM(dc.cantidad) as cantidad_comprada", 
                    "SUM(dc.cantidad * dc.precio_compra) as total_comprado"
                ];
                $from_join_sql = "FROM producto p 
                                  JOIN compra_producto dc ON p.id = dc.id_producto 
                                  JOIN compra c ON dc.id_compra = c.id
                                  JOIN negocio ON c.nit_negocio = negocio.nit";
                $date_column_for_filter = "c.fecha_hora"; // Filter purchases by date
                $group_by_sql = "GROUP BY p.id, p.nombre";
                $order_by_sql = "ORDER BY cantidad_comprada DESC, total_comprado DESC";
                break;
            
            case "Productos Menos Comprados":
                $column_headers = ["Código Producto", "Nombre Producto", "Cantidad Comprada", "Total Comprado ($)"];
                // Similar to "Productos Más comprados" but ordered by ascending quantity
                $select_sql_parts = [
                    "p.id as codigo", 
                    "p.nombre as nombre_producto", 
                    "SUM(dc.cantidad) as cantidad_comprada", 
                    "SUM(dc.cantidad * dc.precio_compra) as total_comprado"
                ];
                $from_join_sql = "FROM producto p 
                                  JOIN compra_producto dc ON p.id = dc.id_producto 
                                  JOIN compra c ON dc.id_compra = c.id
                                  JOIN negocio ON c.nit_negocio = negocio.nit";
                $date_column_for_filter = "c.fecha_hora"; // Filter purchases by date
                $group_by_sql = "GROUP BY p.id, p.nombre";
                $order_by_sql = "ORDER BY cantidad_comprada ASC, total_comprado ASC";
                break;
            
            case "Ventas por vendedor":
                $column_headers = ["Vendedor", "Total Ventas ($)"];
                // Assuming 'usuario' table has 'nombre' and 'rol'
                // Assuming 'venta' table links to 'usuario'
                $select_sql_parts = [
                    "u.nombre as vendedor", 
                    "SUM(v.total) as total_ventas"
                ];
                $from_join_sql = "FROM usuario u 
                                  JOIN venta v ON u.id = v.id_vendedor
                                  JOIN negocio ON v.nit_negocio = negocio.nit";
                $date_column_for_filter = "v.fecha_hora"; // Filter sales by date
                $group_by_sql = "GROUP BY u.id, u.nombre";
                $order_by_sql = "ORDER BY total_ventas DESC";
                break;
            // Add cases for:
            // "Utilidades totales" (complex, requires cost data)
            // "Produtos Menos Vendidos"
            // "Productos Más comprados"
            // "Productos Menos Comprados"
            // "Ventas por vendedor" (requires 'usuario' table with roles and linking venta to usuario)

            default:
                return '<div class="alert alert-warning text-center" role="alert">Tipo de reporte no reconocido o no implementado.</div>';
        }

        // Append date conditions
        $date_conditions_sql_array = $this->getDateConditions($date_column_for_filter, $filtros, $query_params);
        if (!empty($date_conditions_sql_array)) {
            $where_conditions_sql = array_merge($where_conditions_sql, $date_conditions_sql_array);
        }
        
        $where_clause_sql = "";
        if (!empty($where_conditions_sql)) {
            $where_clause_sql = "WHERE " . implode(" AND ", $where_conditions_sql);
        }

        $conexion = mainModel::conectar();
        $datos_reporte = [];
        $total_registros = 0;

        // --- Query for total count (for pagination, if applicable to the report type) ---
        // For grouped results, COUNT(*) of the grouped query is needed, or count distinct groups.
        // This can be complex. For simplicity, if pagination is for items like "Productos Mas Vendidos",
        // the LIMIT applies after ordering. If it's for daily summaries, pagination might be less common or different.
        // For now, let's assume pagination applies to the main query result set.
        
        $consulta_total_sql = "SELECT COUNT(*) ";
        if (!empty($group_by_sql)) { // For grouped queries, count the number of groups
            $consulta_total_sql = "SELECT COUNT(*) FROM (SELECT {$select_sql_parts[0]} {$from_join_sql} {$where_clause_sql} {$group_by_sql}) AS subquery_count";
        } else {
            $consulta_total_sql .= "{$from_join_sql} {$where_clause_sql}";
        }

        try {
            $stmt_total = $conexion->prepare($consulta_total_sql);
            $stmt_total->execute($query_params);
            $total_registros = (int) $stmt_total->fetchColumn();
        } catch (PDOException $e) {
            // Log error: error_log("Error counting records: " . $e->getMessage() . " SQL: " . $consulta_total_sql . " Params: " . json_encode($query_params));
            $total_registros = 0; // Fallback
        }
        
        $n_paginas = ceil($total_registros / $registros_por_pagina);

        // --- Query for data ---
        $consulta_datos_sql = "SELECT " . implode(", ", $select_sql_parts) . " "
                            . $from_join_sql . " "
                            . $where_clause_sql . " "
                            . $group_by_sql . " "
                            . $order_by_sql . " "
                            . "LIMIT $inicio, $registros_por_pagina";
        
        try {
            $stmt_datos = $conexion->prepare($consulta_datos_sql);
            $stmt_datos->execute($query_params);
            $datos_reporte = $stmt_datos->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error: error_log("Error fetching report data: " . $e->getMessage() . " SQL: " . $consulta_datos_sql . " Params: " . json_encode($query_params));
            $datos_reporte = []; // Fallback
             $tabla .= '<div class="alert alert-danger">Error al generar el reporte: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }


        // --- Build HTML Table ---
        $tabla .= '
        <div class="table-responsive mb-2">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>';
        foreach ($column_headers as $header) {
            $tabla .= '<th scope="col">' . htmlspecialchars($header) . '</th>';
        }
        $tabla .= '
                    </tr>
                </thead>
                <tbody>
        ';

        if ($total_registros >= 1 && $pagina_actual <= $n_paginas) {
            $contador_filas = $inicio + 1;
            foreach ($datos_reporte as $row) {
                $tabla .= '<tr>';
                foreach ($row as $key => $value) {
                    // Format currency if applicable, or specific formatting per column
                    if (is_numeric($value) && (strpos(strtolower($key), 'total') !== false || strpos(strtolower($key), 'precio') !== false || strpos(strtolower($key), 'monto') !== false)) {
                        $tabla .= '<td>$' . number_format($value, 2) . '</td>';
                    } else {
                        $tabla .= '<td>' . htmlspecialchars($value) . '</td>';
                    }
                }
                $tabla .= '</tr>';
                $contador_filas++;
            }
            $reg_inicio = $inicio + 1;
            $reg_final = $contador_filas - 1;
        } else {
            if ($total_registros >= 1) { // Data exists, but current page is out of bounds
                $tabla .= '
                <tr class="text-center">
                    <td colspan="' . count($column_headers) . '">
                        <a href="' . $url_completa . '1/" class="btn btn-primary">
                            Haga clic acá para recargar el listado (Ir a la página 1)
                        </a>
                    </td>
                </tr>
                ';
            } else { // No records found for the filters
                $tabla .= '
                <tr class="text-center">
                    <td colspan="' . count($column_headers) . '">No hay registros que coincidan con los filtros seleccionados.</td>
                </tr>
                ';
            }
        }

        $tabla .= '
                </tbody>
            </table>
        </div>
        ';

        if ($total_registros >= 1 && $pagina_actual <= $n_paginas) {
            $tabla .= '<p class="text-left">Mostrando registros ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total_registros . '</p>';
            // Ensure $url_completa is correctly formatted for paginador_tablas if it expects query params for filters
            // For simplicity, this example assumes filters are resubmitted with each page request via the form.
            // If using GET for pagination, filter params need to be appended to pagination URLs.
            $tabla .= mainModel::paginador_tablas($pagina_actual, $n_paginas, $url_completa, 10); // 10 is $botones_maximos
        }
        
        // Here you could also append the Area Chart HTML and JS if needed,
        // passing the relevant data to it based on $tipo_reporte and $filtros.

        return $tabla;
    }
}
?>