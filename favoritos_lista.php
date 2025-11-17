<?php
session_start();
require 'conexion.php';

// Si no hay favoritos
if (!isset($_SESSION['favoritos']) || empty($_SESSION['favoritos'])) {
    $productos = [];
} else {
    // Convertir lista en string para consulta
    $ids = implode(",", array_map('intval', $_SESSION['favoritos']));
    $sql = "SELECT * FROM productos WHERE id IN ($ids)";
    $productos = $conn->query($sql);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis favoritos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow-sm mb-4">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand">← Volver al inicio</a>
        <span class="navbar-text text-white">Mis Favoritos</span>
    </div>
</nav>

<div class="container py-4">

    <h2 class="mb-4">Productos favoritos</h2>

    <?php if (empty($productos)): ?>
        <p class="text-muted">No tienes productos en favoritos.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php while($p = $productos->fetch_assoc()): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <a href="detalle_producto.php?id=<?= $p['id'] ?>" class="text-decoration-none text-dark">
                    <div class="card h-100">
                        <img src="<?= $p['imagen'] ?>" class="card-img-top" alt="<?= $p['nombre'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $p['nombre'] ?></h5>
                            <p class="card-price fw-bold">S/. <?= number_format($p['precio'], 2) ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
