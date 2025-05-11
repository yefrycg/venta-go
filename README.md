# VentaGo - Sistema de Punto de Venta

## Descripción
VentaGo es un sistema de punto de venta (POS) basado en la web, diseñado para gestionar ventas, compras, inventario (productos), categorías, proveedores y usuarios para negocios.

## Tecnologías Utilizadas
*   PHP
*   MariaDB
*   HTML5
*   CSS3 (con Bootstrap)
*   JavaScript (con uso de AJAX y SweetAlert2 para notificaciones)

## Prerrequisitos
*   Un servidor web local como XAMPP.
    *   Apache (con `mod_rewrite` habilitado para URLs amigables, debido al uso de `.htaccess`).
    *   PHP (se recomienda versión 7.x o superior).
    *   MariaDB.
*   Un navegador web moderno (Chrome, Firefox, Edge, etc.).
*   Acceso a un gestor de bases de datos como phpMyAdmin (incluido en XAMPP).
*   **Archivo de Base de Datos:** El archivo `database.sql` (o el nombre que le hayas asignado) que contiene la estructura y, opcionalmente, datos iniciales de la base de datos. Este archivo se encuentra en la raíz del proyecto y se descarga junto con el resto del código.

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
        *   Cotejamiento recomendado: `utf8mb4_unicode_ci` o `utf8_general_ci`.
    *   **Importar la estructura de la base de datos:**
        *   Selecciona la base de datos recién creada (`dbventago`) en la barra lateral de phpMyAdmin.
        *   Ve a la pestaña "Importar".
        *   Haz clic en "Seleccionar archivo" y busca tu archivo `.sql` (ej. `database.sql`).
        *   Asegúrate de que el juego de caracteres del archivo esté configurado correctamente (generalmente `utf-8`).
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
    *   Si el archivo `APP.php` no existe o está incompleto, deberás crearlo o completarlo con al menos estas dos constantes.

## Ejecución del Proyecto

1.  **Iniciar Servidor Web:**
    *   Asegúrate de que los módulos Apache y MySQL estén en ejecución desde el panel de control de tu servidor web (XAMPP Control Panel).

2.  **Acceder a la Aplicación:**
    *   Abre tu navegador web y navega a la URL que configuraste en `SERVER_URL` en [config/APP.php](config/APP.php). Por lo general, será:
        `http://localhost/venta-go/` (o la ruta que hayas definido).

3.  **Primeros Pasos:**
    *   La aplicación debería redirigirte a la página de inicio de sesión ([vistas/contenidos/login-view.php](vistas/contenidos/login-view.php)).
    *   Si es la primera vez y no tienes un negocio registrado, utiliza la opción "Regístrate aquí" que te llevará a la página de registro de negocio ([vistas/contenidos/registro-negocio-view.php](vistas/contenidos/registro-negocio-view.php)). Esto creará el negocio y un usuario administrador inicial.

## Notas Adicionales
*   El proyecto utiliza sesiones de PHP para la autenticación y gestión de usuarios (`session_start(['name' => 'VENTAGO']);`).
*   Las alertas y confirmaciones al usuario se manejan con la librería SweetAlert2, como se observa en [vistas/js/alertas.js](vistas/js/alertas.js).
*   Las interacciones dinámicas (como agregar productos a una venta, finalizar compras, búsquedas filtradas) se realizan mediante AJAX, lo que mejora la experiencia del usuario al evitar recargas completas de página.
*   El enrutamiento se gestiona a través de [index.php](index.php) y el [`vistasControlador`](controladores/vistasControlador.php), que interpreta el parámetro `views` de la URL (ej. `http://localhost/venta-go/productos/`).
*   El archivo [.htaccess](.htaccess) es fundamental para que las URLs amigables funcionen. Asegúrate de que `AllowOverride All` esté configurado en la directiva `<Directory>` de Apache para el directorio de tu proyecto y que `mod_rewrite` esté habilitado.
```# VentaGo - Sistema de Punto de Venta
