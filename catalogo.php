<?php
session_start();
require_once('cabecera.php');
require_once('conexion.php');

// Obtener todas las camisetas disponibles
$query = "SELECT nombre, categoria, precio, codigo, id, disponibilidad FROM productos WHERE disponibilidad > 0 ORDER BY fecha DESC";
$result = $conn->query($query);
$camisetas = $result->fetch_all(MYSQLI_ASSOC);
$total = count($camisetas);

?>
<div class="container mt-4 mb-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color:#002147; letter-spacing:1px;">Catálogo completo de camisetas</h2>
        <p class="fs-5 text-secondary mb-1">Descubre todas las camisetas de fútbol disponibles en nuestra tienda</p>
        <span class="badge bg-warning text-dark fs-6 px-3 py-2 mb-2 shadow-sm">Total disponibles: <b><?= $total ?></b></span>
        <hr class="my-3" style="border-top:2px solid #ffd700; width: 60px; margin:auto;">
    </div>
    <div class="row g-4 mb-4" id="catalogo-lista">
        <!-- Las camisetas se insertarán aquí -->
    </div>
    <div class="text-center mb-3">
        <button id="verMasBtn" class="btn btn-warning fw-bold px-4 py-2 shadow-sm animate__animated animate__pulse animate__infinite" style="border-radius: 2rem; font-size: 1.08rem;">Ver más</button>
        <div id="finMensaje" class="mt-3 text-success fw-semibold" style="display:none;">
            <i class="fas fa-check-circle me-2"></i>¡Has visto todas las camisetas disponibles!
        </div>
    </div>
    <div id="sinCamisetas" class="alert alert-info text-center mt-4" style="display:none;">
        <i class="fas fa-info-circle me-2"></i>No hay camisetas disponibles en este momento. ¡Vuelve pronto!
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
const camisetas = <?php echo json_encode($camisetas); ?>;
const total = camisetas.length;
const porPagina = 4;
let mostradas = 0;

function renderCamisetas() {
    const lista = document.getElementById('catalogo-lista');
    for (let i = mostradas; i < Math.min(mostradas + porPagina, total); i++) {
        const c = camisetas[i];
        const col = document.createElement('div');
        col.className = 'col-md-3 d-flex justify-content-center';
        col.innerHTML = `
            <div class=\"card position-relative shadow-lg border-0 rounded-4 overflow-hidden\" style=\"width: 18rem;\">
                <img src=\"assets/img/${c.codigo}.webp\" class=\"card-img-top p-3\" alt=\"Camiseta ${c.nombre}\">
                <div class=\"badge bg-danger text-white position-absolute top-0 end-0 m-2 fs-6 shadow\">
                    ${Number(c.precio).toLocaleString()}
                </div>
                <div class=\"card-body text-center\">
                    <h5 class=\"card-title fw-bold\">${c.nombre}</h5>
                    <p class=\"mb-1\"><span class=\"badge bg-primary bg-opacity-75\">${c.categoria}</span></p>
                    <a href=\"producto.php?id=${c.id}\" class=\"btn btn-comprar mt-2\">
                        <i class=\"fas fa-cart-plus me-2\"></i>Comprar
                    </a>
                </div>
            </div>
        `;
        lista.appendChild(col);
    }
    mostradas += porPagina;
    if (mostradas >= total) {
        document.getElementById('verMasBtn').style.display = 'none';
        document.getElementById('finMensaje').style.display = 'block';
    }
}

document.getElementById('verMasBtn').addEventListener('click', renderCamisetas);
window.onload = function() {
    if (total === 0) {
        document.getElementById('verMasBtn').style.display = 'none';
        document.getElementById('sinCamisetas').style.display = 'block';
    } else {
        renderCamisetas();
    }
};
</script>
<?php require_once('pie.php'); ?> 