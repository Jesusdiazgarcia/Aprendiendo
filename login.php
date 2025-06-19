<?php 
session_start();

// Si ya está logueado, redirigir al inicio ANTES de incluir cabecera.php
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$debug_info = '';

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('conexion.php');
    
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $clave = $_POST['clave'];
    
    $debug_info .= "Intentando login para usuario: $usuario<br>";
    
    if (empty($usuario) || empty($clave)) {
        $error = 'Por favor, completa todos los campos';
    } else {
        // Buscar usuario en la base de datos
        $query = "SELECT id, nombre, usuario, clave, email, tipo FROM usuarios WHERE usuario = '$usuario' AND activo = 1";
        $result = $conn->query($query);
        
        $debug_info .= "Query ejecutada: $query<br>";
        $debug_info .= "Resultados encontrados: " . ($result ? $result->num_rows : 'Error en query') . "<br>";
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $debug_info .= "Usuario encontrado: " . $user['nombre'] . "<br>";
            
            // Verificar la contraseña
            if (password_verify($clave, $user['clave'])) {
                $debug_info .= "Contraseña correcta<br>";
                
                // Login exitoso - crear sesión
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nombre'] = $user['nombre'];
                $_SESSION['usuario_usuario'] = $user['usuario'];
                $_SESSION['usuario_email'] = $user['email'];
                $_SESSION['usuario_tipo'] = $user['tipo'];
                
                $debug_info .= "Sesión creada. Redirigiendo...<br>";
                
                // Redirigir al inicio
                header("Location: index.php?login=success");
                exit();
            } else {
                $error = 'Usuario o contraseña incorrectos';
                $debug_info .= "Contraseña incorrecta<br>";
            }
        } else {
            $error = 'Usuario o contraseña incorrectos';
            $debug_info .= "Usuario no encontrado<br>";
        }
    }
}

include("cabecera.php");
require_once('conexion.php');
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 mt-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold textproducto">Iniciar Sesión</h2>
                        <p class="text-muted">Accede a tu cuenta de FCStore</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="usuario" class="form-label fw-semibold textproducto">
                                <i class="fas fa-user me-2"></i>Usuario:
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="usuario" 
                                   name="usuario" 
                                   value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="clave" class="form-label fw-semibold textproducto">
                                <i class="fas fa-lock me-2"></i>Contraseña:
                            </label>
                            <input type="password" 
                                   class="form-control form-control-lg" 
                                   id="clave" 
                                   name="clave" 
                                   required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0 text-muted">
                            ¿No tienes cuenta? 
                            <a href="registro.php" class="text-decoration-none fw-semibold">Regístrate aquí</a>
                        </p>
                    </div>

                    <!-- Información de prueba -->
                    <div class="mt-4 p-3 bg-light rounded-3">
                        <h6 class="fw-semibold textproducto mb-2">👥 Usuarios de Prueba:</h6>
                        <small class="text-muted">
                            <strong>Usuario:</strong> admin<br>
                            <strong>Contraseña:</strong> password<br><br>
                            <strong>Usuario:</strong> juanperez<br>
                            <strong>Contraseña:</strong> password
                        </small>
                    </div>

                    <!-- Debug info (solo mostrar si hay debug) -->
                    <?php if (!empty($debug_info)): ?>
                        <div class="mt-4 p-3 bg-warning rounded-3">
                            <h6 class="fw-semibold textproducto mb-2">🐛 Debug Info:</h6>
                            <small class="text-muted">
                                <?php echo $debug_info; ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("pie.php") ?> 