<?php

    if ($peticionAjax) {
        require_once "../modelos/negocioModelo.php";
    } else {
        require_once "./modelos/negocioModelo.php";
    }

    class negocioControlador extends negocioModelo {

        //---------- Controlador para agregar negocio -------------//
        public function agregar_negocio_controlador() {
            
            $nombre_negocio = $_POST['nombre_negocio'];
            $contacto = $_POST['contacto'];
            $direccion = $_POST['direccion'];
            $nombre = $_POST['nombre'];
            $contrasena = $_POST['contrasena'];
            $rol = $_POST['rol'];
            $correo = $_POST['correo'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre_negocio == "" || $contacto=="" || $direccion == "" || $contrasena == "" || $rol == "" || $correo == "" || $nombre == "") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No haz llenado todos los campos que son obligatorios",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando el nombre del negocio -------------//
            $check_negocio = mainModel::ejecutar_consulta_simple("SELECT nombre FROM negocio WHERE nombre = '$nombre' LIMIT 1;");
            if ($check_negocio->rowCount() > 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El negocio ingresado ya se encuentra registrado en el sistema",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_negocio = [
                "Nombre_negocio"=>$nombre_negocio,
                "Contacto"=>$contacto,
                "Direccion"=>$direccion
            ];
            $agregar_negocio = negocioModelo::agregar_negocio_modelo($datos_negocio);

            //-------------Tomamos el nit recien guardado------------------//
            $nit_negocio = mainModel::ejecutar_consulta_simple("SELECT nit FROM negocio WHERE nombre = '$nombre_negocio' LIMIT 1;")->fetch(PDO::FETCH_ASSOC)['nit'];
            
            $datos_usuario = [
                "Nombre"=>$nombre,
                "Correo"=>$correo,
                "Contrasena"=>$contrasena,
                "Rol"=>$rol,
                "Nit_negocio"=>$nit_negocio
            ];

            $agregar_usuario = negocioModelo::agregar_usuario_modelo($datos_usuario);

            if ($agregar_negocio->rowCount() == 1 && $agregar_usuario->rowCount() == 1) {
                $alerta = [
                    "Alerta"=>"redireccionar",
                    "Titulo"=>"negocio registrado",
                    "Texto"=>"Los datos del negocio han sido registrados con éxito",
                    "Tipo"=>"success",
                    "URL" => SERVER_URL."login/"
                ];
                echo json_encode($alerta);
                
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos podido registrar al negocio",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
            }
            
        } //fin

        //---------- Controlador para obtener negocio -------------//
        public function obtener_negocio_controlador($id) {
            return negocioModelo::obtener_negocio_modelo($id);
        } // Fin

        //---------- Controlador para actualizar los datos del negocio -------------//
        public function actualizar_negocio_controlador() {

            $id = $_POST['id_actualizar'];

            // Comprobar al negocio en la BD
            $check_negocio = mainModel::ejecutar_consulta_simple("SELECT * FROM negocio WHERE nit = '$id'");
            if ($check_negocio->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado al negocio en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_negocio->fetch();
            }

            $nombre = $_POST['nombre_actualizar'];
            $contacto = $_POST['contacto_actualizar'];
            $direccion = $_POST['direccion_actualizar'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "" || $direccion == "" || $contacto == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Nit" => $id,
                "Nombre" => $nombre,
                "Contacto" => $contacto,
                "Direccion" => $direccion
            ];

            $actualizar_negocio = negocioModelo::actualizar_negocio_modelo($datos);

            if ($actualizar_negocio->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos han sido actualizados con éxito",
                    "Tipo" => "success"
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos",
                    "Tipo" => "error"
                ];
            }
            echo json_encode($alerta);
        } //Fin
    }