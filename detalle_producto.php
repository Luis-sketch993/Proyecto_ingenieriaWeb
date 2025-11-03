<?php
session_start();
include 'conexion.php';

// Validar que llegue el ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Consultar producto
$sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, p.imagen, c.nombre AS categoria
        FROM productos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Si no existe el producto
if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$producto = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($producto['nombre']) ?> | Second Use</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">

  <!-- Barra superior -->
  <nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a href="index.php" class="navbar-brand">← Volver</a>
      <span class="navbar-text me-3 text-white">Second Use</span>

    </div>
  </nav>

  <div class="container py-5">
    <div class="product-card row align-items-center">
      
      <div class="col-md-5 text-center product-image">
        <img src="<?= htmlspecialchars($producto['imagen']) ?>" 
             class="img-fluid" 
             alt="<?= htmlspecialchars($producto['nombre']) ?>">
      </div>

      <div class="col-md-7 product-info">
        <h2 class="product-title"><?= htmlspecialchars($producto['nombre']) ?></h2>
        <p class="product-category"><?= htmlspecialchars($producto['categoria']) ?></p>
        <p class="product-description"><?= htmlspecialchars($producto['descripcion']) ?></p>

        <h4 class="product-price">S/. <?= number_format($producto['precio'], 2) ?></h4>
        <p class="product-stock"><strong>Stock:</strong> <?= htmlspecialchars($producto['stock']) ?> unidades</p>

        <div class="product-buttons">
          <a href="finalizar_compra.php" class="btn btn-primary me-2">Comprar ahora</a>
          <a href="carrito.php?agregar=<?= $producto['id'] ?>" class="btn btn-outline-secondary">Agregar al carrito</a>
        </div>
      </div>
    </div>
  </div>

</body>

</html>
