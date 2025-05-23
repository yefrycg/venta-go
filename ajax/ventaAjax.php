<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['cliente_id']) || isset($_POST['id_venta'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/ventaControlador.php";
        $ins_venta = new ventaControlador();

        //---------- Agregar una venta ------------//
        // Agregar una venta
        if (isset($_POST['cliente_id']) && isset($_POST['nombre']) && isset($_POST['vendedor_id']) && isset($_POST['detalles']) && isset($_POST['total'])) {
            $resultado = $ins_venta->finalizar_venta_controlador();
            echo $resultado;
        }

        //---------- Ver detalle de la venta ------------//
        if (isset($_POST['id_venta'])) {
            $id_venta = $_POST['id_venta'];
            $resultado = $ins_venta->ver_detalle_venta_controlador($id_venta);
            echo $resultado;
        }

    } else {
        session_start(['name' => 'VENTAGO']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }