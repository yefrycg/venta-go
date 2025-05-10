<?php

    require_once "mainModel.php";

    class productoModelo extends mainModel {

        //---------- Modelo para agregar producto -------------//
        protected static function agregar_producto_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO producto(nit_negocio, id_categoria, nombre, precio_actual, unidad, stock_actual) 
            VALUES (:Nit_negocio,:Id_categoria,:Nombre,:Precio_actual,:Unidad_medida,:Stock_actual);");
            
            $sql->bindParam(":Id_categoria", $datos['Id_categoria']);
            $sql->bindParam(":Nit_negocio", $datos['Nit_negocio']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Precio_actual", $datos['Precio_actual']);
            $sql->bindParam(":Unidad_medida", $datos['Unidad_medida']);
            $sql->bindParam(":Stock_actual", $datos['Stock_actual']);

            $sql->execute();

            return $sql;
        }//FIN agregar_producto_modelo

        //---------- Modelo para eliminar producto -------------//
        protected static function eliminar_producto_modelo($codigo) {

            $sql = mainModel::conectar()->prepare("DELETE FROM producto WHERE id = :Codigo;");

            $sql->bindParam(":Codigo", $codigo);

            $sql->execute();

            return $sql;
        } //FIN eliminar_producto_modelo

        //---------- Modelo para obtener un producto -------------//
        protected static function obtener_producto_modelo($codigo) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM producto WHERE id = :Codigo;");

            $sql->bindParam(":Codigo", $codigo);

            $sql->execute();

            return $sql;
        } //FIN obtener_producto_modelo

        //---------- Modelo para obtener todos los productos -------------//
        protected static function obtener_productos_modelo() {
            $nit_negocio = $_SESSION['nit_negocio'];
            $sql = mainModel::conectar()->prepare("SELECT * FROM producto WHERE nit_negocio = :Nit_negocio;");

            $sql->bindParam(":Nit_negocio", $nit_negocio);
            
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        } //FIN obtener_productos_modelo

        //------------ Modelo para obtener cantidad de productos en BD ----------//
        protected static function obtener_cantidad_productos_modelo() {
            $nit_negocio = $_SESSION['nit_negocio'];
            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM producto WHERE nit_negocio = :Nit_negocio;");
            
            $sql->bindParam(":Nit_negocio", $nit_negocio);

            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        } //FIN obtener_cantidad_productos_modelo

        //------------ Modelo para actualizar producto ----------//
        protected static function actualizar_producto_modelo($datos) {
            $sql = mainModel::conectar()->prepare("UPDATE producto SET
            nombre = :Nombre,
            stock_actual = :Stock,
            precio_actual = :Precio,
            unidad = :Unidad_medida
            WHERE id = :Codigo");
            $sql->bindParam(":Stock", $datos['Stock']);
            $sql->bindParam(":Precio", $datos['Precio']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Unidad_medida", $datos['Unidad_medida']);
            $sql->bindParam(":Codigo", $datos['Codigo']);

            $sql->execute();
            
            return $sql;
        }//FIN actualizar_producto_modelo
    }