<?php
include 'conexion.php';

// Crear un usuario admin de prueba
$nombre = "admin";
$email = "admin@seconduse.com";
$password = password_hash("admin123", PASSWORD_BCRYPT);
$telefono = "000000000";
$direccion = "Admin";

$sql = "INSERT INTO usuarios (nombre, email, password, telefono, direccion, rol) VALUES (?, ?, ?, ?, ?, 'admin')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nombre, $email, $password, $telefono, $direccion);

if ($stmt->execute()) {
    echo "✓ Usuario admin creado exitosamente";
    echo "<br>Usuario: admin";
    echo "<br>Contraseña: admin123";
    echo "<br><br><a href='admin/login.php'>Ir al login</a>";
} else {
    if (strpos($stmt->error, 'Duplicate entry') !== false) {
        echo "⚠ El usuario admin ya existe";
        echo "<br><br><a href='admin/login.php'>Ir al login</a>";
    } else {
        echo "✗ Error: " . $stmt->error;
    }
}
$stmt->close();
?>
