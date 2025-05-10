<!-- Compras -->
<div class="container-fluid">

    <!-- Cabecera página -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Compras</h1>
        <a href="<?php echo SERVER_URL; ?>compra-new/" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Añadir</span>
        </a>
    </div>

    <!-- Filtro de Compras -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Compras</h6>
        </div>
        <div class="card-body">
            <form id="filtroCompras" method="POST" action="<?php echo SERVER_URL; ?>compras/">
                <div class="row">
                    <!-- ID de Compra -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id">Id</label>
                            <select name="id" id="filtro_id" class="form-control">
                            <?php
                                require_once "./controladores/compraControlador.php";
                                $ins_compra = new compraControlador();
                                $compras = $ins_compra->obtener_compras_controlador();

                                if ($compras) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($compras as $compra) {
                                        $selected = ($compra->id == $_POST['id']) ? 'selected' : '';
                                        echo '<option value="' . $compra->id . '" ' . $selected . '>' . $compra->id . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay compras disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Nombre del Proveedor -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_proveedor">Nombre del Proveedor</label>
                            <select name="proveedor" id="filtro_proveedor" class="form-control">
                            <?php
                                require_once "./controladores/proveedorControlador.php";
                                $ins_proveedor = new proveedorControlador();
                                $proveedores = $ins_proveedor->obtener_proveedores_controlador();

                                if ($proveedores) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($proveedores as $proveedor) {
                                        $selected = ($proveedor->nombre == $_POST['proveedor']) ? 'selected' : '';
                                        echo '<option value="' . $proveedor->nombre . '" ' . $selected . '>' . $proveedor->nombre . ' '. $proveedor->apellidos .'</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay proveedores disponibles</option>';
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

                    <!-- Filtro por total -->
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
                            <label for="filtro_orden">Ordenar proveedores de:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="ASC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'ASC') ? 'selected' : ''; ?>>A-Z</option>
                                <option value="DESC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'DESC') ? 'selected' : ''; ?>>Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>compras/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Compras -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Compras</h6>
        </div>
        <div class="card-body">
            <?php
                require_once "./controladores/compraControlador.php";
                $ins_compra = new compraControlador();

                $filtros = [
                    'id' => isset($_POST['id']) ? trim($_POST['id']) : '',
                    'proveedor' => isset($_POST['proveedor']) ? trim($_POST['proveedor']) : '',
                    'fecha_desde' => isset($_POST['fecha_desde']) ? trim($_POST['fecha_desde']) : '',
                    'fecha_hasta' => isset($_POST['fecha_hasta']) ? trim($_POST['fecha_hasta']) : '',
                    'total_desde' => isset($_POST['total_desde']) ? trim($_POST['total_desde']) : '',
                    'total_hasta' => isset($_POST['total_hasta']) ? trim($_POST['total_hasta']) : '',
                    'total' => isset($_POST['total']) ? trim($_POST['total']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_compra->paginar_compra_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>

    <!-- Modal Ver Detalles Compra -->
    <div class="modal fade" id="modalVerDetallesCompra" tabindex="-1" aria-labelledby="modalVerDetallesCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalVerDetallesCompraLabel">Detalles de la Compra</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
            <strong>ID Compra:</strong> <span id="detalle-compra-id"></span>
            </div>
            <div class="mb-3">
            <strong>Proveedor:</strong> <span id="detalle-compra-proveedor"></span>
            </div>
            <div class="mb-3">
            <strong>Fecha y Hora:</strong> <span id="detalle-compra-fecha"></span>
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
                    <th>Precio Compra</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody id="tabla-detalles-compra-body">
                <!-- Detalles se cargarán aquí -->
                </tbody>
            </table>
            </div>
            <hr>
            <div class="text-end">
            <strong>Total Compra:</strong> <span id="detalle-compra-total" class="fs-5 fw-bold"></span>
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

        const modalElement = document.getElementById('modalVerDetallesCompra');
        const compraModal = new bootstrap.Modal(modalElement); // Initialize Bootstrap Modal
        let lastFocusedButton = null; // Variable to store the button that opened the modal

        // Use event delegation for dynamically added buttons
        document.body.addEventListener('click', function(event) {
            const button = event.target.closest('.btn-ver-detalles');
            if (button) {
                lastFocusedButton = button; // Store the triggering button
                const idCompra = button.getAttribute('data-id-compra');

                // Show loading state if desired
                document.getElementById('detalle-compra-id').textContent = 'Cargando...';
                document.getElementById('detalle-compra-proveedor').textContent = 'Cargando...';
                document.getElementById('detalle-compra-fecha').textContent = 'Cargando...';
                document.getElementById('tabla-detalles-compra-body').innerHTML = '<tr><td colspan="5">Cargando...</td></tr>';
                document.getElementById('detalle-compra-total').textContent = 'Cargando...';

                compraModal.show(); // Show modal immediately

                // Prepare data for AJAX request
                const formData = new FormData();
                formData.append('id_compra', idCompra); // Send plain ID if not encrypted

                // AJAX request using Fetch API
                fetch('<?php echo SERVER_URL; ?>ajax/compraAjax.php', { // Adjust URL to your AJAX handler
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
                        const compra = data.compra;
                        const detalles = data.detalles;
                        let totalGeneral = 0; // Recalculate or use compra.total

                        // Populate modal header info
                        document.getElementById('detalle-compra-id').textContent = compra.id;
                        document.getElementById('detalle-compra-proveedor').textContent = compra.nombre_proveedor;
                        document.getElementById('detalle-compra-fecha').textContent = compra.fecha_hora;

                        // Populate details table
                        const tablaBody = document.getElementById('tabla-detalles-compra-body');
                        tablaBody.innerHTML = ''; // Clear previous content

                        if (detalles && detalles.length > 0) {
                            detalles.forEach(detalle => {
                                const cantidad = parseFloat(detalle.cantidad) || 0;
                                const precio = parseFloat(detalle.precio_compra) || 0;
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
                            tablaBody.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron detalles para esta compra.</td></tr>';
                        }

                        // Populate total (use the total from the main compra record for accuracy)
                        document.getElementById('detalle-compra-total').textContent = (parseFloat(compra.total) || 0).toFixed(2);

                    } else {
                        // Handle controlled error response from server
                        console.error('Error fetching details:', data.message);
                        document.getElementById('tabla-detalles-compra-body').innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error: ${data.message || 'Error desconocido.'}</td></tr>`;
                        // Optionally reset other fields too
                        document.getElementById('detalle-compra-id').textContent = 'Error';
                        document.getElementById('detalle-compra-proveedor').textContent = 'Error';
                        document.getElementById('detalle-compra-fecha').textContent = 'Error';
                        document.getElementById('detalle-compra-total').textContent = 'Error';
                    }
                })
                .catch(error => {
                    console.error('Error during fetch:', error);
                     // Display fetch/network error in modal
                    document.getElementById('tabla-detalles-compra-body').innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error al cargar los datos: ${error.message}</td></tr>`;
                    // Optionally reset other fields too
                    document.getElementById('detalle-compra-id').textContent = 'Error';
                    document.getElementById('detalle-compra-proveedor').textContent = 'Error';
                    document.getElementById('detalle-compra-fecha').textContent = 'Error';
                    document.getElementById('detalle-compra-total').textContent = 'Error';
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
            document.getElementById('detalle-compra-id').textContent = '';
            document.getElementById('detalle-compra-proveedor').textContent = '';
            document.getElementById('detalle-compra-fecha').textContent = '';
            document.getElementById('tabla-detalles-compra-body').innerHTML = '';
            document.getElementById('detalle-compra-total').textContent = '';
        });

    });
</script>