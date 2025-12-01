<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Este correo ya está registrado.";
        header("Location: register.php");
        exit();
    }

    // Insertar el nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $password);

    if ($stmt->execute()) {
        // Obtener el ID del usuario recién creado
        $usuario_id = $stmt->insert_id;
        
        // Iniciar sesión automáticamente
        $_SESSION['usuario_id'] = $usuario_id;
        $_SESSION['usuario'] = $nombre;
        $_SESSION['email'] = $email;
        $_SESSION['rol'] = 'cliente';
        
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Error al registrar usuario: " . $conn->error;
        header("Location: register.php");
    }

    $stmt->close();
    $conn->close();
}
?>
