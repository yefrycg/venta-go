<?php
    $peticionAjax = true;
    require_once "../config/APP.php"; // Ajusta la ruta si es necesario

    // Verificar qué acción se está solicitando
    if(isset($_POST['unidad']) || isset($_POST['valor_minimo']) || isset($_POST['estado_alerta_upd']) || 
    isset($_POST['id_eliminar']) || isset($_POST['id_actualizar'])) {
    require_once "../controladores/alertaControlador.php";
    $ins_alerta = new alertaControlador();

    if (isset($_POST['unidad']) && isset($_POST['valor_minimo'])) { // Para agregar alerta
        echo $ins_alerta->agregar_alerta_controlador();

    } elseif (isset($_POST['id_actualizar']) && isset($_POST['valor_actualizar']) && isset($_POST['unidad_actualizar'])) {
        echo $ins_alerta->actualizar_estado_alerta_controlador();

    } elseif (isset($_POST['id_eliminar'])) { // Para eliminar alerta
        echo $ins_alerta->eliminar_alerta_controlador();

    }
    } else {
        session_start(['name' => 'VENTAGO']); // Asegúrate que el nombre de sesión sea el correcto
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/"); // Redirigir al login
        exit();
    }

?>