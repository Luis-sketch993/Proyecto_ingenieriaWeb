<?php
$host = 'localhost';
$usuario = 'root';
$password = '';
$basededatos = 'seconduse_db';

$conn = new mysqli($host, $usuario, $password, $basededatos);

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

$result = $conn->query('SELECT id, nombre, precio FROM productos ORDER BY id');

if ($result && $result->num_rows > 0) {
    echo "IDs en la base de datos:\n";
    while($row = $result->fetch_assoc()) {
        echo $row['id'] . ' - ' . $row['nombre'] . ' - S/. ' . $row['precio'] . "\n";
    }
} else {
    echo "No hay productos en la base de datos.\n";
}

$conn->close();
?>
