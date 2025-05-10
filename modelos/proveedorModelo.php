<?php

    require_once "mainModel.php";

    class proveedorModelo extends mainModel {

        //---------- Modelo para agregar proveedor -------------//
        protected static function agregar_proveedor_modelo($datos) {
            $sql = mainModel::conectar()->prepare("INSERT INTO proveedor(nit_negocio,nombre,contacto,correo) 
            VALUES(:Nit_negocio,:Nombre,:Contacto,:Correo);");

            $sql->bindParam(":Nit_negocio", $datos['Nit_negocio']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contacto", $datos['Contacto']);
            $sql->bindParam(":Correo", $datos['Correo']);
            
            $sql->execute();

            return $sql;
        }

        //---------- Modelo para eliminar proveedor -------------//
        protected static function eliminar_proveedor_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM proveedor WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener proveedor -------------//
        protected static function obtener_proveedor_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM proveedor WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener todos los proveedores -------------//
        protected static function obtener_proveedores_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM proveedor WHERE nit_negocio=:Nit_negocio;");

            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de proveedores ----------//
        protected static function obtener_cantidad_proveedores_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM proveedor Where nit_negocio = :Nit_negocio ;");
            
            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);

            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar proveedor ----------//
        protected static function actualizar_proveedor_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE proveedor SET
            nombre = :Nombre,
            contacto = :Contacto,
            correo = :Correo
            WHERE id = :Id AND nit_negocio = :Nit_negocio");

            $sql->bindParam(":Id", $datos['Id']);
            $sql->bindParam(":Nit_negocio", $datos['Nit_negocio']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contacto", $datos['Contacto']);
            $sql->bindParam(":Correo", $datos['Correo']);

            $sql->execute();
            return $sql;
        }
    }