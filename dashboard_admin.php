<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: index.php');
    exit();
}
require_once('cabecera.php');
require_once('conexion.php');

// Procesar edición de producto
$mensaje_editar = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_producto'])) {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $precio = floatval($_POST['precio']);
    $codigo = $conn->real_escape_string($_POST['codigo']);
    $disponibilidad = intval($_POST['disponibilidad']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $promocion = isset($_POST['promocion']) ? 1 : 0;
    $set_img = '';
    // Imagen opcional
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $img = $_FILES['imagen'];
        $ext = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
        $mime = mime_content_type($img['tmp_name']);
        if ($ext === 'webp' && $mime === 'image/webp') {
            $img_destino = __DIR__ . "/assets/img/" . $codigo . ".webp";
            move_uploaded_file($img['tmp_name'], $img_destino);
        } else {
            $mensaje_editar = '<div class="alert alert-danger mt-3">La imagen debe ser formato .webp.</div>';
        }
    }
    if (!$mensaje_editar) {
        $query = "UPDATE productos SET nombre='$nombre', categoria='$categoria', precio=$precio, codigo='$codigo', disponibilidad=$disponibilidad, promocion=$promocion, descripcion='$descripcion' WHERE id=$id";
        if ($conn->query($query)) {
            $mensaje_editar = '<div class="alert alert-success mt-3">Producto actualizado correctamente.</div>';
        } else {
            $mensaje_editar = '<div class="alert alert-danger mt-3">Error al actualizar producto: ' . htmlspecialchars($conn->error) . '</div>';
        }
    }
}

// Procesar formulario de nuevo producto
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_producto'])) {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $precio = floatval($_POST['precio']);
    $codigo = $conn->real_escape_string($_POST['codigo']);
    $disponibilidad = intval($_POST['disponibilidad']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $promocion = isset($_POST['promocion']) ? 1 : 0;
    // Validar imagen
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        $mensaje = '<div class="alert alert-danger mt-3">Debes subir una imagen .webp para el producto.</div>';
    } else {
        $img = $_FILES['imagen'];
        $ext = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
        $mime = mime_content_type($img['tmp_name']);
        if ($ext !== 'webp' || $mime !== 'image/webp') {
            $mensaje = '<div class="alert alert-danger mt-3">La imagen debe ser formato .webp.</div>';
        } else {
            $img_destino = __DIR__ . "/assets/img/" . $codigo . ".webp";
            if (move_uploaded_file($img['tmp_name'], $img_destino)) {
                $query = "INSERT INTO productos (nombre, categoria, precio, codigo, disponibilidad, promocion, descripcion) VALUES ('$nombre', '$categoria', $precio, '$codigo', $disponibilidad, $promocion, '$descripcion')";
                if ($conn->query($query)) {
                    $mensaje = '<div class="alert alert-success mt-3">Producto agregado correctamente.</div>';
                } else {
                    if (file_exists($img_destino)) unlink($img_destino);
                    $mensaje = '<div class="alert alert-danger mt-3">Error al agregar producto: ' . htmlspecialchars($conn->error) . '</div>';
                }
            } else {
                $mensaje = '<div class="alert alert-danger mt-3">Error al guardar la imagen en el servidor.</div>';
            }
        }
    }
}

