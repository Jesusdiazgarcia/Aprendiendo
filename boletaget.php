<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FCstore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
          
<body>
<?php
// Primero obtener los valores de GET y asignarlos a variables
$precio = isset($_GET['precio']) ? $_GET['precio'] : 0;
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : 0;
$subtotal = $precio * $cantidad;

// Determinar costo de envío según subtotal
if($subtotal > 50000){
    $envio = 0;
} elseif($subtotal > 25000){
    $envio = 2000;
} else {
    $envio = 5000;
}

// Calcular descuento si aplica
$descuento = 0;
if($subtotal > 50000) {
    $descuento = $subtotal * 0.10;
}

// Calcular subtotal después de envío y descuento
$subtotal_con_envio = $subtotal + $envio;
$subtotal_final = $subtotal_con_envio - $descuento;

// Calcular IVA y total
$iva = $subtotal_final * 0.19;
$total = $subtotal_final + $iva;
?>
    <header class="container my-4">
        <h1>Tienda Virtual<br>
        <small class="text-muted">Rayitas S.A. </small></h1>
    </header>
    <div class="container">
        <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Rayas Verticales</td>
                    <td><?php echo number_format($precio); ?></td>
                    <td><?php echo number_format($cantidad); ?></td>
                    <td><?php echo number_format($subtotal); ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Envío</td>
                    <td><?php echo number_format($envio); ?></td>
                </tr>
                
                <?php if($subtotal > 50000): ?>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Descuento 10%</td>
                    <td><?php echo number_format($descuento); ?></td>
                </tr>
                <?php endif; ?>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Subtotal</td>
                    <td><?php echo number_format($subtotal_final); ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>IVA 19%</td>
                    <td><?php echo number_format($iva); ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>TOTAL</td>
                    <td><?php echo number_format($total); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
  <div class="container">
     <?php for ($i=1; $i < 1000; $i++) {
    echo  "debo hacer más preguntas en clases o me ponen a hacer tareas ridículas" ;}
      ?>
  </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>