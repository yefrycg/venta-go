<?php

    class vistasModelo {
        
        //---------- Modelo para obtener las vistas ----------//
        protected static function obtener_vistas_modelo($vistas) {
            $whiteList = ["panel", "compras", "compra-new", "ventas", "venta-new",
                            "categorias", "categoria-new", "categoria-update",
                            "productos", "producto-new", "producto-update",
                            "proveedores", "proveedor-new", "proveedor-update",
                            "usuarios", "usuario-new", "usuario-update"];
            if (in_array($vistas, $whiteList)) {
                if (is_file("./vistas/contenidos/".$vistas."-view.php")) {
                    $contenido = "./vistas/contenidos/".$vistas."-view.php";
                } else {
                    $contenido = "404";
                }
            }elseif($vistas == "registro-negocio"){
                $contenido = "registro-negocio";
            } elseif ($vistas == "login" || $vistas == "index") {
                $contenido = "login";
            }else {
                $contenido = "404";
            }
            return $contenido;
        }
    }