<?php
session_start();
require 'conexion.php';

// Validar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para realizar un pago.";
    header("Location: login.php");
    exit();
}

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Método de solicitud inválido.";
    header("Location: finalizar_compra.php");
    exit();
}

// Obtener y validar datos
$pedido_id = isset($_POST['pedido_id']) ? intval($_POST['pedido_id']) : 0;
$numero_tarjeta = $_POST['numero_tarjeta'] ?? '';
$nombre_titular = $_POST['nombre_titular'] ?? '';
$fecha_exp = $_POST['fecha_exp'] ?? '';
$cvv = $_POST['cvv'] ?? '';

if ($pedido_id === 0) {
    $_SESSION['error'] = "ID de pedido inválido.";
    header("Location: finalizar_compra.php");
    exit();
}

// Validar número de tarjeta
if (strlen(str_replace(' ', '', $numero_tarjeta)) !== 16) {
    $_SESSION['error'] = "Número de tarjeta inválido. Debe tener 16 dígitos.";
    header("Location: simular_pago_tarjeta.php?pedido=" . $pedido_id);
    exit();
}

// Validar campos requeridos
if (empty($nombre_titular) || empty($fecha_exp) || empty($cvv)) {
    $_SESSION['error'] = "Todos los campos de la tarjeta son requeridos.";
    header("Location: simular_pago_tarjeta.php?pedido=" . $pedido_id);
    exit();
}

// Validar CVV
if (strlen($cvv) < 3 || strlen($cvv) > 4 || !is_numeric($cvv)) {
    $_SESSION['error'] = "CVV inválido.";
    header("Location: simular_pago_tarjeta.php?pedido=" . $pedido_id);
    exit();
}

// Validar fecha de expiración
if (!preg_match('/^\d{2}\/\d{2}$/', $fecha_exp)) {
    $_SESSION['error'] = "Fecha de expiración inválida (formato: MM/AA).";
    header("Location: simular_pago_tarjeta.php?pedido=" . $pedido_id);
    exit();
}

try {
    // Verificar que el pedido existe y pertenece al usuario
    $stmt_check = $conn->prepare("SELECT id, usuario_id, estado FROM pedidos WHERE id = ?");
    $stmt_check->bind_param("i", $pedido_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows === 0) {
        throw new Exception("El pedido no existe.");
    }
    
    $pedido = $result_check->fetch_assoc();
    
    // Verificar que el pedido pertenece al usuario
    if ($pedido['usuario_id'] != $_SESSION['usuario_id']) {
        throw new Exception("El pedido no pertenece a tu cuenta.");
    }
    
    // Si ya está pagado, no procesar de nuevo
    if ($pedido['estado'] === 'Pagado') {
        $_SESSION['info'] = "Este pedido ya fue pagado.";
        header("Location: pedido_exito.php?pedido=" . $pedido_id . "&metodo=tarjeta");
        exit();
    }
    
    // SIMULACIÓN DE PAGO
    // En una aplicación real, aquí se conectaría con la pasarela de pago
    // Por ahora, aceptamos todos los pagos (simulación)
    
    // Simular procesamiento del pago (aquí podrías agregar lógica de validación)
    sleep(1); // Simular tiempo de procesamiento
    
    // Actualizar estado del pedido a 'Pagado'
    $conn->begin_transaction();
    
    $estado_pagado = 'Pagado';
    $fecha_pago = date('Y-m-d H:i:s');
    $metodo_pago = 'Tarjeta de Crédito';
    
    $stmt_update = $conn->prepare("UPDATE pedidos SET estado = ?, fecha_pago = ?, metodo_pago = ? WHERE id = ?");
    $stmt_update->bind_param("sssi", $estado_pagado, $fecha_pago, $metodo_pago, $pedido_id);
    
    if (!$stmt_update->execute()) {
        throw new Exception("Error al actualizar el estado del pedido.");
    }
    
    $conn->commit();
    
    // Redirigir a página de éxito
    $_SESSION['success'] = "¡Pago procesado exitosamente!";
    header("Location: pedido_exito.php?pedido=" . $pedido_id . "&metodo=tarjeta");
    exit();
    
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Error al procesar el pago: " . $e->getMessage();
    header("Location: simular_pago_tarjeta.php?pedido=" . $pedido_id);
    exit();
}
?>
