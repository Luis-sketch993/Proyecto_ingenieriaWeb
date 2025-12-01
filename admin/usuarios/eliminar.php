<?php
include '../verificar_sesion.php';
include '../../conexion.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: index.php?error=ID de usuario no especificado");
    exit();
}

$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?success=Usuario eliminado exitosamente");
} else {
    header("Location: index.php?error=Error al eliminar el usuario");
}

$stmt->close();
exit();
?>
