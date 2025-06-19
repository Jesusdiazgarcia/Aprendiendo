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
    
    $usuario_id = $_SESSION['usuario_id'];
    $producto_id = (int)$_POST['producto_id'];
    $cantidad = (int)$_POST['cantidad'];
    $talla = $conn->real_escape_string($_POST['talla']);
    
    // Obtener información del producto
    $stmt_producto = $conn->prepare("SELECT nombre, precio, codigo, disponibilidad FROM productos WHERE id = ?");
    $stmt_producto->bind_param("i", $producto_id);
    $stmt_producto->execute();
    $result_producto = $stmt_producto->get_result();
    $producto = $result_producto->fetch_assoc();
    
    if (!$producto) {
        header("Location: boleta.php?error=product_not_found");
        exit();
    }
    
    // Verificar stock disponible
    if ($producto['disponibilidad'] < $cantidad) {
        header("Location: boleta.php?error=insufficient_stock");
        exit();
    }
    
    // Verificar si el producto ya está en el carrito
    $stmt_check = $conn->prepare("SELECT id, cantidad FROM compras WHERE cliente = ? AND producto_id = ?");
    $stmt_check->bind_param("ii", $usuario_id, $producto_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        // Actualizar cantidad existente
        $item_carrito = $result_check->fetch_assoc();
        $nueva_cantidad = $item_carrito['cantidad'] + $cantidad;
        
        if ($producto['disponibilidad'] < $nueva_cantidad) {
            header("Location: boleta.php?error=insufficient_stock");
            exit();
        }
        
        $stmt_update = $conn->prepare("UPDATE compras SET cantidad = ?, talla = ? WHERE id = ?");
        $stmt_update->bind_param("isi", $nueva_cantidad, $talla, $item_carrito['id']);
        $stmt_update->execute();
    } else {
        // Insertar nuevo producto - usar los campos exactos de tu tabla
        $stmt_insert = $conn->prepare("INSERT INTO compras (cliente, producto_id, codigo, nombre, precio, cantidad, talla, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt_insert->bind_param("iissdis", $usuario_id, $producto_id, $producto['codigo'], $producto['nombre'], $producto['precio'], $cantidad, $talla);
        $stmt_insert->execute();
    }

    if ($conn->error) {
        header("Location: boleta.php?error=insert_failed");
        exit();
    } else {
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

if (isset($_GET['error'])) {
    $error_message = '';
    switch ($_GET['error']) {
        case 'insert_failed':
            $error_message = 'No se pudo agregar el producto al carrito.';
            break;
        case 'insufficient_stock':
            $error_message = 'No hay suficiente stock disponible para este producto.';
            break;
        case 'product_not_found':
            $error_message = 'Producto no encontrado.';
            break;
        default:
            $error_message = 'Ha ocurrido un error inesperado.';
    }
    
    echo '<div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error:</strong> ' . $error_message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          </div>';
}

// Obtener productos del carrito del usuario logueado
$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT c.* 
          FROM compras c 
          WHERE c.cliente = ? 
          ORDER BY c.fecha DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$total = $result->num_rows;
?>

<!-- Hero Section del Carrito -->
<div class="cart-hero-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="cart-title">🛒 Mi Carrito de Compras</h1>
                <p class="cart-subtitle">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <?php if ($total > 0): ?>
        <div class="row">
            <!-- Sección de Productos -->
            <div class="col-lg-8">
                <div class="cart-products-section">
                    <h3 class="section-title">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Productos en tu carrito
                    </h3>
                    
                    <?php
                    $subtotalGeneral = 0;
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                    foreach ($rows as $row) {
                        $subtotalProducto = $row['precio'] * $row['cantidad'];
                        $subtotalGeneral += $subtotalProducto;
                    ?>
                        <div class="cart-product-card">
                            <div class="row align-items-center">
                                <!-- Imagen del producto -->
                                <div class="col-3 col-md-2">
                                    <div class="product-image">
                                        <img src="assets/img/<?php echo $row['codigo']; ?>.webp" 
                                             alt="<?php echo htmlspecialchars($row['nombre']); ?>" 
                                             class="product-img">
                                    </div>
                                </div>
                                
                                <!-- Detalles del producto -->
                                <div class="col-6 col-md-4">
                                    <div class="product-details">
                                        <h6 class="product-name"><?php echo htmlspecialchars($row['nombre']); ?></h6>
                                        <p class="product-code">Código: <?php echo $row['codigo']; ?></p>
                                        <div class="product-price">
                                            <span class="price-label">Precio unitario</span>
                                            <span class="price-value">$<?php echo number_format($row['precio']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Cantidad y talla -->
                                <div class="col-3 col-md-2">
                                    <div class="quantity-badge">
                                        <i class="fas fa-layer-group"></i>
                                        <?php echo $row['cantidad']; ?>
                                    </div>
                                    <div class="product-size d-none d-md-block mt-2">
                                        <span class="size-label">Talla</span>
                                        <span class="size-value"><?php echo $row['talla']; ?></span>
                                    </div>
                                </div>
                                
                                <!-- Talla en móvil -->
                                <div class="col-3 d-md-none">
                                    <div class="product-size-mobile">
                                        <span class="size-label">Talla</span>
                                        <span class="size-value"><?php echo $row['talla']; ?></span>
                                    </div>
                                </div>
                                
                                <!-- Total del producto -->
                                <div class="col-3 col-md-2">
                                    <div class="product-total">
                                        <span class="total-label">Total</span>
                                        <span class="total-value">$<?php echo number_format($subtotalProducto); ?></span>
                                    </div>
                                </div>
                                
                                <!-- Acciones -->
                                <div class="col-3 col-md-2">
                                    <div class="product-actions">
                                        <button class="btn btn-danger" 
                                                onclick="confirmarEliminacion(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['nombre']); ?>')"
                                                title="Eliminar producto">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Sección de Resumen -->
            <div class="col-lg-4">
                <div class="cart-summary-section">
                    <div class="summary-card">
                        <div class="summary-header">
                            <h4 class="summary-title">
                                <i class="fas fa-calculator me-2"></i>
                                Resumen de Compra
                            </h4>
                        </div>
                        <div class="summary-body">
                            <?php
                            // Calcular costos
                            $envio = $subtotalGeneral > 50 ? 0 : 5.99;
                            $descuento = $subtotalGeneral > 100 ? $subtotalGeneral * 0.10 : 0;
                            $iva = ($subtotalGeneral - $descuento) * 0.21;
                            $total = $subtotalGeneral - $descuento + $iva + $envio;
                            ?>
                            
                            <div class="summary-item">
                                <span>Subtotal</span>
                                <span class="summary-value">$<?php echo number_format($subtotalGeneral, 2); ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span>Envío</span>
                                <span class="summary-value"><?php echo $envio == 0 ? 'Gratis' : '$' . number_format($envio, 2); ?></span>
                            </div>
                            
                            <?php if ($descuento > 0): ?>
                            <div class="summary-item text-success">
                                <span>Descuento 10%</span>
                                <span class="summary-value">-$<?php echo number_format($descuento, 2); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="summary-item">
                                <span>IVA 21%</span>
                                <span class="summary-value">$<?php echo number_format($iva, 2); ?></span>
                            </div>
                            
                            <div class="summary-divider"></div>
                            
                            <div class="summary-total">
                                <span class="total-label">Total Final</span>
                                <span class="total-amount">$<?php echo number_format($total, 2); ?></span>
                            </div>
                            
                            <div class="summary-actions">
                                <button class="btn btn-success w-100 mb-2" onclick="procesarCompra()">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Procesar Compra
                                </button>
                                <a href="index.php" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Seguir Comprando
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Carrito Vacío -->
        <div class="empty-cart-section">
            <div class="empty-cart-content">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="empty-cart-title">Tu carrito está vacío</h3>
                <p class="empty-cart-text">No tienes productos en tu carrito de compras. ¡Explora nuestra tienda y encuentra las mejores camisetas de fútbol!</p>
                <a href="index.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-store me-2"></i>
                    Ir a la Tienda
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres eliminar <strong id="productoNombre"></strong> de tu carrito?</p>
                <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="confirmarEliminacion">
                    <i class="fas fa-trash me-2"></i>
                    Sí, Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Resultado -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="resultModalHeader">
                <h5 class="modal-title" id="resultModalLabel">
                    <i class="fas fa-info-circle me-2"></i>
                    Resultado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="resultModalBody">
                <!-- El contenido se llenará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>
                    Entendido
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let productoAEliminar = null;

function confirmarEliminacion(productoId, nombreProducto) {
    productoAEliminar = productoId;
    document.getElementById('productoNombre').textContent = nombreProducto;
    
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
}

document.getElementById('confirmarEliminacion').addEventListener('click', function() {
    if (productoAEliminar) {
        eliminarProducto(productoAEliminar);
    }
});

function eliminarProducto(productoId) {
    // Mostrar loading
    const confirmBtn = document.getElementById('confirmarEliminacion');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Eliminando...';
    confirmBtn.disabled = true;
    
    // Realizar petición AJAX
    fetch('eliminar_producto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'producto_id=' + productoId
    })
    .then(response => response.json())
    .then(data => {
        // Cerrar modal de confirmación
        const confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
        confirmModal.hide();
        
        // Mostrar resultado
        mostrarResultado(data);
        
        if (data.success) {
            // Recargar la página después de un breve delay
            setTimeout(() => {
                location.reload();
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarResultado({
            success: false,
            message: 'Error de conexión. Inténtalo de nuevo.'
        });
    })
    .finally(() => {
        // Restaurar botón
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
    });
}

function mostrarResultado(data) {
    const modal = new bootstrap.Modal(document.getElementById('resultModal'));
    const header = document.getElementById('resultModalHeader');
    const title = document.getElementById('resultModalLabel');
    const body = document.getElementById('resultModalBody');
    
    if (data.success) {
        header.className = 'modal-header bg-success text-white';
        title.innerHTML = '<i class="fas fa-check-circle me-2"></i>Éxito';
        body.innerHTML = `
            <div class="alert alert-success">
                <h6 class="alert-heading">¡Producto eliminado!</h6>
                <p class="mb-0">${data.message}</p>
            </div>
        `;
    } else {
        header.className = 'modal-header bg-danger text-white';
        title.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Error';
        body.innerHTML = `
            <div class="alert alert-danger">
                <h6 class="alert-heading">Error al eliminar</h6>
                <p class="mb-0">${data.message}</p>
            </div>
        `;
    }
    
    modal.show();
}

function procesarCompra() {
    alert('Funcionalidad de procesamiento de compra en desarrollo...');
}
</script>

<?php include("pie.php"); ?>