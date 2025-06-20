<?php
// Asegurar que la sesión esté disponible
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div style="background-color: #002147;" class="py-3">
    <div class="container d-flex align-items-center justify-content-between flex-wrap">
        <h1 class="mb-0 me-3">
            <span class="brillante">FCSTORE</span>
        </h1>
        <nav class="navbar p-0 m-0 w-auto" style="background-color: transparent !important;">
            <ul class="navbar-nav flex-row flex-wrap flex-lg-row align-items-center ms-auto gap-2 gap-lg-3 mb-0" style="flex-direction: row !important;">
                <li class="nav-item">
                    <a class="navbar-brand text-white navram1 fw-bold" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbar-link-custom" href="boleta.php">
                        <i class="fas fa-basket-shopping"></i>
                    </a>
                </li>
                <!-- Botón Ver Catálogo -->
                <li class="nav-item">
                    <a href="catalogo.php" class="btn btn-warning fw-bold px-4 py-2 ms-2 shadow-sm" style="border-radius: 2rem; font-size: 1.08rem;">
                        <i class="fas fa-tshirt me-2"></i>Ver catálogo
                    </a>
                </li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <!-- Usuario logueado -->
                    <li class="nav-item dropdown">
                        <a class="nav-link navbar-link-custom" href="#" id="userDropdown">
                            <?php if(isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo']==='admin'): ?>
                                <span class="badge bg-danger me-1"><i class="fas fa-user-shield"></i> Admin</span>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> ▼
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu" style="display: none;">
                            <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                            <li><a class="dropdown-item" href="mis_compras.php"><i class="fas fa-shopping-bag me-2"></i>Mis Compras</a></li>
                            <?php if(isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo']==='admin'): ?>
                                <li><a class="dropdown-item" href="dashboard_admin.php"><i class="fas fa-tools me-2"></i>Dashboard Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Usuario no logueado -->
                    <li class="nav-item">
                         <a class="nav-link navbar-link-custom" href="registro.php">Registrarse</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link navbar-link-custom" href="login.php">Iniciar sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>