<?php 
session_start();
include("cabecera.php");
require_once('conexion.php');

// Verifica si el parámetro 'id' existe y es un número positivo
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $id_producto = $_GET['id'];

    // Buscar los detalles del producto en la base de datos
    $query = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->num_rows;
    $row = $result->fetch_assoc();

    if ($total > 0) {
        ?>
        <!-- Hero Section del Producto -->
        <div class="product-hero-section">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Productos</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($row['nombre']) ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="container">
            <div class="row g-5">
                <!-- Galería de Producto -->
                <div class="col-lg-6">
                    <div class="product-gallery">
                        <div class="main-image-container">
                            <?php if ($row['promocion'] == "1") { ?>
                                <div class="promo-badge">
                                    <i class="fas fa-fire me-1"></i>
                                    ¡Promoción!
                                </div>
                            <?php } ?>
                            <img src="assets/img/<?= $row['codigo'] ?>.webp" 
                                 alt="<?= htmlspecialchars($row['nombre']) ?>"
                                 class="main-product-image"
                                 id="mainImage">
                        </div>
                        
                        <!-- Miniaturas -->
                        <div class="product-thumbnails mt-3">
                            <?php
                            // Verificar si existen imágenes adicionales
                            $imagen_2 = "assets/img/" . $row['codigo'] . "_2.webp";
                            $imagen_3 = "assets/img/" . $row['codigo'] . "_3.webp";
                            $tiene_imagenes_adicionales = file_exists($imagen_2) || file_exists($imagen_3);
                            
                            // Solo mostrar miniaturas si hay imágenes adicionales
                            if ($tiene_imagenes_adicionales): ?>
                                <div class="thumbnail active" onclick="cambiarImagen('assets/img/<?= $row['codigo'] ?>.webp', this)">
                                    <img src="assets/img/<?= $row['codigo'] ?>.webp" alt="Vista 1">
                                </div>
                                
                                <?php if (file_exists($imagen_2)): ?>
                                    <div class="thumbnail" onclick="cambiarImagen('<?= $imagen_2 ?>', this)">
                                        <img src="<?= $imagen_2 ?>" alt="Vista 2">
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (file_exists($imagen_3)): ?>
                                    <div class="thumbnail" onclick="cambiarImagen('<?= $imagen_3 ?>', this)">
                                        <img src="<?= $imagen_3 ?>" alt="Vista 3">
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Información del Producto -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <div class="product-header">
                            <h1 class="product-title"><?= htmlspecialchars($row['nombre']) ?></h1>
                            <p class="product-code">Código: <?= $row['codigo'] ?></p>
                            <span class="category-badge">
                                <i class="fas fa-tag me-1"></i>
                                <?= htmlspecialchars($row['categoria']) ?>
                            </span>
                        </div>

                        <!-- Información de Stock -->
                        <div class="stock-info">
                            <?php if ($row['disponibilidad'] > 0): ?>
                                <div class="stock-badge in-stock">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span class="stock-text">En stock - <?= $row['disponibilidad'] ?> unidades disponibles</span>
                                </div>
                            <?php else: ?>
                                <div class="stock-badge out-of-stock">
                                    <i class="fas fa-times-circle me-2"></i>
                                    <span class="stock-text">Agotado</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Sección de Precio -->
                        <div class="product-price-section">
                            <div class="price-container">
                                <div class="current-price">
                                    <span class="price-amount">$<?= number_format($row['precio']) ?></span>
                                    <span class="price-label">Precio actual</span>
                                </div>
                                
                                <?php if ($row['promocion'] == "1"): ?>
                                    <div class="original-price">
                                        <span class="price-amount">$<?= number_format($row['precio'] * 1.3) ?></span>
                                        <span class="price-label">Precio original</span>
                                    </div>
                                    <div class="discount-badge">
                                        <i class="fas fa-percentage me-1"></i>
                                        30% OFF
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Descripción del Producto -->
                        <div class="product-description">
                            <h4 class="description-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Descripción
                            </h4>
                            <p class="description-text"><?= htmlspecialchars($row['descripcion']) ?></p>
                        </div>

                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <?php if ($row['disponibilidad'] > 0): ?>
                                <!-- Formulario de Compra -->
                                <div class="purchase-form">
                                    <form id="compra" name="compra" method="post" action="boleta.php">
                                        <div class="form-section">
                                            <h5 class="form-section-title">
                                                <i class="fas fa-cog me-2"></i>
                                                Opciones de Compra
                                            </h5>
                                            
                                            <div class="row">
                                                <!-- Selector de talla -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="talla" class="form-label fw-semibold">
                                                        <i class="fas fa-ruler me-1"></i>
                                                        Talla:
                                                    </label>
                                                    <select class="form-select custom-select" id="talla" name="talla" required>
                                                        <option value="">Selecciona una talla</option>
                                                        <option value="s">S - Pequeña</option>
                                                        <option value="m" selected>M - Mediana</option>
                                                        <option value="l">L - Grande</option>
                                                        <option value="xl">XL - Extra Grande</option>
                                                    </select>
                                                </div>

                                                <!-- Selector de cantidad -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="cantidad" class="form-label fw-semibold">
                                                        <i class="fas fa-hashtag me-1"></i>
                                                        Cantidad:
                                                    </label>
                                                    <div class="quantity-selector">
                                                        <button type="button" class="quantity-btn" onclick="cambiarCantidad(-1)">-</button>
                                                        <input type="number" class="quantity-input" id="cantidad" name="cantidad" value="1" min="1" max="<?= $row['disponibilidad'] ?>" required>
                                                        <button type="button" class="quantity-btn" onclick="cambiarCantidad(1)">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="purchase-actions">
                                            <button type="submit" name="comprar" value="comprar" class="btn purchase-btn">
                                                <i class="fas fa-cart-plus me-2"></i>
                                                Añadir al Carrito
                                            </button>
                                            
                                            <div class="purchase-info">
                                                <div class="info-item">
                                                    <i class="fas fa-shipping-fast text-primary"></i>
                                                    <span>Envío gratis en compras superiores a $50</span>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-shield-alt text-success"></i>
                                                    <span>Garantía de devolución de 30 días</span>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-credit-card text-info"></i>
                                                    <span>Pago seguro con tarjeta o PayPal</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Datos ocultos -->
                                        <input type="hidden" name="producto_id" value="<?= $row['id'] ?>">
                                    </form>
                                </div>
                            <?php else: ?>
                                <!-- Producto Agotado -->
                                <div class="out-of-stock-section">
                                    <div class="alert alert-warning" role="alert">
                                        <div class="alert-content">
                                            <i class="fas fa-exclamation-triangle alert-icon"></i>
                                            <div>
                                                <h5 class="alert-heading">Producto Agotado</h5>
                                                <p class="mb-0">Lo sentimos, este producto no está disponible en este momento. Te notificaremos cuando vuelva a estar en stock.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Sección de Login Requerido -->
                            <div class="login-required-section">
                                <div class="alert alert-info" role="alert">
                                    <div class="alert-content">
                                        <i class="fas fa-user-lock alert-icon"></i>
                                        <div>
                                            <h5 class="alert-heading">Acceso Requerido</h5>
                                            <p class="mb-0">Para agregar productos al carrito, debes iniciar sesión o crear una cuenta.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="login-actions">
                                    <a href="login.php" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Iniciar Sesión
                                    </a>
                                    <a href="registro.php" class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Registrarse
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Características del Producto -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="product-features">
                        <div class="features-header">
                            <h3 class="features-title">
                                <i class="fas fa-star me-2"></i>
                                Características del Producto
                            </h3>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-leaf"></i>
                                    </div>
                                    <h5>Material Ecológico</h5>
                                    <p>100% poliéster reciclado para un futuro más sostenible</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-wind"></i>
                                    </div>
                                    <h5>Tecnología Dri-FIT</h5>
                                    <p>Absorbe la humedad y mantiene la frescura durante el ejercicio</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <h5>Producto Oficial</h5>
                                    <p>Licenciado oficialmente por el club, garantía de autenticidad</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <h5>Envío Gratis</h5>
                                    <p>Envío gratuito en compras superiores a $50</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function cambiarImagen(src, elemento) {
            document.getElementById('mainImage').src = src;
            
            // Remover clase active de todas las miniaturas
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Agregar clase active a la miniatura clickeada
            elemento.classList.add('active');
        }

        function cambiarCantidad(delta) {
            const input = document.getElementById('cantidad');
            const nuevaCantidad = parseInt(input.value) + delta;
            const max = parseInt(input.max);
            
            if (nuevaCantidad >= 1 && nuevaCantidad <= max) {
                input.value = nuevaCantidad;
            }
        }
        </script>
        <?php
    } else {
        // Producto no encontrado
        ?>
        <div class="error-section">
            <div class="error-content">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2 class="error-title">Producto no encontrado</h2>
                <p class="error-message">Lo sentimos, no se encontró ningún producto con el ID especificado.</p>
                <a href="index.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-home me-2"></i>
                    Volver al Inicio
                </a>
            </div>
        </div>
        <?php
    }
} else {
    // ID de producto inválido
    ?>
    <div class="error-section">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="error-title">ID de producto inválido</h2>
            <p class="error-message">No se ha especificado un ID de producto válido.</p>
            <a href="index.php" class="btn btn-primary btn-lg">
                <i class="fas fa-home me-2"></i>
                Volver al Inicio
            </a>
        </div>
    </div>
    <?php
}
?>

<?php include("pie.php") ?>