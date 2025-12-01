<?php
session_start();
include 'conexion.php';

// Validar que los campos no estén vacíos
if (empty($_POST['email']) || empty($_POST['password'])) {
    $_SESSION['error'] = "Por favor completa todos los campos.";
    header("Location: login.php");
    exit();
}

$email = $_POST['email'];
$password = $_POST['password'];

// Verificar el correo en la base de datos
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    // Verificar contraseña cifrada
    if (password_verify($password, $usuario['password'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario'] = $usuario['nombre'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['rol'] = $usuario['rol'];
        header("Location: index.php");
        exit();
    } 
    
}


    $_SESSION['error'] = "Credenciales incorrectas. Inténtalo de nuevo.";
    header("Location: login.php");
    exit();

?>

