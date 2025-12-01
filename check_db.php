<?php
include 'conexion.php';

// Mostrar estructura de la tabla usuarios
$result = $conn->query("DESCRIBE usuarios");

if ($result) {
    echo "=== Estructura de la tabla 'usuarios' ===\n";
    while ($row = $result->fetch_assoc()) {
        echo implode(" | ", $row) . "\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}

// Mostrar estructura de la tabla productos
$result2 = $conn->query("DESCRIBE productos");

if ($result2) {
    echo "\n=== Estructura de la tabla 'productos' ===\n";
    while ($row = $result2->fetch_assoc()) {
        echo implode(" | ", $row) . "\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}

// Mostrar estructura de la tabla categorias
$result3 = $conn->query("DESCRIBE categorias");

if ($result3) {
    echo "\n=== Estructura de la tabla 'categorias' ===\n";
    while ($row = $result3->fetch_assoc()) {
        echo implode(" | ", $row) . "\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}
?>
