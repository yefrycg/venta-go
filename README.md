# VentaGo - Sistema de Punto de Venta

## Descripción
VentaGo es un sistema de punto de venta (POS) basado en la web, diseñado para gestionar ventas, compras, inventario (productos), categorías, proveedores y usuarios para negocios.

## Prerrequisitos
*   Un servidor web local como XAMPP.
    *   Apache (con `mod_rewrite` habilitado para URLs amigables, debido al uso de `.htaccess`).
    *   PHP (se recomienda versión 7.x o superior).
    *   MySQL.
*   Acceso a un gestor de bases de datos como phpMyAdmin (incluido en XAMPP).
*   **Archivo de Base de Datos:** El archivo `dbventago.sql` que contiene la estructura y, opcionalmente, datos iniciales de la base de datos. Este archivo se encuentra en la raíz del proyecto y se descarga junto con el resto del código.

## Instalación y Configuración

1.  **Clonar o Descargar el Proyecto:**
    *   Si tienes Git, clona el repositorio.
    *   Si no, descarga el código fuente del proyecto como un archivo ZIP y extráelo.
    *   Coloca la carpeta del proyecto (`venta-go`) en el directorio raíz de tu servidor web (`htdocs/` para XAMPP).

2.  **Configurar la Base de Datos:**
    *   Inicia los módulos Apache y MySQL desde el panel de control de tu servidor web (XAMPP Control Panel).
    *   Abre phpMyAdmin (accesible en `http://localhost/phpmyadmin`).
    *   Crea una nueva base de datos. Según la configuración del proyecto ([config/SERVER.php](config/SERVER.php)), el nombre es `dbventago`.
        *   Nombre de la base de datos: `dbventago`
    *   **Importar la estructura de la base de datos:**
        *   Selecciona la base de datos recién creada (`dbventago`) en la barra lateral de phpMyAdmin.
        *   Ve a la pestaña "Importar".
        *   Haz clic en "Seleccionar archivo" y busca tu archivo `.sql` (`dbventago.sql`).
        *   Haz clic en el botón "Continuar" (o "Importar") en la parte inferior de la página. Esto creará todas las tablas y, si el archivo `.sql` los incluye, insertará los datos iniciales.

3.  **Configurar Conexión a la Base de Datos:**
    *   Abre el archivo [config/SERVER.php](config/SERVER.php).
    *   Verifica y ajusta las constantes de conexión si es necesario. Los valores por defecto suelen funcionar con una instalación estándar de XAMPP:
        ```php
        // filepath: config/SERVER.php
        <?php

            //configuración de la base de datos
            const SERVER_NAME = "localhost";
            const DB_NAME = "dbventago"; // Asegúrate que coincida con la BD creada
            const USER_NAME = "root";    // Usuario por defecto de MySQL en XAMPP
            const PASSWORD = "";         // Contraseña por defecto de MySQL en XAMPP

            const SGBD = "mysql:host=".SERVER_NAME.";dbname=".DB_NAME;
        ```

4.  **Configurar URL de la Aplicación:**
    *   Abre el archivo [config/APP.php](config/APP.php). Este archivo es crucial para el funcionamiento correcto de las URLs y rutas.
    *   Asegúrate de que la constante `SERVER_URL` esté configurada correctamente para tu entorno local. Si el proyecto está en una subcarpeta `venta-go` dentro de `htdocs`, debería ser:
        ```php
        // filepath: config/APP.php
        <?php
            const SERVER_URL = "http://localhost/venta-go/"; // Ajusta si tu proyecto está en otra ruta
            const COMPANY = "Nombre de tu Empresa"; // Nombre que aparecerá en el título y otros lugares
            // ... otras constantes que pueda tener el archivo ...
        ```

## Ejecución del Proyecto

1.  **Iniciar Servidor Web:**
    *   Asegúrate de que los módulos Apache y MySQL estén en ejecución desde el panel de control de tu servidor web (XAMPP Control Panel).

2.  **Acceder a la Aplicación:**
    *   Abre tu navegador web y navega a la URL que configuraste en `SERVER_URL` en [config/APP.php](config/APP.php). Por lo general, será:
        `http://localhost/venta-go/`.

3.  **Primeros Pasos:**
    *   La aplicación debería redirigirte a la página de inicio de sesión ([vistas/contenidos/login-view.php](vistas/contenidos/login-view.php)).
    *   Si es la primera vez y no tienes un negocio registrado, utiliza la opción "Regístrate aquí" que te llevará a la página de registro de negocio ([vistas/contenidos/registro-negocio-view.php](vistas/contenidos/registro-negocio-view.php)). Esto creará el negocio y un usuario administrador inicial.
