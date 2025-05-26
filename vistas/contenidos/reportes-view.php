<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Cabecera de página principal -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Reportes</h1>
    </div>

    <!-- Filtro de Reportes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Reportes</h6>
        </div>

        <div class="card-body">
            <form id="filtroreportes" method="POST" action="<?php echo SERVER_URL; ?>reportes/">
                <div class="row">

                    <!--  Rango de Fechas -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_fehca">Fecha</label>
                            <select name="filtro_fecha" id="filtro_fecha" class="form-control" required>
                                <option value="" selected>Seleccione</option>
                                <option value="Hoy" <?php echo (isset($_POST['filtro_fecha']) && $_POST['filtro_fecha'] == 'Hoy') ? 'selected' : ''; ?> >Hoy</option>
                                <option value="Semana" <?php echo (isset($_POST['filtro_fecha']) && $_POST['filtro_fecha'] == 'Semana') ? 'selected' : ''; ?> >Última Semana</option>
                                <option value="Mes" <?php echo (isset($_POST['filtro_fecha']) && $_POST['filtro_fecha'] == 'Mes') ? 'selected' : ''; ?> >Último Mes</option>
                                <option value="Personalizado" <?php echo (isset($_POST['fefiltro_fecha']) && $_POST['filtro_fecha'] == 'Personalizado') ? 'selected' : ''; ?> >Personalizado</option>
                            </select>
                        </div>
                    </div>

                    <script>
                        document.getElementById('filtro_fecha').addEventListener('change', function() {

                            if (this.value === 'Personalizado') { // Hoy
                                // mostrar campos de fecha personalizada
                                document.getElementById('fecha_desde_01').style.display = 'block';
                                document.getElementById('filtro_fecha_desde').required = true;
                                document.getElementById('filtro_fecha_hasta').required = true;
                                document.getElementById('fecha_hasta_01').style.display = 'block';

                            } else{
                                document.getElementById('fecha_desde_01').style.display = 'none';
                                document.getElementById('filtro_fecha_desde').required = false;
                                document.getElementById('filtro_fecha_hasta').required = false;
                                document.getElementById('fecha_hasta_01').style.display = 'none';
                            }
                        });
                    </script>
                    
                    <div class="col-md-2" id="fecha_desde_01" style="display: none;">
                        <div class="form-group">
                            <label for="filtro_fecha_desde">Fecha Desde</label>
                            <input type="date" class="form-control" id="filtro_fecha_desde" name="fecha_desde" value="<?php echo isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : ''; ?>">
                        </div>
                    </div>

                    <div class="col-md-2" id="fecha_hasta_01" style="display: none;">
                        <div class="form-group">
                            <label for="filtro_fecha_hasta">Fecha Hasta</label>
                            <input type="date" class="form-control" id="filtro_fecha_hasta" name="fecha_hasta" value="<?php echo isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : ''; ?>">
                        </div>
                    </div>

                    <!--  Tipo de reporte -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_reporte">Tipo de reporte:</label>
                            <select name="tipo_reporte" id="tipo_reporte" class="form-control" required>
                                <option value="" selected>Seleccione</option>
                                <option value="Ventas totales" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'Ventas totales') ? 'selected' : ''; ?> >Ventas totales</option>
                                <option value="Compras totales" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'Compras totales') ? 'selected' : ''; ?>
                                >Compras totales</option>
                                <option value="Utilidades totales" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'Utilidades totales') ? 'selected' : ''; ?>
                                >Utilidades totales</option>
                                <option value="Productos Más Vendidos" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'Productos Más Vendidos') ? 'selected' : ''; ?>
                                >Productos Más Vendidos</option>
                                <option value="Produtos Menos Vendidos" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'Produtos Menos Vendidos') ? 'selected' : ''; ?>
                                >Produtos Menos Vendidos</option>
                                <option value="Productos Más comprados" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'Productos Más comprados') ? 'selected' : ''; ?>
                                >Productos Más comprados</option>
                                <option value="Productos Menos Comprados" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'Productos Menos Comprados') ? 'selected' : ''; ?>
                                >Productos Menos Comprados</option>
                                <option value="Ventas por vendedor" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'Ventas por vendedor') ? 'selected' : ''; ?>
                                >Ventas por vendedor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -> Buscar | Reestablecer -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Generar reporte</button>
                        <a href="<?php echo SERVER_URL; ?>reportes/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Reportes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Reportes</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/reporteControlador.php";
                $ins_reporte = new reporteControlador();

                $filtros = [
                    'fecha' => isset($_POST['filtro_fecha']) ? trim($_POST['filtro_fecha']) : '',
                    'tipo_reporte' => isset($_POST['tipo_reporte']) ? trim($_POST['tipo_reporte']) : '',
                    'fecha_desde' => isset($_POST['filtro_fecha_desde']) ? trim($_POST['filtro_fecha_desde']) : '',
                    'fecha_hasta' => isset($_POST['filtro_fecha_hasta']) ? trim($_POST['filtro_fecha_hasta']) : ''
                ];

                echo $ins_reporte->paginar_reporte_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>

</div>
<!-- /.container-fluid -->