# VentaGo - Sistema de Punto de Venta

## Descripción
VentaGo es un sistema de punto de venta (POS) basado en la web, diseñado para gestionar ventas, compras, inventario (productos), categorías, proveedores y usuarios para negocios.

## Tecnologías Utilizadas
*   PHP
*   MySQL
*   HTML5
*   CSS3 (probablemente con Bootstrap, inferido por clases como `form-control`, `btn`, `card`, `shadow`, `selectpicker`)
*   JavaScript (ECMAScript, con uso extensivo de AJAX y SweetAlert2 para notificaciones)

## Prerrequisitos
*   Un servidor web local como XAMPP, WAMP o MAMP.
    *   Apache (con `mod_rewrite` habilitado para URLs amigables, debido al uso de `.htaccess`).
    *   PHP (se recomienda versión 7.x o superior).
    *   MySQL o MariaDB.
*   Un navegador web moderno (Chrome, Firefox, Edge, etc.).
*   Acceso a un gestor de bases de datos como phpMyAdmin (incluido en XAMPP).

## Instalación y Configuración

1.  **Clonar o Descargar el Proyecto:**
    *   Si tienes Git, clona el repositorio.
    *   Si no, descarga el código fuente del proyecto como un archivo ZIP y extráelo.
    *   Coloca la carpeta del proyecto (ej. `venta-go`) en el directorio raíz de tu servidor web (ej. `htdocs/` para XAMPP, `www/` para WAMP).

2.  **Configurar la Base de Datos:**
    *   Inicia los módulos Apache y MySQL desde el panel de control de tu servidor web (ej. XAMPP Control Panel).
    *   Abre phpMyAdmin (generalmente accesible en `http://localhost/phpmyadmin`).
    *   Crea una nueva base de datos. Según la configuración del proyecto ([config/SERVER.php](config/SERVER.php)), el nombre es `dbventago`.
        *   Nombre de la base de datos: `dbventago`
        *   Cotejamiento recomendado: `utf8mb4_unicode_ci` o `utf8_general_ci`.
    *   **Crear las tablas:** No se proporciona un archivo `.sql` con la estructura de la base de datos. Deberás crear las tablas y sus columnas manualmente basándote en el código de los modelos (archivos en la carpeta `modelos/`). Las tablas principales parecen ser:
        *   `negocio`
        *   `usuario`
        *   `categoria`
        *   `producto`
        *   `proveedor`
        *   `compra`
        *   `compra_producto` (tabla de detalle para compras)
        *   `venta`
        *   `venta_producto` (tabla de detalle para ventas)
        *   `alerta` (si es una tabla para notificaciones o configuraciones de stock mínimo)

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
    *   Asegúrate de que los módulos Apache y MySQL estén en ejecución desde el panel de control de tu servidor web (ej. XAMPP Control Panel).

2.  **Acceder a la Aplicación:**
    *   Abre tu navegador web y navega a la URL que configuraste en `SERVER_URL` en [config/APP.php](config/APP.php). Por lo general, será:
        `http://localhost/venta-go/` (o la ruta que hayas definido).

3.  **Primeros Pasos:**
    *   La aplicación debería redirigirte a la página de inicio de sesión ([vistas/contenidos/login-view.php](vistas/contenidos/login-view.php)).
    *   Si es la primera vez y no tienes un negocio registrado, utiliza la opción "Regístrate aquí" que te llevará a la página de registro de negocio ([vistas/contenidos/registro-negocio-view.php](vistas/contenidos/registro-negocio-view.php)). Esto creará el negocio y un usuario administrador inicial.

## Estructura del Proyecto
```
.
├── .htaccess           # Reglas de reescritura de URL para Apache (URLs amigables)
├── index.php           # Punto de entrada principal de la aplicación
├── ajax/               # Scripts PHP para manejar peticiones AJAX
│   ├── categoriaAjax.php
│   ├── ...
│   └── ventaAjax.php
├── config/             # Archivos de configuración
│   ├── APP.php         # Configuración general de la aplicación (URL, nombre de la compañía, etc.)
│   └── SERVER.php      # Configuración de la conexión a la base de datos
├── controladores/      # Lógica de negocio, intermediarios entre modelos y vistas
│   ├── alertaControlador.php
│   ├── ...
│   └── vistasControlador.php # Maneja qué vista cargar
├── modelos/            # Clases para interactuar con la base de datos (consultas SQL)
│   ├── alertaModelo.php
│   ├── ...
│   └── vistasModelo.php    # Define las vistas permitidas
└── vistas/             # Capa de presentación (archivos HTML, CSS, JS)
    ├── contenidos/     # Archivos PHP que contienen el HTML específico de cada página/módulo
    ├── css/            # (Directorio estándar para CSS, aunque no se listaron archivos específicos)
    ├── include/        # Fragmentos de HTML reutilizables (ej. logout, scripts, links, sidebar, topbar, footer)
    ├── js/             # Archivos JavaScript personalizados (ej. alertas.js)
    └── plantilla.php   # Plantilla HTML principal que envuelve los contenidos
```

## Notas Adicionales
*   El proyecto utiliza sesiones de PHP para la autenticación y gestión de usuarios (`session_start(['name' => 'VENTAGO']);`).
*   Las alertas y confirmaciones al usuario se manejan con la librería SweetAlert2, como se observa en [vistas/js/alertas.js](vistas/js/alertas.js).
*   Las interacciones dinámicas (como agregar productos a una venta, finalizar compras, búsquedas filtradas) se realizan mediante AJAX, lo que mejora la experiencia del usuario al evitar recargas completas de página.
*   El enrutamiento se gestiona a través de [index.php](index.php) y el [`vistasControlador`](controladores/vistasControlador.php), que interpreta el parámetro `views` de la URL (ej. `http://localhost/venta-go/productos/`).
*   El archivo [.htaccess](.htaccess) es fundamental para que las URLs amigables funcionen. Asegúrate de que `AllowOverride All` esté configurado en la directiva `<Directory>` de Apache para el directorio de tu proyecto y que `mod_rewrite` esté habilitado.
```# VentaGo - Sistema de Punto de Venta
