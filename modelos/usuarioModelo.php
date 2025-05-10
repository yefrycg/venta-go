<?php

    require_once "mainModel.php";

    class usuarioModelo extends mainModel {

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
        } // fin
        
        //---------- Modelo para eliminar usuario -------------//
        protected static function eliminar_usuario_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM usuario WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        } // fin

        //---------- Modelo para obtener un usuario -------------//
        protected static function obtener_usuario_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM usuario WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        } // fin _ NO CAMBIÓ

        //---------- Modelo para obtener todos los usuarios -------------//
        protected static function obtener_usuarios_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM usuario WHERE nit_negocio = :Nit_negocio AND rol = 'Vendedor';");

            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);
        
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        } // Fin 

        //------------ Modelo para obtener cantidad de usuarios en BD -----------//
        protected static function obtener_cantidad_usuarios_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM usuario WHERE nit_negocio = :Nit_negocio AND rol = 'Vendedor';");
            
            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);
            
            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        } // fin

        //------------ Modelo para actualizar usuario -----------//
        protected static function actualizar_usuario_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE usuario SET
            nombre = :Nombre,
            contrasena = :Contrasena,
            correo = :Correo
            WHERE id = :Id");

            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Correo", $datos['Correo']);
            $sql->bindParam(":Contrasena", $datos['Contrasena']);
            $sql->bindParam(":Id", $datos['Id']);

            $sql->execute();
            
            return $sql;
        } // fin revisar comentarios
    }     //OK