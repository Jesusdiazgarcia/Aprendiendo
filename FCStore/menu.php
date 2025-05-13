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
                            <a class="nav-link navbar-link-custom fs-5" href="#">
                                🛒
                            </a>
                        </li>
                        <li class="nav-item">
                             <a class="nav-link navbar-link-custom" href="registro.php">Registrarse</a>
                        </li>
                        <li class="nav-item">
                             <a class="nav-link navbar-link-custom" href="login.php">Iniciar sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </div> </div>