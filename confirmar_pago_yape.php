<?php
session_start();
require 'conexion.php';

// Validar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para confirmar tu pago.";
    header("Location: login.php");
    exit();
}

// Validar que tenga un pedido_id
if (!isset($_POST['pedido_id']) || empty($_POST['pedido_id'])) {
    $_SESSION['error'] = "ID de pedido no válido.";
    header("Location: finalizar_compra.php");
    exit();
}

$pedido_id = intval($_POST['pedido_id']);
$usuario_id = $_SESSION['usuario_id'];

try {
    // Verificar que el pedido pertenece al usuario actual
    $stmt_verify = $conn->prepare("SELECT id, estado, usuario_id FROM pedidos WHERE id = ? AND usuario_id = ?");
    $stmt_verify->bind_param("ii", $pedido_id, $usuario_id);
    $stmt_verify->execute();
    $result_verify = $stmt_verify->get_result();
    
    if ($result_verify->num_rows === 0) {
        throw new Exception("El pedido no existe o no pertenece a tu cuenta.");
    }
    
    $pedido = $result_verify->fetch_assoc();
    
    // Si ya está pagado, redirigir a éxito
    if ($pedido['estado'] === 'Pagado') {
        $_SESSION['info'] = "Este pedido ya fue pagado.";
        header("Location: pedido_exito.php?pedido=" . $pedido_id . "&metodo=yape");
        exit();
    }
    
    // Actualizar estado del pedido a 'Pagado'
    $estado_pagado = 'Pagado';
    $stmt_update = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    $stmt_update->bind_param("si", $estado_pagado, $pedido_id);
    
    if (!$stmt_update->execute()) {
        throw new Exception("Error al confirmar el pago del pedido.");
    }
    
    $_SESSION['success'] = "¡Tu pago ha sido confirmado exitosamente!";
    header("Location: pedido_exito.php?pedido=" . $pedido_id . "&metodo=yape");
    exit();
    
} catch (Exception $e) {
    $_SESSION['error'] = "Error al confirmar el pago: " . $e->getMessage();
    header("Location: pago_yape.php?pedido=" . $pedido_id);
    exit();
}
?>
