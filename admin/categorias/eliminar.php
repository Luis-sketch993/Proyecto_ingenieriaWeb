<?php
include '../verificar_sesion.php';
include '../../conexion.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: index.php?error=ID de categoría no especificado");
    exit();
}

$sql = "DELETE FROM categorias WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?success=Categoría eliminada exitosamente");
} else {
    header("Location: index.php?error=Error al eliminar la categoría");
}

$stmt->close();
exit();
?>
