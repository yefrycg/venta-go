<?php

    require_once "mainModel.php";

    class compraModelo extends mainModel {
        
        //---------- Modelo para agregar compra -------------//
        protected static function agregar_compra_modelo($datos) {

            $conexion = mainModel::conectar();
            $sql = $conexion->prepare("INSERT INTO compra (id_proveedor, nit_negocio, fecha_hora, total) 
                VALUES (:Id_proveedor, :Nit_negocio, NOW(), :Total)");
            $sql->bindParam(":Id_proveedor", $datos['Id_proveedor']);
            $sql->bindParam(":Nit_negocio", $datos['Nit_negocio']);
            $sql->bindParam(":Total", $datos['Total_compra']);

            if ($sql->execute()) {
                return $conexion->lastInsertId(); // Confirmar que devuelve un ID válido
            }
            return false;
        } // fin de agregar compra


        //---------- Modelo para agregar compra_producto -------------//
        protected static function agregar_compra_producto_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO compra_producto (id_compra, id_producto, cantidad, precio_compra, precio_venta) 
            VALUES (:Id_compra, :Id_producto, :Cantidad, :Precio_compra, :Precio_venta);");

            $sql->bindParam(":Id_compra", $datos['Id_compra']);
            $sql->bindParam(":Id_producto", $datos['Id_producto']);
            $sql->bindParam(":Cantidad", $datos['Cantidad']);
            $sql->bindParam(":Precio_compra", $datos['Precio_compra']);
            $sql->bindParam(":Precio_venta", $datos['Precio_venta']);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para ver detalle de compra -------------//
        protected static function ver_detalle_compra_modelo($id_compra) {
            $sql_compra = mainModel::conectar()->prepare("SELECT c.id, c.fecha_hora, c.total, p.nombre AS nombre_proveedor
                                                        FROM compra c
                                                        INNER JOIN proveedor p ON c.id_proveedor = p.id
                                                        WHERE c.id = :IDCompra");
            $sql_compra->bindParam(":IDCompra", $id_compra);
            $sql_compra->execute();
            $compra_info = $sql_compra->fetch(PDO::FETCH_ASSOC);
        
            if (!$compra_info) {
                return false; // Compra not found
            }
        
            $sql_detalles = mainModel::conectar()->prepare("SELECT cp.id_producto, pr.nombre AS nombre_producto, cp.cantidad, cp.precio_compra
                                                            FROM compra_producto cp
                                                            INNER JOIN producto pr ON cp.id_producto = pr.id
                                                            WHERE cp.id_compra = :IDCompra");
            $sql_detalles->bindParam(":IDCompra", $id_compra);
            $sql_detalles->execute();
            $detalles_info = $sql_detalles->fetchAll(PDO::FETCH_ASSOC);
        
            return [
                'compra' => $compra_info,
                'detalles' => $detalles_info
            ];
        }

        //---------- Modelo para obtener compras -------------//
        protected static function obtener_compras_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM compra WHERE nit_negocio = :Nit_negocio;");

            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de compras en BD ----------//
        protected static function obtener_cantidad_compras_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM compra WHERE nit_negocio = :Nit_negocio;");
    
            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);

            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }
    }