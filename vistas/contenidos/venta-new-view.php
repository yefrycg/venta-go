<!-- Registro de ventas -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Registrar Venta</h1>
    <div class="row gy-4">

        <!-- Venta Producto -->
        <div class="col-xl-8 mb-4">
            <div class="text-white bg-primary p-1 text-center">
                Detalles de la Venta
            </div>
            <div class="p-3 border border-3 border-primary">
                <div class="row">

                    <!-- Producto -->
                    <div class="col-12">
                        <div class="form-group">
                            <label for="producto_id">Busque un producto aquí:</label>
                            <select name="producto_id" id="producto_id" class="form-control selectpicker" data-live-search="true" data-size="1" title="Busque un producto aquí" required>
                                <?php
                                require_once "./controladores/productoControlador.php";
                                $ins_producto = new productoControlador();
                                $productos = $ins_producto->obtener_productos_controlador();

                                if ($productos) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($productos as $producto) {
                                        echo '<option value="' . $producto->id . '" 
                                                    data-unidad="' . $producto->unidad . '" 
                                                    data-stock="' . $producto->stock_actual . '" 
                                                    data-precio="' . $producto->precio_actual . '">
                                                    ' . $producto->nombre . '
                                            </option>';
                                    }
                                } else {
                                    echo '<option value="">No hay productos disponibles</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Unidad de Medida -->
                    <div class="col-6 mb-4">
                        <label for="unidad">Unidad de medida:</label>
                        <input disabled name="unidad" id="unidad" type="text" class="form-control">
                    </div>

                    <!-- Stock -->
                    <div class="col-6 mb-4">
                        <label for="stock">Stock:</label>
                        <input disabled name="stock" id="stock" type="number" min="0" step="0.00" class="form-control">
                    </div>

                    <!-- Precio de Venta -->
                    <div class="col-6 mb-4">
                        <label for="precio_venta">Precio de venta:</label>
                        <input disabled type="number" name="precio_venta" id="precio_venta" class="form-control" min="0" step="0.00">
                    </div>

                    <!-- Cantidad -->
                    <div class="col-6 mb-4">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="0" step="0.00" value="0.00" required>
                    </div>

                    <!-----botón para agregar--->
                    <div class="col-12 mb-4 text-end">
                        <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                    </div>

                    <!-- Tabla para el detalle de la venta -->
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="tabla_detalle" class="table table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-white">#</th>
                                        <th class="text-white">Producto</th>
                                        <th class="text-white">Cantidad</th>
                                        <th class="text-white">Precio venta</th>
                                        <th class="text-white">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th colspan="4">Total</th>
                                        <th colspan="2"><input type="hidden" name="total" value="0" id="inputTotal"> <span id="total">0</span></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Venta -->
        <div class="col-xl-4">
            <div class="text-white bg-success p-1 text-center">
                Datos Generales
            </div>

            <div class="p-3 border border-3 border-success">
                <!-- Formulario de registro -->
                <form class="FormularioAjax" action="<?php echo SERVER_URL; ?>ajax/ventaAjax.php" method="POST" data-form="save" autocomplete="off">
                    
                    <input type="hidden" name="vendedor_id" id="vendedor_id_hidden">
                    <input type="hidden" name="cliente_id" id="cliente_id_hidden">
                    <input type="hidden" name="nombre" id="nombre_hidden">
                    <input type="hidden" name="detalles" id="detalles_hidden">
                    <input type="hidden" name="total" id="total_hidden">

                    <!-- Cliente id input -->
                    <div class="col-sm-6 mb-4">
                        <label for="cliente_id">Cedula del cliente:</label>
                        <input type="number" name="cliente_id" id="cliente_id" class="form-control border-success" value="..." required>
                    </div>
                    
                    <!-- Cliente nombre input -->
                    <div class="col-sm-6 mb-4">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control border-success" placeholder="..." required>
                    </div>

                    <!-- Fecha -->
                    <div class="col-sm-6 mb-4">
                        <label for="fecha">Fecha:</label>
                        <input readonly type="date" name="fecha" id="fecha" class="form-control border-success" value="<?php echo date("Y-m-d"); ?>" required>
                    </div>

                    <!-- Usuario -->
                    <input type="hidden" name="vendedor_id" id="vendedor_id" value="<?php echo $_SESSION['id_usuario']; ?>">

                    <!-- Botón de guardar -->
                    <div class="col-12 mb-4 mt-2 text-center">
                        <button type="submit" class="btn btn-success" id="guardar">Finalizar venta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let detalles = [];
    let subtotal = 0;

    // Actualizar campos dinámicos al seleccionar un producto
    document.getElementById("producto_id").addEventListener("change", function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById("unidad").value = selectedOption.getAttribute("data-unidad") || "";
        document.getElementById("stock").value = selectedOption.getAttribute("data-stock") || 0;
        document.getElementById("precio_venta").value = selectedOption.getAttribute("data-precio") || 0.00;
    });

    // Agregar un producto a la tabla de detalles
    document.getElementById("btn_agregar").addEventListener("click", function() {
        const productoId = document.getElementById("producto_id").value;
        const stock = document.getElementById("stock").value;
        const cantidad = parseFloat(document.getElementById("cantidad").value);
        const precioVenta = parseFloat(document.getElementById("precio_venta").value);

        if (productoId && cantidad > 0 && precioVenta > 0) {
            if (cantidad <= stock) {
                const productoNombre = document.querySelector(`#producto_id option[value="${productoId}"]`).textContent;
                const subtotalItem = cantidad * (precioVenta);

                detalles.push({
                    productoId,
                    productoNombre,
                    cantidad,
                    precioVenta,
                    subtotalItem
                });

                subtotal += subtotalItem;
                document.getElementById("cantidad").value = 0; // Reiniciar cantidad
                document.getElementById("producto_id").value = ""; // Reiniciar producto
                document.getElementById("unidad").value = ""; // Reiniciar unidad
                document.getElementById("stock").value = ""; // Reiniciar stock
                document.getElementById("precio_venta").value = ""; // Reiniciar precio
                document.getElementById("producto_id").dispatchEvent(new Event('change')); // Actualizar campos dinámicos
                actualizarTabla();
            } else {
                Swal.fire({
                    title: "Error",
                    text: "La cantidad debe ser menor o igual que el stock",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            }
        } else {
            Swal.fire({
                title: "Error",
                text: "Por favor, complete todos los campos correctamente.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
        }
    });

    // Actualizar la tabla de detalles
    function actualizarTabla() {
        const tabla = document.getElementById("tabla_detalle").getElementsByTagName("tbody")[0];
        tabla.innerHTML = ""; // Limpiar tabla

        detalles.forEach((detalle, index) => {
            const row = tabla.insertRow();
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${detalle.productoNombre}</td>
                <td>${detalle.cantidad}</td>
                <td>${detalle.precioVenta.toFixed(2)}</td>
                <td>${detalle.subtotalItem.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm" onclick="eliminarDetalle(${index})">Eliminar</button></td>
            `;
        });

        const total = subtotal;
        document.getElementById("total").textContent = total.toFixed(2);
        document.getElementById("inputTotal").value = subtotal.toFixed(2);
    }

    function eliminarDetalle(index) {
        subtotal -= detalles[index].subtotalItem;
        detalles.splice(index, 1);
        actualizarTabla();
    }

    document.getElementById("guardar").addEventListener("click", async function(e) {
        e.preventDefault();

        const vendedorId = document.getElementById("vendedor_id").value;
        const clienteId = document.getElementById("cliente_id").value;
        const nombre = document.getElementById("nombre").value;
        const total = document.getElementById("inputTotal").value;

        if (detalles.length === 0) {
            Swal.fire({
                title: "Error",
                text: "Debe agregar al menos un producto.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            return;
        }

        // Llenar campos ocultos
        document.getElementById("vendedor_id_hidden").value = vendedorId;
        document.getElementById("cliente_id_hidden").value = clienteId;
        document.getElementById("nombre_hidden").value = nombre;
        document.getElementById("detalles_hidden").value = JSON.stringify(detalles);
        document.getElementById("total_hidden").value = total;

        const form = document.querySelector(".FormularioAjax");
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: form.method,
                body: formData
            });

            const result = await response.json();

            if (result.Alerta === "limpiar") {
                Swal.fire({
                    title: result.Titulo,
                    text: result.Texto,
                    icon: result.Tipo,
                    confirmButtonText: "Aceptar"
                }).then(() => {
                    window.location.reload(); // Recargar la página
                });
            } else {
                Swal.fire({
                    title: result.Titulo,
                    text: result.Texto,
                    icon: result.Tipo,
                    confirmButtonText: "Aceptar"
                });
            }
        } catch (error) {
            console.error("Error al procesar la solicitud:", error);
            Swal.fire({
                title: "Error inesperado",
                text: "No se pudo completar la solicitud. Intente nuevamente.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
        }
    });
</script>