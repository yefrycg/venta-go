<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['nombre']) || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/proveedorControlador.php";
        $ins_proveedor = new proveedorControlador();

        //---------- Agregar un proveedor ------------//
        if (isset($_POST['nombre']) && isset($_POST['contacto']) && isset($_POST['correo'])) {
            echo $ins_proveedor->agregar_proveedor_controlador();
        }

        //---------- Eliminar un proveedor ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_proveedor->eliminar_proveedor_controlador();
        }

        //---------- Actualizar un proveedor ------------//
        if (isset($_POST['id_actualizar']) && isset($_POST['nombre_actualizar']) && isset($_POST['contacto_actualizar']) && isset($_POST['correo_actualizar'])) {
            echo $ins_proveedor->actualizar_proveedor_controlador();
        }
    } else {
        session_start(['name' => 'VENTAGO']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }