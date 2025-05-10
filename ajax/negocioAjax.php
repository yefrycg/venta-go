<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['id_actualizar']) || isset($_POST['nombre']) || isset($_POST['nombre_negocio'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/negocioControlador.php";
        $ins_negocio = new negocioControlador();

        //---------- Agregar un negocio ------------//
        if (isset($_POST['nombre_negocio']) && isset($_POST['contacto']) && isset($_POST['direccion']) 
        && isset($_POST['nombre']) && isset($_POST['contrasena']) && isset($_POST['correo']) && isset($_POST['rol'])) {
            echo $ins_negocio->agregar_negocio_controlador();
        }

        //---------- Actualizar un negocio ------------//
        if (isset($_POST['id_actualizar']) && isset($_POST['nombre_actualizar']) && isset($_POST['contacto_actualizar']) && isset($_POST['direccion_actualizar'])) {
            echo $ins_negocio->actualizar_negocio_controlador();
        }
    } else {
        session_start(['name' => 'VENTAGO']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }