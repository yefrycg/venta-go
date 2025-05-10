<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    // id_eliminar se debe cambiar a detalles a su debido tiempo
    if (isset($_POST['id_proveedor']) || isset($_POST['id_compra'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/compraControlador.php";
        $ins_compra = new compraControlador();

        //---------- Agregar una compra ------------//
        if (isset($_POST['id_proveedor']) && isset($_POST['detalles']) && isset($_POST['total'])) {
            $resultado = $ins_compra->finalizar_compra_controlador();
            echo $resultado;
        }

        //---------- Ver detalle de una compra ------------//
        if (isset($_POST['id_compra'])) {
            $id_compra = $_POST['id_compra'];
            $resultado = $ins_compra->ver_detalle_compra_controlador($id_compra);
            echo $resultado;
        }

    } else {
        session_start(['name' => 'VENTAGO']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }