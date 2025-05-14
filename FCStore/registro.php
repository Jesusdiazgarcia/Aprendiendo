<?php include('cabecera.php') ?>
<div class="container">
    <div class="registro-container">
      <h1 class="registro-title">Registro de Cliente</h1>

      <form action="procesar_registro.php" method="post" id="registroCliente" name="registroCliente">

        <div class="mb-3">
          <label for="nombre" class="form-label textproducto">Nombre completo:</label>
          <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label textproducto">Correo electrónico:</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
          <label for="telefono" class="form-label textproducto">Teléfono:</label>
          <input type="tel" class="form-control" id="telefono" name="telefono" required>
        </div>

        <div class="mb-3">
          <label for="pais" class="form-label textproducto">País:</label>
          <input type="text" class="form-control" id="pais" name="pais" required>
        </div>

        <div class="mb-3">
          <label for="direccion" class="form-label textproducto">Dirección:</label>
          <input type="text" class="form-control" id="direccion" name="direccion" required>
        </div>

        <div class="mb-3">
          <label for="usuario" class="form-label textproducto">Nombre de usuario:</label>
          <input type="text" class="form-control" id="usuario" name="usuario" required>
        </div>

        <div class="mb-3">
          <label for="clave" class="form-label textproducto">Contraseña:</label>
          <input type="password" class="form-control" id="clave" name="clave" required>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
        </div>

      </form>
    </div>
  </div>
<?php include('pie.php') ?>