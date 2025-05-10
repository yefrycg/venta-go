<?php

    require_once "mainModel.php";

    class ventaModelo extends mainModel {

        //---------- Modelo para agregar venta -------------//
        protected static function agregar_venta_modelo($datos) {
            
            $conexion = mainModel::conectar();
            $sql = $conexion->prepare("INSERT INTO venta (nit_negocio, id_vendedor, cedula_cliente, nombre_cliente, total) 
            VALUES (:Nit_negocio, :Id_vendedor, :Cedula_cliente, :Nombre_cliente, NOW(), :Total);");

            $sql->bindParam(":Nit_negocio", $datos['Nit_negocio']);
            $sql->bindParam(":Id_vendedor", $datos['Id_vendedor']);
            $sql->bindParam(":Cedula_cliente", $datos['Cedula_cliente']);
            $sql->bindParam(":Nombre_cliente", $datos['Nombre_cliente']);
            $sql->bindParam(":Total", $datos['Total_venta']);

            if ($sql->execute()) {
                return $conexion->lastInsertId(); // Confirmar que devuelve un ID válido
            }
            return false;
        }

        //---------- Modelo para agregar venta_producto -------------//
        protected static function agregar_venta_producto_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO venta_producto (id_venta, id_producto, cantidad, precio) 
            VALUES (:Id_venta, :Id_producto, :Cantidad, :Precio);");

            $sql->bindParam(":Id_venta", $datos['Id_venta']);
            $sql->bindParam(":Id_producto", $datos['Id_producto']);
            $sql->bindParam(":Precio", $datos['Precio']);
            $sql->bindParam(":Cantidad", $datos['Cantidad']);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para ver detalle de venta -------------//
        protected static function ver_detalle_venta_modelo($id_venta) {

            // Queda pendiente poner las columnas que se van a mostrar en la vista
            $sql = mainModel::conectar()->prepare("SELECT * FROM venta 
            INNER JOIN venta_producto ON venta.id = venta_producto.id_venta 
            INNER JOIN producto ON producto.id = venta_producto.id_producto 
            WHERE id = :Id_venta;");

            $sql->bindParam(":Id_venta", $id_venta);

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //---------- Modelo para obtener ventas -------------//
        protected static function obtener_ventas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM venta WHERE nit_negocio = :Nit_negocio;");

            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de ventas en BD ----------//
        protected static function obtener_cantidad_ventas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM venta WHERE nit_negocio = :Nit_negocio;");
            
            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);

            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }
    }