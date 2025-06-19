<?php 
session_start();

// Procesar el formulario ANTES de incluir cabecera.php
if (isset($_POST['comprar']) && $_POST['comprar'] == "comprar") {
    // Verificar si el usuario está logueado
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.php?error=login_required");
        exit();
    }
    
    // Incluir conexión
    require_once('conexion.php');
    
    $cliente_id = $_SESSION['usuario_id'];
    $codigo = $conn->real_escape_string($_POST['codigo']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $precio = $conn->real_escape_string($_POST['precio']);
    $cantidad = $conn->real_escape_string($_POST['cantidad']);
    
    $query = "INSERT INTO compras (cliente, codigo, nombre, precio, cantidad, fecha) 
              VALUES ('$cliente_id', '$codigo', '$nombre', '$precio', '$cantidad', NOW())";
    $conn->query($query);

    if ($conn->error) {
        header("Location: boleta.php?error=insert_failed");
        exit();
    } else {
        // ✅ Redirigir solo si se insertó correctamente
        header("Location: boleta.php?success=added");
        exit();
    }
}

// Ahora incluir la cabecera
include("cabecera.php"); 

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    echo '<div class="container mt-5">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">⚠️ Acceso Requerido</h4>
                <p>Debes <a href="login.php" class="alert-link">iniciar sesión</a> para ver tu carrito de compras.</p>
            </div>
          </div>';
    include("pie.php");
    exit();
}

// Mostrar mensajes de éxito o error
if (isset($_GET['success']) && $_GET['success'] == 'added') {
    echo '<div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Producto agregado!</strong> El producto se ha añadido a tu carrito correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          </div>';
}

if (isset($_GET['error']) && $_GET['error'] == 'insert_failed') {
    echo '<div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error:</strong> No se pudo agregar el producto al carrito.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          </div>';
}

// Obtener solo las compras del usuario logueado
$cliente_id = $_SESSION['usuario_id'];
$query = "SELECT * FROM compras WHERE cliente = '$cliente_id' ORDER BY fecha DESC";
$resource = $conn->query($query); 
$total = $resource->num_rows;
?>
  
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold textproducto mb-4">🛒 Mi Carrito de Compras</h2>
            <p class="text-muted">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        </div>
    </div>
    
    <?php if ($total > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $subtotalGeneral = 0;
                $rows = $resource->fetch_all(MYSQLI_ASSOC);
                for ($i = 0; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    $subtotalProducto = $row['precio'] * $row['cantidad'];
                    $subtotalGeneral += $subtotalProducto;
                ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="assets/img/<?php echo $row['codigo']; ?>.webp" 
                                     alt="<?php echo $row['nombre']; ?>" 
                                     class="me-3" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                <div>
                                    <strong><?php echo htmlspecialchars($row['nombre']); ?></strong>
                                    <br><small class="text-muted">Código: <?php echo $row['codigo']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>$<?php echo number_format($row['precio']); ?></td>
                        <td><?php echo number_format($row['cantidad']); ?></td>
                        <td><strong>$<?php echo number_format($subtotalProducto); ?></strong></td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarProducto(<?php echo $row['id']; ?>)">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Resumen de compra -->
        <div class="row justify-content-end">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📋 Resumen de Compra</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($subtotalGeneral); ?></span>
                        </div>
                        
                        <?php
                        // Determinar costo de envío según subtotal general
                        if ($subtotalGeneral > 50000) {
                            $envio = 0;
                        } elseif ($subtotalGeneral > 25000) {
                            $envio = 2000;
                        } else {
                            $envio = 5000;
                        }
                        ?>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Envío:</span>
                            <span><?php echo $envio == 0 ? 'Gratis' : '$' . number_format($envio); ?></span>
                        </div>
                        
                        <?php
                        // Calcular descuento si aplica
                        $descuento = 0;
                        if ($subtotalGeneral > 50000) {
                            $descuento = $subtotalGeneral * 0.10;
                        }
                        ?>
                        
                        <?php if ($descuento > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Descuento 10%:</span>
                            <span>-$<?php echo number_format($descuento); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php
                        // Calcular subtotal después de envío y descuento
                        $subtotal_con_envio = $subtotalGeneral + $envio;
                        $subtotal_final = $subtotal_con_envio - $descuento;
                        $iva = $subtotal_final * 0.19;
                        $total = $subtotal_final + $iva;
                        ?>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>IVA 19%:</span>
                            <span>$<?php echo number_format($iva); ?></span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <strong>TOTAL:</strong>
                            <strong class="text-primary fs-5">$<?php echo number_format($total); ?></strong>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg" onclick="procesarCompra()">
                                <i class="fas fa-credit-card me-2"></i>Procesar Compra
                            </button>
                            <a href="index.php" class="btn btn-outline-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Seguir Comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
            </div>
            <h3 class="textproducto">Tu carrito está vacío</h3>
            <p class="text-muted">¡Agrega algunos productos para comenzar a comprar!</p>
            <a href="index.php" class="btn btn-primary btn-lg">
                <i class="fas fa-store me-2"></i>Ir a la Tienda
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function eliminarProducto(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
        // Aquí puedes agregar la lógica para eliminar el producto
        alert('Función de eliminación en desarrollo');
    }
}

function procesarCompra() {
    alert('Función de procesamiento de compra en desarrollo');
}
</script>

<?php include("pie.php"); ?>