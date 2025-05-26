<!--
<?php
    if ($_SESSION['rol_usuario'] != "Administrador") {
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>
-->

<!-- Alertas -->
<div class="container-fluid">

    <!-- Cabecera de página principal -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Alertas de Stock</h1>
    </div>
                           
    <!-- Tabla de  alertas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Productos con alerta</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/alertaControlador.php";
                $ins_alerta = new alertaControlador();

                echo $ins_alerta->paginar_alerta_controlador2();
            ?>
        </div>
    </div>
</div>