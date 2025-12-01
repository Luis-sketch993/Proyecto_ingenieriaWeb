<?php
session_start();

include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($nombre) || empty($password)) {
        header("Location: login.php?error=Usuario y contraseña son requeridos");
        exit();
    }

    // Buscar admin en la base de datos
    $sql = "SELECT id, nombre, password FROM usuarios WHERE nombre = ? AND rol = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['nombre'];
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: login.php?error=Usuario o contraseña incorrectos");
        exit();
    }
    $stmt->close();
}

header("Location: login.php");
exit();
?>
