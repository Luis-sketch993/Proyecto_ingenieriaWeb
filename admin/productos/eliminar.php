<?php
include '../verificar_sesion.php';
include '../../conexion.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: index.php?error=ID de producto no especificado");
    exit();
}

$sql = "DELETE FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?success=Producto eliminado exitosamente");
} else {
    header("Location: index.php?error=Error al eliminar el producto");
}

$stmt->close();
exit();
?>