// Obtener productos
$productos = $conn->query("SELECT * FROM productos ORDER BY fecha DESC");
?>
<div class="container py-5">
    <h2 class="fw-bold mb-4 text-center" style="color:#002147;">Dashboard de Administración</h2>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body">
                    <h4 class="mb-3"><i class="fas fa-database me-2"></i>Base de datos de productos</h4>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle" id="tablaProductos">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Código</th>
                                    <th>Stock</th>
                                    <th>Promo</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($p = $productos->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                                    <td><?= htmlspecialchars($p['categoria']) ?></td>
                                    <td>$<?= number_format($p['precio'], 2) ?></td>
                                    <td><?= htmlspecialchars($p['codigo']) ?></td>
                                    <td><?= $p['disponibilidad'] ?></td>
                                    <td><?= $p['promocion'] ? '<span class=\'badge bg-success\'>Sí</span>' : '<span class=\'badge bg-secondary\'>No</span>' ?></td>
                                    <td><?= $p['fecha'] ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarProducto" 
                                            data-id="<?= $p['id'] ?>"
                                            data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>"
                                            data-categoria="<?= htmlspecialchars($p['categoria'], ENT_QUOTES) ?>"
                                            data-precio="<?= $p['precio'] ?>"
                                            data-codigo="<?= htmlspecialchars($p['codigo'], ENT_QUOTES) ?>"
                                            data-stock="<?= $p['disponibilidad'] ?>"
                                            data-descripcion="<?= htmlspecialchars($p['descripcion'], ENT_QUOTES) ?>"
                                            data-promocion="<?= $p['promocion'] ?>"
                                        >
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="mensaje_editar_ajax"><?= $mensaje_editar ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body">
                    <h4 class="mb-3"><i class="fas fa-plus-circle me-2"></i>Agregar nuevo producto</h4>
                    <?= $mensaje ?>
                    <form method="POST" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="nuevo_producto" value="1">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required maxlength="200">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" class="form-control" required>
                                <option value="">Selecciona una categoría</option>
                                <option value="local">Local</option>
                                <option value="visitante">Visitante</option>
                                <option value="alternativa">Alternativa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" name="precio" class="form-control" required min="0" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Código</label>
                            <input type="text" name="codigo" class="form-control" required maxlength="20">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="disponibilidad" class="form-control" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2" maxlength="500"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="promocion" id="promoCheck">
                            <label class="form-check-label" for="promoCheck">¿Producto en promoción?</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Imagen (.webp, obligatoria)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/webp" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold"><i class="fas fa-plus me-2"></i>Agregar producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de edición de producto -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditarProducto" method="POST" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="editar_producto" value="1">
        <input type="hidden" name="id" id="edit_id">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarProductoLabel"><i class="fas fa-edit me-2"></i>Editar producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div id="editarMensajeAjax"></div>
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" id="edit_nombre" class="form-control" required maxlength="200">
          </div>
          <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria" id="edit_categoria" class="form-control" required>
                <option value="">Selecciona una categoría</option>
                <option value="local">Local</option>
                <option value="visitante">Visitante</option>
                <option value="alternativa">Alternativa</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" name="precio" id="edit_precio" class="form-control" required min="0" step="0.01">
          </div>
          <div class="mb-3">
            <label class="form-label">Código</label>
            <input type="text" name="codigo" id="edit_codigo" class="form-control" required maxlength="20">
          </div>
          <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="disponibilidad" id="edit_stock" class="form-control" required min="0">
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2" maxlength="500"></textarea>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="promocion" id="edit_promocion">
            <label class="form-check-label" for="edit_promocion">¿Producto en promoción?</label>
          </div>
          <div class="mb-3">
            <label class="form-label">Imagen (.webp, opcional, reemplaza la actual)</label>
            <input type="file" name="imagen" class="form-control" accept="image/webp">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary fw-bold" id="btnConfirmarEdicion"><i class="fas fa-save me-2"></i>Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal de confirmación -->
<div class="modal fade" id="modalConfirmarGuardar" tabindex="-1" aria-labelledby="modalConfirmarGuardarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalConfirmarGuardarLabel"><i class="fas fa-question-circle me-2"></i>Confirmar edición</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que quieres guardar los cambios en este producto?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btnGuardarAjax">Sí, guardar</button>
      </div>
    </div>
  </div>
</div>
<script>
// Precargar datos en el modal de edición
var modal = document.getElementById('modalEditarProducto');
modal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  document.getElementById('edit_id').value = button.getAttribute('data-id');
  document.getElementById('edit_nombre').value = button.getAttribute('data-nombre');
  document.getElementById('edit_categoria').value = button.getAttribute('data-categoria');
  document.getElementById('edit_precio').value = button.getAttribute('data-precio');
  document.getElementById('edit_codigo').value = button.getAttribute('data-codigo');
  document.getElementById('edit_stock').value = button.getAttribute('data-stock');
  document.getElementById('edit_descripcion').value = button.getAttribute('data-descripcion');
  document.getElementById('edit_promocion').checked = button.getAttribute('data-promocion') == '1';
  document.getElementById('editarMensajeAjax').innerHTML = '';
});

// Mostrar modal de confirmación antes de guardar
const btnConfirmarEdicion = document.getElementById('btnConfirmarEdicion');
const modalConfirmarGuardar = new bootstrap.Modal(document.getElementById('modalConfirmarGuardar'));
btnConfirmarEdicion.addEventListener('click', function() {
  modalConfirmarGuardar.show();
});

// Enviar formulario por AJAX al confirmar
const btnGuardarAjax = document.getElementById('btnGuardarAjax');
btnGuardarAjax.addEventListener('click', function() {
  const form = document.getElementById('formEditarProducto');
  const formData = new FormData(form);
  fetch('dashboard_admin.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(html => {
    // Extraer mensaje de éxito/error y actualizar la fila
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const mensaje = doc.querySelector('#editarMensajeAjax');
    const tabla = doc.querySelector('#tablaProductos tbody');
    if (mensaje) {
      document.getElementById('editarMensajeAjax').innerHTML = mensaje.innerHTML;
    }
    if (tabla) {
      document.querySelector('#tablaProductos tbody').innerHTML = tabla.innerHTML;
    }
    modalConfirmarGuardar.hide();
  })
  .catch(err => {
    document.getElementById('editarMensajeAjax').innerHTML = '<div class="alert alert-danger mt-3">Error al guardar cambios.</div>';
    modalConfirmarGuardar.hide();
  });
});
</script>
<?php require_once('pie.php'); ?> 