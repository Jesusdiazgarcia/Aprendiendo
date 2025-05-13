<?php include("cabecera.php"); ?>
<?php if(isset($_POST['comprar']) && $_POST['comprar']=="comprar"){
      $query="INSERT INTO compras (id,cliente,codigo,nombre, precio,cantidad,fecha) VALUES (NULL,'$_POST[cliente]','$_POST[codigo]','$_POST[nombre]','$_POST[precio]', '$_POST[cantidad]',NOW())";
      $conn->query($query); 
      $ID=$conn->insert_id;
      }?>

      <?php
      $query=" SELECT * FROM compras WHERE 1 AND cliente='1' ORDER BY fecha DESC";
      $resource = $conn->query($query); 
      $total = $resource->num_rows;
      ?>
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
<?php

$rayitas = array("horizontales" => ["cantidad" => 16, "precio" => 1000],
                 "verticales" => ["cantidad" => 7, "precio" => 1200],
                 "curvas" => ["cantidad" => 13, "precio" => 2500]);

$subtotalGeneral = 0; // Inicializamos el subtotal general

foreach ($rayitas as $nombre => $detalles):
    $precio = $detalles["precio"];
    $cantidad = $detalles["cantidad"];
    $subtotalProducto = $precio * $cantidad;
    $subtotalGeneral += $subtotalProducto; // Sumamos al subtotal general
?>
    <tr>
        <td>Rayas <?php echo $nombre ?></td>
        <td><?php echo number_format($precio); ?></td>
        <td><?php echo number_format($cantidad); ?></td>
        <td><?php echo number_format($subtotalProducto); ?></td>
    </tr>
<?php endforeach; ?>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>Subtotal</td>
        <td><?php echo number_format($subtotalGeneral); ?></td>
    </tr>
    <?php

    // Determinar costo de envío según subtotal general
    if ($subtotalGeneral > 50000) {
        $envio = 0;
    } elseif ($subtotalGeneral > 25000) {
        $envio = 2000;
    } else {
        $envio = 5000;
    }

    // Calcular descuento si aplica
    $descuento = 0;
    if ($subtotalGeneral > 50000) {
        $descuento = $subtotalGeneral * 0.10;
    }

    // Calcular subtotal después de envío y descuento
    $subtotal_con_envio = $subtotalGeneral + $envio;
    $subtotal_final = $subtotal_con_envio - $descuento;

    // Calcular IVA y total
    $iva = $subtotal_final * 0.19;
    $total = $subtotal_final + $iva;
    ?>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>Envío</td>
        <td><?php echo number_format($envio); ?></td>
    </tr>
    <?php if ($subtotalGeneral > 50000): ?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="red2">Descuento 10%</td>
            <td class="red2"><?php echo number_format($descuento); ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>Subtotal Final</td>
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

<?php include("pie.php"); ?>