<?php
session_start();
require 'conexion.php';

// Establecer el encabezado a JSON desde el principio, para asegurar que no se envíe HTML accidentalmente.
header('Content-Type: application/json');

// Función auxiliar para enviar una respuesta de error JSON y salir.
function sendJsonError($message) {
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit();
}

// 1. VALIDACIÓN DE SESIÓN
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario'])) {
    sendJsonError("Debes iniciar sesión para realizar una compra.");
}

// 2. VALIDACIÓN DE DATOS (Incluye la validación de POST)
if (empty($_SESSION['carrito']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['metodo_pago'])) {
    sendJsonError("Faltan datos (teléfono, dirección, método de pago) o el carrito está vacío.");
}

// Suponemos que tienes el ID del usuario en sesión
$usuario_id = $_SESSION['usuario_id'];
$total_subtotal = 0;
$costo_envio = 15.00; 

// Datos del formulario y método de pago
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$metodo_pago = $_POST['metodo_pago']; // Capturamos el método de pago

// 3. INICIO DE TRANSACCIÓN SQL
$conn->begin_transaction();

try {
    // Calcular subtotal del pedido
    foreach ($_SESSION['carrito'] as $item) {
        $cantidad = (int)$item['cantidad'];
        $precio = (float)$item['precio'];
        $total_subtotal += $precio * $cantidad;
    }
    
    $total_final = $total_subtotal + $costo_envio;

    // 4. INSERTAR PEDIDO
    // El estado inicial es 'Pendiente de Pago' para ambos métodos.
    $estado = 'Pendiente de Pago'; 
    $fecha = date('Y-m-d H:i:s');

    $sql_pedido = "INSERT INTO pedidos (usuario_id, fecha, total, estado, telefono, direccion) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_pedido = $conn->prepare($sql_pedido);
    
    if (!$stmt_pedido) {
        throw new Exception("Error en la consulta SQL para 'pedidos': " . $conn->error);
    }
    
    $stmt_pedido->bind_param("isdsss", $usuario_id, $fecha, $total_final, $estado, $telefono, $direccion);
    
    if (!$stmt_pedido->execute()) {
        throw new Exception("Error al ejecutar la creación del pedido principal.");
    }

    $pedido_id = $conn->insert_id;

    // 5. INSERTAR DETALLE Y ACTUALIZAR STOCK
    $sql_detalle = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    // Asumimos que la tabla productos tiene los campos id, stock
    $sql_stock = "UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?";

    foreach ($_SESSION['carrito'] as $item) {
        $producto_id = (int)$item['id'];
        $cantidad = (int)$item['cantidad'];
        $precio_unitario = (float)$item['precio'];

        // A) Insertar Detalle
        $stmt_det = $conn->prepare($sql_detalle);
        if (!$stmt_det) {
            throw new Exception("Error en la consulta SQL para 'detalle_pedidos'.");
        }
        $stmt_det->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio_unitario);
        
        if (!$stmt_det->execute()) {
            throw new Exception("Error al insertar detalle de producto " . $producto_id);
        }

        // B) Actualizar Stock (Reservar stock)
        $stmt_stock = $conn->prepare($sql_stock);
        if (!$stmt_stock) {
            throw new Exception("Error en la consulta SQL para 'productos' (stock).");
        }
        $stmt_stock->bind_param("iii", $cantidad, $producto_id, $cantidad);
        
        if (!$stmt_stock->execute() || $conn->affected_rows === 0) {
             throw new Exception("Error al actualizar stock de producto " . $producto_id . " o stock insuficiente.");
        }
    }

    // 6. CONFIRMAR CAMBIOS (Pedido creado, stock reservado)
    $conn->commit();
    
    // 7. RESPUESTA JSON DE ÉXITO (INCLUYENDO LA URL DE REDIRECCIÓN)
    if ($metodo_pago === 'yape') {
        $destino = 'pago_yape.php';
    } else {
        $destino = 'simular_pago_tarjeta.php';
    }

    // Enviar respuesta JSON de éxito. JavaScript la leerá y ejecutará la redirección.
    echo json_encode([
        'status' => 'success',
        'redirect_url' => $destino . '?pedido=' . $pedido_id
    ]);
    exit();

} catch (Exception $e) {
    // 8. SI ALGO FALLA, DESHACER TODOS LOS CAMBIOS Y ENVIAR ERROR JSON
    $conn->rollback();
    error_log("Error de transacción de pedido: " . $e->getMessage()); 
    
    // Enviar respuesta JSON de error. JavaScript la capturará y mostrará una alerta.
    sendJsonError("⚠️ Ocurrió un error al procesar tu pedido. Por favor, inténtalo de nuevo. Detalle: " . $e->getMessage());
}
?>