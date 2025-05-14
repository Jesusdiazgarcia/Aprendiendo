<?php include("cabecera.php")  ?>
<?php require_once('conexion.php') ?>
<?php
// Verifica si el parámetro 'id' existe y es un número positivo
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $id_producto = $_GET['id'];

    // Aquí va el código para buscar los detalles del producto en la base de datos
    // Se asume que $conn es una conexión de base de datos válida
    $query = "SELECT * FROM productos WHERE 1 AND id = $id_producto";
    $resource = $conn->query($query);
    $total = $resource->num_rows;
    $row = $resource->fetch_assoc();

    if ($total > 0) {
        // Aquí va el código para mostrar los detalles del producto
        ?>
        <div class="container my-5">
            <div class="row g-5 align-items-center">

                <div class="col-lg-6 text-center position-relative">
                    <?php if ($row['promocion'] == "1") { ?>
                        <span class="position-absolute top-0 start-0 bg-warning text-dark px-3 py-1 rounded-pill fw-semibold shadow"
                              style="margin: 10px;">¡Promoción!</span>
                    <?php } ?>
                    <img src="assets/img/<?= $row['codigo'] ?>.webp" alt="<?= $row['nombre'] ?>"
                         class="img-fluid rounded-4 shadow-lg">
                </div>

                <div class="col-lg-6">
                    <h2 class="fw-bold textproducto mb-3">
                        <?= $row['nombre'] ?> - <span class="textproducto"><?= $row['codigo'] ?></span>
                    </h2>

                    <div class="mb-3">
                        <?php if ($row['promocion'] == "1") { ?>
                            <span class="badge bg-danger fs-5 p-2 me-2">Ahora: $<?= number_format($row['precio']) ?></span>
                            <span class=" text-decoration-line-through">Antes: $<?= number_format($row['precio'] * 1.3) ?></span>
                        <?php } else { ?>
                            <span class="badge bg-primary fs-5 p-2">Precio: $<?= number_format($row['precio']) ?></span>
                        <?php } ?>
                    </div>

    <form id="compra" name="compra" method="post" action="boleta.php">
                        <!-- Visualización de datos -->
                        <p class="textproducto">Categoría: <span><?= $row['categoria'] ?></span></p>
                        <p class="mt-4 textproducto"><?= $row['descripcion'] ?></p>
                        <p class="textproducto">Fecha: <span><?= $row['fecha'] ?></span></p>

                <!-- Selector de talla -->
                <div class="mb-3">
                    <label for="talla" class="form-label fw-semibold textproducto">Talla:</label>
                    <select class="form-select w-50" id="talla" name="talla" required>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </div>

            <!-- Selector de cantidad -->
            <div class="mb-3">
                <label for="product-quantity" class="form-label fw-semibold textproducto">Cantidad:</label>
                <input type="number" class="form-control w-25" id="product-quantity" name="cantidad" value="1" min="1" max="<?= $row['disponibilidad'] ?>" required>
            </div>

            <!-- Botón para enviar el formulario -->
            <div class="d-grid d-md-block mt-4">
                <button type="submit" name="comprar" value="comprar" class="btn btn-primary btn-lg px-5">Añadir al carrito</button>
            </div>

    <!-- Datos ocultos que también se enviarán -->
    <input type="hidden" name="nombre" value="<?= $row['nombre'] ?>">
    <input type="hidden" name="codigo" value="<?= $row['codigo'] ?>">
    <input type="hidden" name="precio" value="<?= $row['precio'] ?>">
    <input type="hidden" name="categoria" value="<?= $row['categoria'] ?>">
    <input type="hidden" name="descripcion" value="<?= $row['descripcion'] ?>">
    <input type="hidden" name="fecha" value="<?= $row['fecha'] ?>">
    <input type="hidden" name="cliente" value="1"> <!-- Esto lo puedes cambiar según el usuario -->
</form>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col">
                    <h4 class="fw-semibold textproducto">Detalles del producto</h4>
                    <ul class="list-unstyled mt-3 text-muted">
                        <li>✅ Material: 100% poliéster reciclado</li>
                        <li>✅ Tecnología Dri-FIT para mayor frescura</li>
                        <li>✅ Producto oficial licenciado</li>
                        <li>🚚 Envío gratis a partir de $50</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    
  } else {
      // Producto no encontrado
      echo "<h1>Error: Producto no encontrado</h1>";
      echo "<p>No se encontró ningún producto con el ID especificado.</p>";
      // Opcionalmente, puedes redirigir al usuario a la página principal:
      // header("Location: index.php");
      exit;
  }
} else {
  // Si el parámetro 'id' no es válido, muestra un mensaje de error amigable
  echo "<h1>Error: Producto no encontrado</h1>";
  echo "<p>No se ha especificado un ID de producto válido.</p>";
  // Opcionalmente, puedes redirigir al usuario a la página principal:
  // header("Location: index.php");
  exit; // Detiene la ejecución del script para evitar errores posteriores
}
?>

<?php include("pie.php") ?>