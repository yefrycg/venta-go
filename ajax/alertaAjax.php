<?php
    $peticionAjax = true;
    require_once "../config/APP.php"; // Ajusta la ruta si es necesario

    // Verificar qué acción se está solicitando
    if (isset($_POST['alerta_titulo_reg']) && isset($_POST['alerta_mensaje_reg'])) { // Para agregar alerta
        require_once "../controladores/alertaControlador.php";
        $ins_alerta = new alertaControlador();
        echo $ins_alerta->agregar_alerta_controlador();

    } elseif (isset($_POST['id_alerta_upd']) && isset($_POST['estado_alerta_upd'])) { // Para actualizar estado de alerta
        require_once "../controladores/alertaControlador.php";
        $ins_alerta = new alertaControlador();
        echo $ins_alerta->actualizar_estado_alerta_controlador();
        
    } else {
        // Si no es una acción conocida, puedes destruir la sesión o mostrar un error
        session_start(['name' => 'VENTAGO']); // Asegúrate que el nombre de sesión sea el correcto
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/"); // Redirigir al login
        exit();
    }
?>