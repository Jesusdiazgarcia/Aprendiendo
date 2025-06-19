<?php 
session_start();
include('cabecera.php') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                <div class="card-body p-5">
                    <h2 class="registro-title fw-bold text-center mb-4" style="color:#002147; letter-spacing:1px;">Crea tu cuenta</h2>
                    <p class="text-center text-secondary mb-4">¡Regístrate para comprar camisetas y acceder a ofertas exclusivas!</p>
                    <form action="procesar_registro.php" method="post" id="registroCliente" name="registroCliente" autocomplete="off">
                        <div class="mb-3">
                            <label for="nombre" class="form-label textproducto">Nombre completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="60" placeholder="Ej: Lionel Messi">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label textproducto">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required maxlength="60" placeholder="Ej: usuario@email.com">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label textproducto">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required maxlength="20" placeholder="Ej: +56 9 1234 5678">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="pais" class="form-label textproducto">País</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-globe"></i></span>
                                <input type="text" class="form-control" id="pais" name="pais" required maxlength="40" placeholder="Ej: Chile">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label textproducto">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control" id="direccion" name="direccion" required maxlength="80" placeholder="Ej: Calle Fútbol 10, Santiago">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="usuario" class="form-label textproducto">Nombre de usuario</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                                <input type="text" class="form-control" id="usuario" name="usuario" required maxlength="30" placeholder="Ej: messifan10">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="clave" class="form-label textproducto">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="clave" name="clave" required minlength="6" maxlength="32" placeholder="Mínimo 6 caracteres">
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold shadow-sm"><i class="fas fa-user-plus me-2"></i>Registrarse</button>
                        </div>
                        <p class="text-center text-muted mt-3 mb-0" style="font-size:0.98rem;">¿Ya tienes cuenta? <a href="login.php" class="fw-semibold text-decoration-none" style="color:#002147;">Inicia sesión aquí</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<?php include('pie.php') ?>