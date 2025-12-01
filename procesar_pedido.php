<?php
session_start();
require 'conexion.php'; 

// 1. VALIDACIÓN DE SESIÓN
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario'])) {
    $_SESSION['error'] = "Debes iniciar sesión para realizar una compra.";
    header("Location: login.php");
    exit();
}

// 2. VALIDACIÓN DE DATOS
if (!isset($_SESSION['usuario']) || empty($_SESSION['carrito']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['metodo_pago'])) {
    $_SESSION['error'] = "Faltan datos o el carrito está vacío. (Incluyendo el método de pago)";
    header("Location: finalizar_compra.php");
    exit();
}

// Suponemos que tienes el ID del usuario en sesión
$usuario_id = $_SESSION['usuario_id'] ?? 1; // Usar ID de usuario 1 si no está logueado (solo para desarrollo)
$total_subtotal = 0;
$costo_envio = 15.00; 

// Datos del formulario y método de pago
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$metodo_pago = $_POST['metodo_pago']; // Capturamos el método de pago

// 2. INICIO DE TRANSACCIÓN SQL
$conn->begin_transaction();

try {
    // Calcular subtotal del pedido
    foreach ($_SESSION['carrito'] as $item) {
        $cantidad = (int)$item['cantidad'];
        $precio = (float)$item['precio'];
        $total_subtotal += $precio * $cantidad;
    }
    
    $total_final = $total_subtotal + $costo_envio;

    // 3. INSERTAR PEDIDO
    // El estado inicial es 'Pendiente de Pago' para ambos métodos,
    // ya que la Tarjeta se pagará en la siguiente página y Yape requiere confirmación.
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

    // 4. INSERTAR DETALLE Y ACTUALIZAR STOCK
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

    // 5. SI LA INSERCIÓN FUE BIEN, CONFIRMAR CAMBIOS (Pedido creado, stock reservado)
    $conn->commit();
    
    // CORRECCIÓN: NO VACIAR EL CARRITO AQUÍ.
    // El carrito solo se debe vaciar cuando el pago esté 100% confirmado,
    // lo cual sucede en 'pedido_exito.php'.
    // unset($_SESSION['carrito']); // <--- ESTA LÍNEA SE HA QUITADO

    // 6. REDIRECCIÓN BASADA EN EL MÉTODO DE PAGO
    if ($metodo_pago === 'yape') {
        // Redirige a la página de pago manual con QR de Yape
        echo "<script>window.location.href = 'pago_yape.php?pedido=" . $pedido_id . "';</script>";
        exit();
    } else {
        // Si es 'pasarela' (tarjeta), redirige al formulario de pago real/simulado.
        // El estado del pedido sigue siendo 'Pendiente de Pago' hasta que el pago se complete en la siguiente página.
        echo "<script>window.location.href = 'simular_pago_tarjeta.php?pedido=" . $pedido_id . "';</script>";
        exit();
    }

} catch (Exception $e) {
    // 7. SI ALGO FALLA, DESHACER TODOS LOS CAMBIOS
    $conn->rollback();
    error_log("Error de transacción de pedido: " . $e->getMessage()); 
    $_SESSION['error'] = "⚠️ Ocurrió un error al procesar tu pedido. Por favor, inténtalo de nuevo. Detalle: " . $e->getMessage();
    header("Location: finalizar_compra.php"); 
    exit();
}
?>