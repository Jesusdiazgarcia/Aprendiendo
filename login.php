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
        <div class="col-md-6 col-lg-5">
            <div class="login-container">
                <div class="login-card">
                    <div class="login-header">
                        <div class="login-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h2 class="login-title">Bienvenido de vuelta</h2>
                        <p class="login-subtitle">Accede a tu cuenta de FCStore</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show login-alert" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login.php" class="login-form">
                        <div class="form-group">
                            <label for="usuario" class="form-label">
                                <i class="fas fa-user"></i>
                                <span>Usuario</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="text" 
                                       class="form-control" 
                                       id="usuario" 
                                       name="usuario" 
                                       placeholder="Ingresa tu usuario"
                                       value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>"
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="clave" class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>Contraseña</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="password" 
                                       class="form-control" 
                                       id="clave" 
                                       name="clave" 
                                       placeholder="Ingresa tu contraseña"
                                       required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-login">
                                <span class="btn-text">Iniciar Sesión</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>

                    <div class="login-footer">
                        <p class="register-text">
                            ¿No tienes cuenta? 
                            <a href="registro.php" class="register-link">Regístrate aquí</a>
                        </p>
                    </div>

                    <!-- Debug info (solo mostrar si hay debug) -->
                    <?php if (!empty($debug_info)): ?>
                        <div class="debug-info">
                            <h6>🐛 Debug Info:</h6>
                            <small>
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