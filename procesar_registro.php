<?php
require_once('conexion.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y limpiar datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $pais = $conn->real_escape_string($_POST['pais']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $clave = $_POST['clave'];
    
    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($telefono) || empty($pais) || 
        empty($direccion) || empty($usuario) || empty($clave)) {
        $error = 'Por favor, completa todos los campos';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El formato del email no es válido';
    } elseif (strlen($clave) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        // Verificar si el usuario ya existe
        $check_user = "SELECT id FROM usuarios WHERE usuario = '$usuario' OR email = '$email'";
        $result_check = $conn->query($check_user);
        
        if ($result_check && $result_check->num_rows > 0) {
            $error = 'El usuario o email ya están registrados';
        } else {
            // Hash de la contraseña
            $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
            
            // Insertar nuevo usuario
            $query = "INSERT INTO usuarios (nombre, email, telefono, pais, direccion, usuario, clave, tipo) 
                      VALUES ('$nombre', '$email', '$telefono', '$pais', '$direccion', '$usuario', '$clave_hash', 'cliente')";
            
            if ($conn->query($query)) {
                $success = '¡Registro exitoso! Ya puedes iniciar sesión.';
                
                // Opcional: Iniciar sesión automáticamente
                $user_id = $conn->insert_id;
                $_SESSION['usuario_id'] = $user_id;
                $_SESSION['usuario_nombre'] = $nombre;
                $_SESSION['usuario_usuario'] = $usuario;
                $_SESSION['usuario_email'] = $email;
                
                // Redirigir después de 2 segundos
                header("refresh:2;url=index.php");
            } else {
                $error = 'Error al registrar usuario: ' . $conn->error;
            }
        }
    }
}
?>

<?php include("cabecera.php") ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 mt-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold textproducto">Resultado del Registro</h2>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <div class="text-center">
                            <a href="registro.php" class="btn btn-primary">Volver al Registro</a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo htmlspecialchars($success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <div class="text-center">
                            <p class="text-muted">Redirigiendo al inicio...</p>
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("pie.php") ?> 