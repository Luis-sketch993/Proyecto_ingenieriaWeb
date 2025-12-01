<?php
include '../verificar_sesion.php';
include '../../conexion.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: index.php?error=ID de producto no especificado");
    exit();
}

// Obtener datos del producto
$sql = "SELECT id, nombre, descripcion, precio, imagen, categoria_id FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    header("Location: index.php?error=Producto no encontrado");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $categoria_id = $_POST['categoria_id'] ?? '';
    $imagen = $_POST['imagen'] ?? '';

    if (empty($nombre) || empty($descripcion) || empty($precio)) {
        $error = "Todos los campos son requeridos";
    } else {
        $sql_update = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria_id = ?, imagen = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssdisi", $nombre, $descripcion, $precio, $categoria_id, $imagen, $id);

        if ($stmt_update->execute()) {
            header("Location: index.php?success=Producto actualizado exitosamente");
            exit();
        } else {
            $error = "Error al actualizar el producto: " . $stmt_update->error;
        }
        $stmt_update->close();
    }
}

// Obtener categorías
$categorias_sql = "SELECT id, nombre FROM categorias";
$categorias_result = $conn->query($categorias_sql);
$categorias = [];
if ($categorias_result) {
    while ($row = $categorias_result->fetch_assoc()) {
        $categorias[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .sidebar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; padding: 10px 15px; display: block; }
        .sidebar a:hover { background: rgba(255,255,255,0.2); }
        .content { padding: 20px; }
        .card { box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); }
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
                <div class="mb-4">
                    <a href="index.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Volver</a>
                    <h2 class="d-inline ms-2"><i class="bi bi-pencil-square"></i> Editar Producto</h2>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre del Producto</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="categoria_id" class="form-label">Categoría</label>
                                    <select class="form-control" id="categoria_id" name="categoria_id" required>
                                        <option value="">-- Seleccionar Categoría --</option>
                                        <?php foreach ($categorias as $cat): ?>
                                            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $producto['categoria_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="precio" class="form-label">Precio (S/.)</label>
                                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?= $producto['precio'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="imagen" class="form-label">URL de Imagen</label>
                                    <input type="text" class="form-control" id="imagen" name="imagen" value="<?= htmlspecialchars($producto['imagen']) ?>">
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Actualizar Producto</button>
                                <a href="index.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
