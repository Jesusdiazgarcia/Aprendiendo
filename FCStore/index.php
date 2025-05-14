<?php include("cabecera.php")  ?>
<?php require_once('conexion.php'); ?>
<?php
$max = 5;
$pag = isset($_GET['pag']) ? (int)$_GET['pag'] : 0;
$inicio = $pag * $max;

$busqueda = isset($_GET['busqueda']) ? $conn->real_escape_string($_GET['busqueda']) : '';
$categoria = isset($_GET['categoria']) ? $conn->real_escape_string($_GET['categoria']) : '';

$query = "SELECT nombre, categoria, precio, codigo, id, disponibilidad FROM productos WHERE 1";

 $params = "";
  if (!empty($busqueda)) {
    $params .= "&busqueda=" . urlencode($busqueda);
  }
  if (!empty($categoria)) {
    $params .= "&categoria=" . urlencode($categoria);
  }
$query .= " ORDER BY fecha DESC LIMIT $inicio, $max";
$resource = $conn->query($query);

// Total para paginación
$total_query = "SELECT COUNT(*) AS total FROM productos WHERE 1";
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
        
        <a href="producto.php?id=<?php  echo $row['id']?>" class="btn btn-primary mt-2">Comprar</a>
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
