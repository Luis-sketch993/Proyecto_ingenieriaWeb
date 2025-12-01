<?php
include 'verificar_sesion.php';
include '../conexion.php';

// Obtener estadísticas
$productos_count = $conn->query("SELECT COUNT(*) as total FROM productos")->fetch_assoc()['total'];
$usuarios_count = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
$categorias_count = $conn->query("SELECT COUNT(*) as total FROM categorias")->fetch_assoc()['total'];

// Obtener últimos productos
$ultimos_productos = $conn->query("SELECT id, nombre, precio FROM productos ORDER BY id DESC LIMIT 5");

// Obtener últimos usuarios
$ultimos_usuarios = $conn->query("SELECT id, nombre, email, fecha_registro FROM usuarios ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin SecundUse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .sidebar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; position: sticky; top: 0; }
        .sidebar h4 { font-weight: 700; }
        .sidebar a { color: white; text-decoration: none; padding: 12px 15px; display: block; transition: all 0.3s; }
        .sidebar a:hover { background: rgba(255,255,255,0.2); padding-left: 20px; }
        .sidebar a.active { background: rgba(255,255,255,0.3); border-left: 3px solid white; }
        .content { padding: 30px; }
        .stat-card { background: white; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .stat-card h6 { color: #666; font-size: 14px; margin-bottom: 10px; }
        .stat-card .number { font-size: 32px; font-weight: 700; }
        .stat-card.blue { border-left: 4px solid #667eea; }
        .stat-card.green { border-left: 4px solid #28a745; }
        .stat-card.orange { border-left: 4px solid #fd7e14; }
        .stat-card .icon { font-size: 28px; opacity: 0.3; }
        .card { border: none; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px 8px 0 0; }
        .table { margin-bottom: 0; }
        .btn-group-sm a { margin: 2px; }
        .welcome-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; }
        .welcome-header h1 { font-size: 28px; font-weight: 700; margin: 0; }
        .welcome-header p { margin: 5px 0 0 0; opacity: 0.9; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h4 class="text-white p-3"><i class="bi bi-speedometer2"></i> Admin SecondUse</h4>
                <a href="dashboard.php" class="active"><i class="bi bi-house"></i> Dashboard</a>
                <a href="productos/index.php"><i class="bi bi-box"></i> Productos</a>
                <a href="usuarios/index.php"><i class="bi bi-people"></i> Usuarios</a>
                <a href="categorias/index.php"><i class="bi bi-tag"></i> Categorías</a>
                <hr class="text-white">
                <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
            </div>

            <!-- Contenido Principal -->
            <div class="col-md-10 content">
                <!-- Header de Bienvenida -->
                <div class="welcome-header">
                    <h1>¡Bienvenido, <?= htmlspecialchars($_SESSION['admin']) ?>! 👋</h1>
                    <p>Panel de Administración - Second Use</p>
                </div>

                <!-- Tarjetas de Estadísticas -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card blue">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6>Total de Productos</h6>
                                    <div class="number"><?= $productos_count ?></div>
                                </div>
                                <i class="bi bi-box icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card green">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6>Total de Usuarios</h6>
                                    <div class="number"><?= $usuarios_count ?></div>
                                </div>
                                <i class="bi bi-people icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card orange">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6>Total de Categorías</h6>
                                    <div class="number"><?= $categorias_count ?></div>
                                </div>
                                <i class="bi bi-tag icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimos Productos -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-box"></i> Últimos Productos</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($prod = $ultimos_productos->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $prod['id'] ?></td>
                                                <td><?= htmlspecialchars(substr($prod['nombre'], 0, 20)) ?></td>
                                                <td>S/. <?= number_format($prod['precio'], 2) ?></td>
                                                <td>
                                                    <a href="productos/editar.php?id=<?= $prod['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-center">
                                <a href="productos/index.php" class="btn btn-sm btn-outline-primary">Ver Todos</a>
                            </div>
                        </div>
                    </div>

                    <!-- Últimos Usuarios -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-people"></i> Últimos Usuarios</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Email</th>
                                            <th>Fecha</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($user = $ultimos_usuarios->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($user['nombre']) ?></td>
                                                <td><?= htmlspecialchars(substr($user['email'], 0, 20)) ?></td>
                                                <td><?= substr($user['fecha_registro'], 0, 10) ?></td>
                                                <td>
                                                    <a href="usuarios/editar.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-center">
                                <a href="usuarios/index.php" class="btn btn-sm btn-outline-primary">Ver Todos</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Atajos Rápidos -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="mb-3">Acciones Rápidas</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="productos/crear.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo Producto</a>
                            <a href="usuarios/crear.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo Usuario</a>
                            <a href="categorias/crear.php" class="btn btn-warning"><i class="bi bi-plus-circle"></i> Nueva Categoría</a>
                            <a href="../index.php" class="btn btn-secondary"><i class="bi bi-house"></i> Ir a Tienda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
