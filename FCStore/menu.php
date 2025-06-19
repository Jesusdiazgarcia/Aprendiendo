<?php
// Asegurar que la sesión esté disponible
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div style="background-color: #002147;" class="py-3"> <div class="container d-flex align-items-center justify-content-between">
        <h1 class=" mb-0 me-3"> <span class="brillante">BSHOP</span>
            <img src="assets/img/barcelona.png" alt="FC Barcelona" style="height: 60px; vertical-align: middle;">
        </h1>

        <nav class="navbar navbar-expand-lg flex-grow-1" style="background-color: transparent !important;">
             <div class="container-fluid">
                <a class="navbar-brand text-white navram1 fw-bold" href="index.php">Inicio</a>
                <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <form method="GET" action="index.php" class="row g-2 align-items-center mb-4">
  <!-- Campo de búsqueda -->
  <div class="col-md-6">
    <div class="input-group">
      <input type="text" class="form-control border-0 shadow-sm" name="busqueda" placeholder="Buscar productos..." value="<?= isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : '' ?>">
    </div>
  </div>

  <!-- Dropdown de categorías -->
  <div class="col-md-4">
    <select name="categoria" class="form-select border-0 shadow-sm">
      <option value="">Todas las categorías</option>
      <?php
      $categorias = $conn->query("SELECT DISTINCT categoria FROM productos ORDER BY categoria");
      while ($cat = $categorias->fetch_assoc()) {
        $selected = (isset($_GET['categoria']) && $_GET['categoria'] == $cat['categoria']) ? 'selected' : '';
        echo "<option value=\"{$cat['categoria']}\" $selected>{$cat['categoria']}</option>";
      }
      ?>
    </select>
  </div>

  <!-- Botón de búsqueda -->
  <div class="col-md-2">
    <button type="submit" class="btn btn-warning text-dark fw-semibold w-100 shadow-sm">Buscar</button>
  </div>
</form>
                    
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item me-3">
                            <a class="nav-link navbar-link-custom fs-5" href="boleta.php">
                                🛒
                            </a>
                        </li>
                        
                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <!-- Usuario logueado -->
                            <li class="nav-item dropdown me-2">
                                <a class="nav-link navbar-link-custom" href="#" id="userDropdown" onclick="toggleUserMenu(event)">
                                    👤 <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> ▼
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu" style="display: none;">
                                    <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                                    <li><a class="dropdown-item" href="mis_compras.php"><i class="fas fa-shopping-bag me-2"></i>Mis Compras</a></li>
                                    <li><hr class="dropdown-divider"></li>
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
                </div>
            </div>
        </nav>

    </div> </div>

<!-- Script manual para el dropdown del usuario -->
<script>
function toggleUserMenu(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const dropdownMenu = document.getElementById('userDropdownMenu');
    const userDropdown = document.getElementById('userDropdown');
    
    console.log('Toggle dropdown clicked');
    
    if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
        // Mostrar dropdown
        dropdownMenu.style.display = 'block';
        dropdownMenu.style.visibility = 'visible';
        dropdownMenu.style.opacity = '1';
        userDropdown.innerHTML = userDropdown.innerHTML.replace('▼', '▲');
        console.log('Dropdown mostrado');
    } else {
        // Ocultar dropdown
        dropdownMenu.style.display = 'none';
        dropdownMenu.style.visibility = 'hidden';
        dropdownMenu.style.opacity = '0';
        userDropdown.innerHTML = userDropdown.innerHTML.replace('▲', '▼');
        console.log('Dropdown ocultado');
    }
}

// Cerrar dropdown al hacer clic fuera
document.addEventListener('click', function(event) {
    const dropdownMenu = document.getElementById('userDropdownMenu');
    const userDropdown = document.getElementById('userDropdown');
    
    if (dropdownMenu && userDropdown) {
        if (!userDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = 'none';
            dropdownMenu.style.visibility = 'hidden';
            dropdownMenu.style.opacity = '0';
            userDropdown.innerHTML = userDropdown.innerHTML.replace('▲', '▼');
            console.log('Dropdown cerrado por clic fuera');
        }
    }
});

// Cerrar dropdown con tecla Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const dropdownMenu = document.getElementById('userDropdownMenu');
        const userDropdown = document.getElementById('userDropdown');
        
        if (dropdownMenu && userDropdown) {
            dropdownMenu.style.display = 'none';
            dropdownMenu.style.visibility = 'hidden';
            dropdownMenu.style.opacity = '0';
            userDropdown.innerHTML = userDropdown.innerHTML.replace('▲', '▼');
            console.log('Dropdown cerrado con Escape');
        }
    }
});

console.log('Script de dropdown cargado');
</script>