<?php
session_start();
include 'conexion.php';

// Si el usuario no está logueado → lo manda al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Si el carrito está vacío → vuelve al carrito
if (empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Calcular total del pedido
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Crear pedido
$estado = 'Pendiente';
$fecha = date('Y-m-d H:i:s');

$sql = "INSERT INTO pedidos (usuario_id, fecha, total, estado) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isds", $usuario_id, $fecha, $total, $estado);
$stmt->execute();

$pedido_id = $conn->insert_id;

// Insertar detalle del pedido
foreach ($_SESSION['carrito'] as $item) {
    $sql_detalle = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario)
                    VALUES (?, ?, ?, ?)";
    $stmt_det = $conn->prepare($sql_detalle);
    $stmt_det->bind_param("iiid", $pedido_id, $item['id'], $item['cantidad'], $item['precio']);
    $stmt_det->execute();

    // Actualizar stock
    $sql_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
    $stmt_stock = $conn->prepare($sql_stock);
    $stmt_stock->bind_param("ii", $item['cantidad'], $item['id']);
    $stmt_stock->execute();
}

// Vaciar carrito
unset($_SESSION['carrito']);

// Mensaje y redirección
echo "<script>
alert('✅ Pedido realizado con éxito. ¡Gracias por tu compra!');
window.location='index.php';
</script>";
?>

// ✅ Mostrar mensaje de éxito
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pedido realizado con éxito</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f5f6fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .success-box {
      background: white;
      padding: 50px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      text-align: center;
      animation: fadeIn 0.7s ease-in-out;
    }
    .success-box h2 {
      color: #28a745;
      font-weight: 700;
    }
    .success-box p {
      color: #555;
      font-size: 1.1rem;
    }
    .success-icon {
      font-size: 80px;
      color: #28a745;
      margin-bottom: 20px;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="success-box">
    <div class="success-icon">✅</div>
    <h2>¡Pedido realizado con éxito!</h2>
    <p>Gracias por tu compra, <strong><?= htmlspecialchars($nombre) ?></strong>.</p>
    <p>Tu pedido ha sido registrado y se encuentra en estado <strong>Pendiente</strong>.</p>
    <p>Recibirás una notificación cuando se procese tu pedido.</p>
    <hr>
    <div class="mt-3">
      <a href="mis_pedidos.php" class="btn btn-success me-2">Ver mis pedidos</a>
      <a href="index.php" class="btn btn-outline-dark">Volver a la tienda</a>
    </div>
  </div>
</body>
</html>