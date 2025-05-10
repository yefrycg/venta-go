<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de Punto de Venta - VentaGo">
    <meta name="author" content="Yefry Cárdenas, Héctor Arzuaga, Keiven Padilla y Juan Vargas">

    <title><?php echo COMPANY ?></title>

    <!-- Fuentes y estilos -->
    <?php include "./vistas/include/link.php" ?>

</head>

<body id="page-top">

    <?php
        $peticionAjax = false;
        require_once "./controladores/vistasControlador.php";
        $IV = new vistasControlador();

        $vistas = $IV->obtener_vistas_controlador();

        if ($vistas == "login" || $vistas == "404" || $vistas == "registro-negocio") {
            require_once "./vistas/contenidos/".$vistas."-view.php";
        } else {
            session_start(['name' => 'VENTAGO']);

            $pagina = explode("/", $_GET['views']);

            require_once "./controladores/loginControlador.php";
            $lc = new loginControlador();

            if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['nit_negocio']) || !isset($_SESSION['nombre_usuario']) 
            || !isset($_SESSION['contrasena_usuario']) || !isset($_SESSION['rol_usuario']) || !isset($_SESSION['token_sesion'])) {
                echo $lc->forzar_cierre_sesion_controlador();
                exit();
            }
        ?>
        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <?php include "./vistas/include/sideBar.php" ?>

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Contenido principal -->
                <div id="content">

                    <!-- Topbar -->
                    <?php include "./vistas/include/topBar.php" ?>

                    <!-- ventanas -->
                    <?php include $vistas ?>
                </div>

                <!-- Footer -->
                <?php include "./vistas/include/footer.php" ?>

            </div>
        </div>

        <?php
        include "./vistas/include/logOut.php";
        }
        ?>

    <!-- Script -->
    <?php include "./vistas/include/script.php" ?>

</body>
</html>