<?php include("cabecera.php"); ?>
<?php if (isset($_POST['comprar']) && $_POST['comprar'] == "comprar") {
    $query = "INSERT INTO compras (id,cliente,codigo,nombre, precio,cantidad,fecha) 
              VALUES (NULL,'$_POST[cliente]','$_POST[codigo]','$_POST[nombre]','$_POST[precio]', '$_POST[cantidad]',NOW())";
    $conn->query($query);

    if ($conn->error) {
        echo "Error al insertar: " . $conn->error;
    } else {
        // ✅ Redirigir solo si se insertó correctamente
        header("Location: boleta.php");
        exit();
    }
}?>

      <?php
      $query=" SELECT * FROM compras ORDER BY fecha DESC";
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



$subtotalGeneral = 0; // Inicializamos el subtotal general

 if ($total) { $rows = $resource->fetch_all(MYSQLI_ASSOC);
    for ($i = 0; $i < count($rows); $i++) {
        $row = $rows[$i];
        $subtotalProducto = $row['precio'] * $row['cantidad'] ;
         $subtotalGeneral += $subtotalProducto;

?>
    <tr>
        <td>Rayas <?php echo $row['nombre'] ?></td>
        <td><?php echo number_format($row['precio']); ?></td>
        <td><?php echo number_format($row['cantidad']); ?></td>
        <td><?php echo number_format($subtotalProducto); ?></td>
    </tr>
<?php     }
} else { ?>
    <p class="error">No hay resultados para su consa</p>
<?php } ?>
 

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