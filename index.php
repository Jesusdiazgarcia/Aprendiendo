<?php 
session_start();
include("cabecera.php")  ?>
<?php require_once('conexion.php'); ?>

<?php
// Mostrar mensaje de logout exitoso
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo '<div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Sesión cerrada!</strong> Has cerrado sesión correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          </div>';
}

// Mostrar mensaje de login exitoso
if (isset($_GET['login']) && $_GET['login'] == 'success') {
    echo '<div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Bienvenido!</strong> Has iniciado sesión correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          </div>';
}
?>

<?php
$max = 5;
$pag = isset($_GET['pag']) ? (int)$_GET['pag'] : 0;
$inicio = $pag * $max;

$busqueda = isset($_GET['busqueda']) ? $conn->real_escape_string($_GET['busqueda']) : '';
$categoria = isset($_GET['categoria']) ? $conn->real_escape_string($_GET['categoria']) : '';

// Construir la consulta con filtros
$query = "SELECT nombre, categoria, precio, codigo, id, disponibilidad FROM productos WHERE 1=1";

// Aplicar filtro de búsqueda
if (!empty($busqueda)) {
    $query .= " AND nombre LIKE '%$busqueda%'";
}

// Aplicar filtro de categoría
if (!empty($categoria)) {
    $query .= " AND categoria = '$categoria'";
}

$query .= " ORDER BY fecha DESC LIMIT $inicio, $max";
$resource = $conn->query($query);

// Construir parámetros para paginación
$params = "";
if (!empty($busqueda)) {
    $params .= "&busqueda=" . urlencode($busqueda);
}
if (!empty($categoria)) {
    $params .= "&categoria=" . urlencode($categoria);
}

// Total para paginación con los mismos filtros
$total_query = "SELECT COUNT(*) AS total FROM productos WHERE 1=1";
if (!empty($busqueda)) {
    $total_query .= " AND nombre LIKE '%$busqueda%'";
}
if (!empty($categoria)) {
    $total_query .= " AND categoria = '$categoria'";
}
$result_total = $conn->query($total_query);
$total = $result_total->fetch_assoc()['total'];
$total_pag = ceil($total / $max) - 1;
?>
<!doctype html>
<div class="container">
   
<!-- Barra de búsqueda y filtro de categorías (solo en index) -->
<div class="container mt-4 mb-3">
  <div class="search-bar w-100">
    <form method="GET" action="index.php" class="d-flex flex-wrap flex-lg-nowrap align-items-center justify-content-center gap-2" style="max-width: 700px; margin: 0 auto;">
      <div class="input-group flex-nowrap" style="flex:1 1 260px; min-width:140px; max-width:340px;">
        <input type="text" class="form-control border-0 shadow-sm" name="busqueda" placeholder="Buscar productos..." value="<?= isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : '' ?>">
        <button type="submit" class="btn btn-warning text-dark fw-semibold shadow-sm px-4">
          <i class="fas fa-search me-1"></i>Buscar
        </button>
      </div>
      <select name="categoria" class="form-select border-0 shadow-sm" style="max-width: 180px; min-width: 120px; flex:0 0 140px;">
        <option value="">Todas las categorías</option>
        <?php
        $categorias = $conn->query("SELECT DISTINCT categoria FROM productos ORDER BY categoria");
        while ($cat = $categorias->fetch_assoc()) {
          $selected = (isset($_GET['categoria']) && $_GET['categoria'] == $cat['categoria']) ? 'selected' : '';
          echo "<option value=\"{$cat['categoria']}\" $selected>{$cat['categoria']}</option>";
        }
        ?>
      </select>
    </form>
  </div>
</div>

<!-- paginacion -->
<div class="pagination-buttons text-center mb-3">
  <?php if ($pag - 1 >= 0) { ?>
    <button class="btn btn-outline-secondary">
      <a href="index.php?pag=<?php echo $pag - 1 ?>&total=<?php echo $total ?><?php echo $params ?>" class="page-link">&larr; Anterior</a>
    </button>
  <?php } ?>

  <button class="btn btn-light" id="page-indicator">
    <a class="page-link">
      <?php echo ($inicio + 1) ?> a <?php echo min($inicio + $max, $total) ?> de <?php echo $total ?>
    </a>
  </button>

  <?php if ($pag + 1 <= $total_pag) { ?>
    <button class="btn btn-outline-secondary">
      <a href="index.php?pag=<?php echo $pag + 1 ?>&total=<?php echo $total ?><?php echo $params ?>" class="page-link">Siguiente &rarr;</a>
    </button>
  <?php } ?>
</div>
  <div class="row g-4 mb-4">
    <!-- Fila 1 -->
    <?php if ($total) {
    $rows = $resource->fetch_all(MYSQLI_ASSOC);
    for ($i = 0; $i < count($rows); $i++) {
        $row = $rows[$i];
?>
    
  <div class="col-md-3 d-flex justify-content-center ">
    <div class=" card position-relative shadow-lg border-0 rounded-4 overflow-hidden" style="width: 18rem;">
      <img src="assets/img/<?= $row['codigo']?>.webp" class="card-img-top p-3" alt="Camiseta FC Barcelona">
      <div class="badge bg-danger text-white position-absolute top-0 end-0 m-2 fs-6 shadow">
        <?= number_format($row['precio'])  ?>
      </div>
      <div class="card-body text-center">
        <h5 class="card-title fw-bold"><?= $row['nombre']?></h5>
        <p>Categoria: <span><?= $row['categoria'] ?></span></p>
        
        <a href="producto.php?id=<?php  echo $row['id']?>" class="btn btn-comprar mt-2">
          <i class="fas fa-cart-plus me-2"></i>Comprar
        </a>
      </div>
    </div>
  </div>
    
<?php 
    }
} else { ?>
    <p class="error">No hay resultados para su consulta</p>
<?php } ?>
</div>



<?php include("pie.php") ?>
