<?php
session_start();
include 'conexion.php';

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto
if (isset($_GET['agregar'])) {
    $id = intval($_GET['agregar']);
    $sql = "SELECT id, nombre, precio, imagen FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($producto = $result->fetch_assoc()) {
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad']++;
        } else {
            $_SESSION['carrito'][$id] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'imagen' => $producto['imagen'],
                'cantidad' => 1
            ];
        }
    }

    header("Location: carrito.php");
    exit();
}

// Aumentar o disminuir cantidad
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($_GET['accion'] === 'sumar') {
        $_SESSION['carrito'][$id]['cantidad']++;
    } elseif ($_GET['accion'] === 'restar') {
        $_SESSION['carrito'][$id]['cantidad']--;
        if ($_SESSION['carrito'][$id]['cantidad'] < 0) {
            $_SESSION['carrito'][$id]['cantidad'] = 0;
        }
    } elseif ($_GET['accion'] === 'eliminar') {
        unset($_SESSION['carrito'][$id]);
    } elseif ($_GET['accion'] === 'reactivar') {
        $_SESSION['carrito'][$id]['cantidad'] = 1;
    }

    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito de Compras | Second Use</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">

  <!-- Barra superior -->
  <nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a href="index.php" class="navbar-brand">← Seguir comprando</a>
      <span class="navbar-text me-3 text-white">🛒 Carrito de Compras</span>
    </div>
  </nav>

  <div class="container py-5">
    <h2 class="mb-4 text-center">Tu Carrito</h2>

    <?php if (empty($_SESSION['carrito'])): ?>
      <div class="alert alert-info text-center">Tu carrito está vacío 😢</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow-sm">
          <thead class="table-dark">
            <tr>
              <th>Imagen</th>
              <th>Producto</th>
              <th>Precio</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $total = 0;
            foreach ($_SESSION['carrito'] as $item):
              $subtotal = $item['precio'] * $item['cantidad'];
              $total += $subtotal;
            ?>
            <tr class="<?= $item['cantidad'] == 0 ? 'table-warning' : '' ?>">
              <td><img src="<?= htmlspecialchars($item['imagen']) ?>" width="80" height="80" class="rounded"></td>
              <td><?= htmlspecialchars($item['nombre']) ?></td>
              <td>S/. <?= number_format($item['precio'], 2) ?></td>
              <td>
                <?php if ($item['cantidad'] > 0): ?>
                  <a href="carrito.php?accion=restar&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-secondary">−</a>
                  <span class="mx-2"><?= $item['cantidad'] ?></span>
                  <a href="carrito.php?accion=sumar&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-secondary">+</a>
                <?php else: ?>
                  <span class="text-muted">Sin cantidad</span><br>
                  <a href="carrito.php?accion=reactivar&id=<?= $item['id'] ?>" class="btn btn-sm btn-success mt-2">Reactivar</a>
                <?php endif; ?>
              </td>
              <td>
                <?= $item['cantidad'] > 0 ? 'S/. '.number_format($subtotal, 2) : '-' ?>
              </td>
              <td>
                <a href="carrito.php?accion=eliminar&id=<?= $item['id'] ?>" class="btn btn-sm btn-danger">🗑</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="text-end mt-4">
        <h4>Total: <span class="text-success fw-bold">S/. <?= number_format($total, 2) ?></span></h4>
        <a href="index.php" class="btn btn-outline-dark me-2">Seguir comprando</a>
        <?php if (isset($_SESSION['usuario'])): ?>
    <!-- Usuario logueado: puede finalizar compra -->
    <a href="finalizar_compra.php" class="btn btn-success btn-lg mt-3">
        Finalizar compra
    </a>
    <?php else: ?>

    <!-- Usuario no logueado: lo manda al login -->
    <a href="login.php" class="btn btn-warning btn-lg mt-3">
        Inicia sesión para finalizar compra
    </a>
<?php endif; ?>

      </div>
    <?php endif; ?>
  </div>
</body>
</html>
