<!-- Ventas -->
<div class="container-fluid">

    <!-- Cabecera de página -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Ventas</h1>
    </div>

    <!-- Filtro de Ventas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Ventas</h6>
        </div>

        <div class="card-body">
            <form id="filtroVentas" method="POST" action="<?php echo SERVER_URL; ?>ventas/">
                <div class="row">

                    <!-- ID de Venta -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id">Id</label>
                            <select name="id" id="filtro_id" class="form-control">
                            <?php
                                require_once "./controladores/ventaControlador.php";
                                $ins_venta = new ventaControlador();
                                $ventas = $ins_venta->obtener_ventas_controlador();

                                if ($ventas) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($ventas as $venta) {
                                        $selected = ($venta->id == $_POST['id']) ? 'selected' : '';
                                        echo '<option value="' . $venta->id . '" ' . $selected . '>' . $venta->id . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay ventas disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Nombre del Cliente -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_cliente">Cliente</label>
                            <select name="cliente" id="filtro_cliente" class="form-control">
                            <?php
                                require_once "./controladores/ventaControlador.php";
                                $ins_venta = new ventaControlador();
                                $ventas = $ins_venta->obtener_ventas_controlador();

                                if ($ventas) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($ventas as $venta) {
                                        $selected = ($venta->nombre_cliente == $_POST['nombre_cliente']) ? 'selected' : '';
                                        echo '<option value="' . $venta->nombre_cliente . '" ' . $selected . '>' . $venta->nombre_cliente . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay clientes disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Rango de Fechas -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_fecha_desde">Fecha Desde</label>
                            <input type="date" class="form-control" id="filtro_fecha_desde" name="fecha_desde" value="<?php echo isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : ''; ?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_fecha_hasta">Fecha Hasta</label>
                            <input type="date" class="form-control" id="filtro_fecha_hasta" name="fecha_hasta" value="<?php echo isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Nombre del Vendedor -->
                    <?php
                    if ($_SESSION['rol_usuario'] == "Administrador") {?>
                        <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_vendedor">Vendedor</label>
                            <select name="vendedor" id="filtro_vendedor" class="form-control">
                            <?php
                                require_once "./controladores/usuarioControlador.php";
                                $ins_usuario = new usuarioControlador();
                                $usuarios = $ins_usuario->obtener_usuarios_controlador();

                                if ($usuarios) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($usuarios as $usuario) {
                                        $selected = ($usuario->nombre == $_POST['vendedor']) ? 'selected' : '';
                                        echo '<option value="' . $usuario->nombre . '" ' . $selected . '>' . $usuario->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay productos disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- Rango Total -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_total_desde">Total Desde</label>
                            <input type="number" class="form-control" id="filtro_total_desde" name="total_desde" step="0.01" value="<?php echo isset($_POST['total_desde']) ? $_POST['total_desde'] : ''; ?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_total_hasta">Total Hasta</label>
                            <input type="number" class="form-control" id="filtro_total_hasta" name="total_hasta" step="0.01" value="<?php echo isset($_POST['total_hasta']) ? $_POST['total_hasta'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Filtro por total (Mayor, Menor) -->
                    <div class="col-md-2">
                        <label for="filtro_total">Filtrar por total:</label>
                        <select name="total" id="filtro_total" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Menor" <?php echo (isset($_POST['total']) && $_POST['total'] == 'Menor') ? 'selected' : ''; ?>>Menor</option>
                            <option value="Mayor" <?php echo (isset($_POST['total']) && $_POST['total'] == 'Mayor') ? 'selected' : ''; ?>>Mayor</option>
                        </select>
                    </div>

                    <!-- Ordenar por Nombre -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_orden">Ordenar Clientes de:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="ASC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'ASC') ? 'selected' : ''; ?>>A-Z</option>
                                <option value="DESC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'DESC') ? 'selected' : ''; ?>>Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones (Buscar, Reestablecer) -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>ventas/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Ventas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Ventas</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/ventaControlador.php";
                $ins_venta = new ventaControlador();

                $filtros = [
                    'id' => isset($_POST['id']) ? trim($_POST['id']) : '',
                    'cliente' => isset($_POST['cliente']) ? trim($_POST['cliente']) : '',
                    'fecha_desde' => isset($_POST['fecha_desde']) ? trim($_POST['fecha_desde']) : '',
                    'fecha_hasta' => isset($_POST['fecha_hasta']) ? trim($_POST['fecha_hasta']) : '',
                    'vendedor' => isset($_POST['vendedor']) ? trim($_POST['vendedor']) : '',
                    'total_desde' => isset($_POST['total_desde']) ? trim($_POST['total_desde']) : '',
                    'total_hasta' => isset($_POST['total_hasta']) ? trim($_POST['total_hasta']) : '',
                    'total' => isset($_POST['total']) ? trim($_POST['total']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_venta->paginar_venta_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>

    <!-- Modal Ver Detalles venta -->
    <div class="modal fade" id="modalVerDetallesventa" tabindex="-1" aria-labelledby="modalVerDetallesventaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVerDetallesventaLabel">Detalles de la venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                <strong>ID venta:</strong> <span id="detalle-venta-id"></span>
                </div>
                <div class="mb-3">
                <strong>Cliente:</strong> <span id="detalle-venta-cliente"></span>
                </div>
                <div class="mb-3">
                <strong>Fecha y Hora:</strong> <span id="detalle-venta-fecha"></span>
                </div>
                <hr>
                <h6>Productos:</h6>
                <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio Venta</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody id="tabla-detalles-venta-body">
                    <!-- Detalles se cargarán aquí -->
                    </tbody>
                </table>
                </div>
                <hr>
                <div class="text-end">
                <strong>Total Venta:</strong> <span id="detalle-venta-total" class="fs-5 fw-bold"></span>
                </div>
            </div>
            <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Add this script, preferably in your main JS file or at the end of your view
    document.addEventListener('DOMContentLoaded', function() {

        const modalElement = document.getElementById('modalVerDetallesventa');
        const ventaModal = new bootstrap.Modal(modalElement); // Initialize Bootstrap Modal
        let lastFocusedButton = null; // Variable to store the button that opened the modal

        // Use event delegation for dynamically added buttons
        document.body.addEventListener('click', function(event) {
            const button = event.target.closest('.btn-ver-detalles-venta');
            if (button) {
                lastFocusedButton = button; // Store the triggering button
                const idventa = button.getAttribute('data-id-venta');
                // Show loading state if desired
                document.getElementById('detalle-venta-id').textContent = 'Cargando...';
                document.getElementById('detalle-venta-cliente').textContent = 'Cargando...';
                document.getElementById('detalle-venta-fecha').textContent = 'Cargando...';
                document.getElementById('tabla-detalles-venta-body').innerHTML = '<tr><td colspan="5">Cargando...</td></tr>';
                document.getElementById('detalle-venta-total').textContent = 'Cargando...';

                ventaModal.show(); // Show modal immediately

                // Prepare data for AJAX request
                const formData = new FormData();
                formData.append('id_venta', idventa); 

                fetch('<?php echo SERVER_URL; ?>ajax/ventaAjax.php', { // Adjust URL to your AJAX handler
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        // Try to parse error json response from server if possible
                        return response.json().then(errData => {
                            throw new Error(errData.message || `HTTP error! status: ${response.status}`);
                        }).catch(() => {
                            // Fallback if response is not json
                            throw new Error(`HTTP error! status: ${response.status}`);
                        });
                    }
                    return response.json();
                 })
                .then(data => {
                    if (data.status === 'success') {
                        // error aqui por ahora
                        const venta = data.factura;
                        const detalles = data.detalles;
                        let totalGeneral = 0;
                        // Populate modal header info
                        document.getElementById('detalle-venta-id').textContent = venta[0].id;
                        document.getElementById('detalle-venta-cliente').textContent = venta[0].nombre_cliente;
                        document.getElementById('detalle-venta-fecha').textContent = venta[0].fecha_hora;

                        // Populate details table
                        const tablaBody = document.getElementById('tabla-detalles-venta-body');
                        tablaBody.innerHTML = ''; // Clear previous content

                        if (detalles && detalles.length > 0) {
                            detalles.forEach(detalle => {
                                const cantidad = parseFloat(detalle.cantidad) || 0;
                                const precio = parseFloat(detalle.precio) || 0;
                                const subtotal = cantidad * precio;
                                // totalGeneral += subtotal; // Accumulate subtotal if needed

                                const row = `<tr>
                                    <td>${detalle.id_producto}</td>
                                    <td>${detalle.nombre_producto || 'N/A'}</td>
                                    <td>${cantidad.toFixed(2)}</td>
                                    <td>${precio.toFixed(2)}</td>
                                    <td>${subtotal.toFixed(2)}</td>
                                </tr>`;
                                tablaBody.innerHTML += row;
                            });
                        } else {
                            tablaBody.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron detalles para esta venta.</td></tr>';
                        }

                        // Populate total (use the total from the main venta record for accuracy)
                        document.getElementById('detalle-venta-total').textContent = (parseFloat(venta[0].total) || 0).toFixed(2);

                    } else {
                        // Handle controlled error response from server
                        console.error('Error fetching details:', data.message);
                        document.getElementById('tabla-detalles-venta-body').innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error: ${data.message || 'Error desconocido.'}</td></tr>`;
                        // Optionally reset other fields too
                        document.getElementById('detalle-venta-id').textContent = 'Error';
                        document.getElementById('detalle-venta-cliente').textContent = 'Error';
                        document.getElementById('detalle-venta-fecha').textContent = 'Error';
                        document.getElementById('detalle-venta-total').textContent = 'Error';
                    }
                })
                .catch(error => {
                    console.error('Error during fetch:', error);
                     // Display fetch/network error in modal
                    document.getElementById('tabla-detalles-venta-body').innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error al cargar los datos: ${error.message}</td></tr>`;
                    // Optionally reset other fields too
                    document.getElementById('detalle-venta-id').textContent = 'Error';
                    document.getElementById('detalle-venta-cliente').textContent = 'Error';
                    document.getElementById('detalle-venta-fecha').textContent = 'Error';
                    document.getElementById('detalle-venta-total').textContent = 'Error';
                });
            }
        });

        // Add event listener for when the modal is completely hidden
        modalElement.addEventListener('hidden.bs.modal', function () {
            if (lastFocusedButton) {
                // Timeout helps ensure the focus is set after the browser finishes processing the hide event
                setTimeout(() => {
                     lastFocusedButton.focus(); // Return focus to the button
                }, 0);
                lastFocusedButton = null; // Clear the stored button reference
            }
            // Optional: Clear modal content when hidden to prevent stale data flash on next open
            document.getElementById('detalle-venta-id').textContent = '';
            document.getElementById('detalle-venta-cliente').textContent = '';
            document.getElementById('detalle-venta-fecha').textContent = '';
            document.getElementById('tabla-detalles-venta-body').innerHTML = '';
            document.getElementById('detalle-venta-total').textContent = '';
        });

    });
</script>