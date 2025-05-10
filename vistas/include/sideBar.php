
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= SERVER_URL; ?>panel/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?= COMPANY;?></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Panel -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= SERVER_URL; ?>panel/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Panel</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Compras Collapse Menu -->
    <?php if ($_SESSION['rol_usuario'] == "Administrador") { ?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-truck-loading"></i>
            <span>Compras</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>compra-new/">Añadir Compra</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>compras/">Listado de Compras</a>
            </div>
        </div>
    </li>
    <?php } ?>

    <!-- Nav Item - Ventas Collapse Menu
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Ventas</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>venta-new/">Añadir Venta</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>ventas/">Listado de Ventas</a>
            </div>
        </div>
    </li>-->

    <?php if ($_SESSION['rol_usuario'] == "Administrador") { ?>
    <!-- Nav Item - Categorias collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-tags"></i>
            <span>Categorías</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>categoria-new/">Añadir Categoría</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>categorias/">Listado de Categorías</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Productos collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProductos"
            aria-expanded="true" aria-controls="collapseProductos">
            <i class="fas fa-fw fa-box"></i>
            <span>Productos</span>
        </a>
        <div id="collapseProductos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>producto-new/">Añadir Producto</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>productos/">Listado de Productos</a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Proveedores collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProveedores"
            aria-expanded="true" aria-controls="collapseProveedores">
            <i class="fas fa-fw fa-truck"></i>
            <span>Proveedores</span>
        </a>
        <div id="collapseProveedores" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>proveedor-new/">Añadir Proveedor</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>proveedores/">Listado de Proveedores</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Usuarios collapse Menu-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsuarios"
            aria-expanded="true" aria-controls="collapseUsuarios">
            <i class="fas fa-fw fa-users"></i>
            <span>Usuarios</span>
        </a>
        <div id="collapseUsuarios" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?= SERVER_URL; ?>usuario-new/">Añadir Usuario</a>
                <a class="collapse-item" href="<?= SERVER_URL; ?>usuarios/">Listado de Usuarios</a>
            </div>
        </div>
    </li>
    <?php } ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>