<?php
// Datos de conexión
$host = "localhost";       // Servidor (normalmente localhost en XAMPP)
$usuario = "root";         // Usuario de MySQL (por defecto root)
$password = "";            // Contraseña (vacía en XAMPP)
$basededatos = "seconduse_db"; // Nombre exacto de tu base de datos

// Crear conexión
$conn = new mysqli($host, $usuario, $password, $basededatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
