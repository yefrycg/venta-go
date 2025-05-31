<?php

    require_once "mainModel.php";

    class alertaModelo extends mainModel {

        //---------- Modelo para agregar alerta -------------//
        protected static function agregar_alerta_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO alerta (nit_negocio, unidad, valor)
            VALUES (:Nit_negocio,:Unidad, :Valor);");

            $sql->bindParam(":Nit_negocio", $datos['Nit_negocio']);
            $sql->bindParam(":Unidad", $datos['Unidad']);
            $sql->bindParam(":Valor", $datos['Valor']);
            
            $sql->execute();

            return $sql;
        } // fin

        //---------- Modelo para obtener un alerta -------------//
        protected static function obtener_alerta_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM alerta WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        } // fin

        protected static function obtener_alertas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM alerta WHERE nit_negocio = :Nit_negocio;");
            
            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        } // fin

        protected static function contar_alertas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(producto.id) AS Cantidad 
            FROM `producto` INNER JOIN alerta ON producto.unidad = alerta.unidad 
            WHERE producto.stock_actual < alerta.valor AND alerta.nit_negocio = :Nit_negocio;");

            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);
            
            $sql->execute();

            return $sql->fetch(PDO::FETCH_OBJ);
        } // fin

        //----------- obtener alertas productos -----------//
        protected static function obtener_alertas_productos_modelo() {
            $sql = mainModel::conectar()->prepare("SELECT producto.id AS id, producto.nombre AS nombre 
            FROM `producto` INNER JOIN alerta ON producto.unidad = alerta.unidad 
            WHERE producto.stock_actual < alerta.valor AND alerta.nit_negocio = :Nit_negocio;");

            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);
            
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar alerta -----------//
        protected static function actualizar_alerta_modelo($datos) {
            
            $sql = mainModel::conectar()->prepare("UPDATE alerta SET
            valor = :Valor
            WHERE id = :Id");

            $sql->bindParam(":Id", $datos['Id']);
            $sql->bindParam(":Valor", $datos['Valor']);

            $sql->execute();
            
            return $sql;
        } // fin revisar comentarios

        //---------- Modelo para eliminar alerta -------------//
        protected static function eliminar_alerta_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM alerta WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        } // fin
    }