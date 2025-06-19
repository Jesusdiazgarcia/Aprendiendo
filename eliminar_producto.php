<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

// Verificar si se recibió el ID del registro de compra
if (!isset($_POST['producto_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de compra no proporcionado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$compra_id = (int)$_POST['producto_id']; // Este es realmente el ID del registro de compra

// Verificar que la conexión esté funcionando
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

try {
    // Verificar que el registro de compra existe y pertenece al usuario
    $stmt_check = $conn->prepare("SELECT id FROM compras WHERE id = ? AND cliente = ?");
    $stmt_check->bind_param("ii", $compra_id, $usuario_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado en el carrito']);
        exit;
    }
    
    // Eliminar el registro de compra
    $stmt = $conn->prepare("DELETE FROM compras WHERE id = ? AND cliente = ?");
    $stmt->bind_param("ii", $compra_id, $usuario_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Calcular el nuevo total del carrito usando los campos de la tabla compras
            $stmt_total = $conn->prepare("
                SELECT 
                    SUM(cantidad * precio) as subtotal,
                    COUNT(*) as total_items
                FROM compras
                WHERE cliente = ?
            ");
            $stmt_total->bind_param("i", $usuario_id);
            $stmt_total->execute();
            $resultado = $stmt_total->get_result();
            $datos_carrito = $resultado->fetch_assoc();
            
            $subtotal = $datos_carrito['subtotal'] ?? 0;
            $total_items = $datos_carrito['total_items'] ?? 0;
            
            // Calcular envío, descuento e IVA
            $envio = $subtotal > 50 ? 0 : 5.99;
            $descuento = $subtotal > 100 ? $subtotal * 0.10 : 0;
            $iva = ($subtotal - $descuento) * 0.21;
            $total = $subtotal - $descuento + $iva + $envio;
            
            echo json_encode([
                'success' => true, 
                'message' => 'Producto eliminado del carrito',
                'carrito' => [
                    'subtotal' => number_format($subtotal, 2),
                    'envio' => number_format($envio, 2),
                    'descuento' => number_format($descuento, 2),
                    'iva' => number_format($iva, 2),
                    'total' => number_format($total, 2),
                    'total_items' => $total_items
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el producto']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al ejecutar la eliminación: ' . $conn->error]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
}

$conn->close();
?> 