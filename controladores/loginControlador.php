<?php

    if ($peticionAjax) {
        require_once "../modelos/loginModelo.php";
    } else {
        require_once "./modelos/loginModelo.php";
    }

    class loginControlador extends loginModelo {
        
        //------------- Controlador para iniciar sesión -------------//
        public function iniciar_sesion_controlador() {

            $nombre = $_POST['nombre'];
            $contrasena = $_POST['contrasena'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "" || $contrasena == "") {
                echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "No has llenado todos los campos que son requeridos",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>';
                exit();
            }

            $datos_login = [
                "Nombre" => $nombre,
                "Contrasena" => $contrasena
            ];

            $datos_cuenta = loginModelo::iniciar_sesion_modelo($datos_login);

            if ($datos_cuenta->rowCount() == 1) {
                $row = $datos_cuenta->fetch();

                session_start(['name' => 'VENTAGO']);
                $_SESSION['id_usuario'] = $row['id'];
                $_SESSION['nit_negocio'] = $row['nit_negocio'];
                $_SESSION['nombre_usuario'] = $row['nombre'];
                $_SESSION['contrasena_usuario'] = $row['contrasena'];
                $_SESSION['rol_usuario'] = $row['rol'];
                $_SESSION['correo'] = $row['correo'];
                $_SESSION['token_sesion'] = md5(uniqid(mt_rand(), true));

                return header("Location: " . SERVER_URL . "panel/");
            } else {
                echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "El usuario y/o contraseña son incorrectos",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>';
            }
        }

        //------------- Controlador para forzar el cierre de sesión -------------//
        public function forzar_cierre_sesion_controlador() {

            session_unset();
            session_destroy();
            
            if (headers_sent()) {
                return "<script> window.location.href='".SERVER_URL."login/'; </script>";
            } else {
                return header("Location: " . SERVER_URL . "login/");
            }
        }

        //------------- Controlador para cierre de sesión -------------//
        public function cerrar_sesion_controlador() {
            
            session_start(['name' => 'VENTAGO']);

            $token =$_POST['token'];
            $nombre =$_POST['nombre'];

            if ($token == $_SESSION['token_sesion'] && $nombre == $_SESSION['nombre_usuario']) {
                session_unset();
                session_destroy();

                $alerta = [
                    "Alerta"=>'redireccionar',
                    "URL"=>SERVER_URL."login/"
                ];
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se pudo cerrar la sesión en el sistema",
                    "Tipo"=>"error",
                ];
            }
            echo json_encode($alerta);
        }
    }