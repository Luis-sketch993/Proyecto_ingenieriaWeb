<?php
session_start();

// Si el carrito está vacío, redirigir
if (empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit();
}

$carrito = $_SESSION['carrito'];
$total = 0;
foreach ($carrito as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Finalizar compra | Second Use</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow-sm mb-4">
  <div class="container-fluid">
    <a href="carrito.php" class="navbar-brand">← Volver al carrito</a>
  </div>
</nav>

<div class="container">
  <div class="row">
    <!-- 🧾 Resumen del pedido -->
    <div class="col-md-5 order-md-2 mb-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Tu pedido</h4>
          <ul class="list-group mb-3">
            <?php foreach ($carrito as $producto): ?>
              <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                  <h6 class="my-0"><?= htmlspecialchars($producto['nombre']) ?> (x<?= $producto['cantidad'] ?>)</h6>
                </div>
                <span class="text-muted">S/. <?= number_format($producto['precio'] * $producto['cantidad'], 2) ?></span>
              </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between">
              <strong>Total</strong>
              <strong>S/. <?= number_format($total, 2) ?></strong>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- 🧍 Datos del cliente -->
    <div class="col-md-7">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Datos del comprador</h4>
          <form method="POST" action="procesar_pedido.php">
            <div class="mb-3">
              <label class="form-label">Nombre completo</label>


              <input type="text" 
                 name="nombre" 
                 class="form-control" 
                 value="<?= htmlspecialchars($_SESSION['usuario'] ?? '') ?>" 
                 readonly>

            </div>
            <div class="mb-3">
              <label class="form-label">Teléfono</label>
              <input type="tel" name="telefono" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Dirección de entrega</label>
              <textarea name="direccion" class="form-control" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-success w-100 btn-lg">
              Confirmar pedido
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
