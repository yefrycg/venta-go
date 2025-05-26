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

        // SELECT producto.nombre,producto.stock_actual FROM `producto` INNER JOIN alerta ON producto.unidad = alerta.unidad WHERE producto.stock_actual < alerta.valor;
        // SELECT COUNT(producto.nombre) AS 'numero de alertas' FROM `producto` INNER JOIN alerta ON producto.unidad = alerta.unidad WHERE producto.stock_actual < alerta.valor;

        //---------- Modelo para obtener todas las alertas -------------//
        protected static function obtener_alertas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM alerta WHERE nit_negocio = :Nit_negocio;");
            
            $sql->bindParam(":Nit_negocio", $_SESSION['nit_negocio']);

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        } // fin

        //---------- Modelo para contar la cantidad de alertas -------------//
        protected static function contar_alertas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(producto.nombre) AS 'numero de alertas' 
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

            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar alerta -----------//
        protected static function actualizar_alerta_modelo($datos) {
            
            $sql = mainModel::conectar()->prepare("UPDATE alerta SET
            valor = :Valor
            WHERE id = :Id");

            $sql->bindParam(":Id", $datos['id']);
            $sql->bindParam(":valor", $datos['valor']);

            $sql->execute();
            
            return $sql;
        } // fin revisar comentarios
    }     //OK