<?php

    require_once "mainModel.php";

    class negocioModelo extends mainModel {

        //---------- Modelo para agregar negocio -------------//
        protected static function agregar_negocio_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO negocio (nombre, contacto, direccion) 
            VALUES (:Nombre,:Contacto, :Direccion);");

            $sql->bindParam(":Nombre", $datos['Nombre_negocio']);
            $sql->bindParam(":Contacto", $datos['Contacto']);
            $sql->bindParam(":Direccion", $datos['Direccion']);
            
            $sql->execute();

            return $sql;
        } // fin

        //---------- Modelo para agregar usuario -------------//
        protected static function agregar_usuario_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO usuario (nit_negocio, nombre, contrasena, rol, correo) 
            VALUES (:Nit_negocio, :Nombre,:Contrasena,:Rol, :Correo);");

            $sql->bindParam(":Nit_negocio", $datos['Nit_negocio']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contrasena", $datos['Contrasena']);
            $sql->bindParam(":Rol", $datos['Rol']);
            $sql->bindParam(":Correo", $datos['Correo']);
            
            $sql->execute();

            return $sql;
        }

        //---------- Modelo para obtener un negocio -------------//
        protected static function obtener_negocio_modelo($nit) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM negocio WHERE nit = :Nit;");

            $sql->bindParam(":Nit", $nit);

            $sql->execute();

            return $sql;
        } // fin

        //------------ Modelo para actualizar negocio -----------//
        protected static function actualizar_negocio_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE negocio SET
            nombre = :Nombre,
            contacto = :Contacto,
            direccion = :Direccion
            WHERE nit = :Nit");

            $sql->bindParam(":Nit", $datos['nit']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contacto", $datos['Contacto']);
            $sql->bindParam(":Direccion", $datos['Direccion']);

            $sql->execute();
            
            return $sql;
        } // fin revisar comentarios
    }     //OK