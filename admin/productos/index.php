<?php
include '../verificar_sesion.php';
include '../../conexion.php';

$sql = "SELECT id, nombre, descripcion, precio, imagen, categoria_id FROM productos";
$result = $conn->query($sql);
$productos = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .sidebar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; padding: 10px 15px; display: block; }
        .sidebar a:hover { background: rgba(255,255,255,0.2); }
        .content { padding: 20px; }
        .card { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .table-responsive { background: white; border-radius: 8px; }
        .header-action { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h4 class="text-white p-3">📊 Admin Panel SecondUse</h4>
                <a href="../dashboard.php"><i class="bi bi-house"></i> Dashboard</a>
                <a href="index.php"><i class="bi bi-box"></i> Productos</a>
                <a href="../usuarios/index.php"><i class="bi bi-people"></i> Usuarios</a>
                <a href="../categorias/index.php"><i class="bi bi-tag"></i> Categorías</a>
                <hr class="text-white">
                <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
            </div>

            <!-- Contenido Principal -->
            <div class="col-md-10 content">
                <div class="header-action">
                    <h2><i class="bi bi-box"></i> Gestión de Productos</h2>
                    <a href="crear.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo Producto</a>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Categoría</th>
                                <th>Imagen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productos)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No hay productos registrados</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($productos as $prod): ?>
                                    <tr>
                                        <td><?= $prod['id'] ?></td>
                                        <td><strong><?= htmlspecialchars($prod['nombre']) ?></strong></td>
                                        <td><?= htmlspecialchars(substr($prod['descripcion'], 0, 50)) ?>...</td>
                                        <td>S/. <?= number_format($prod['precio'], 2) ?></td>
                                        <td><?= $prod['categoria_id'] ?></td>
                                        <td>
                                            <?php if ($prod['imagen']): ?>
                                                <img src="../../<?= htmlspecialchars($prod['imagen']) ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <span class="text-muted">Sin imagen</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="editar.php?id=<?= $prod['id'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></a>
                                            <a href="eliminar.php?id=<?= $prod['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
