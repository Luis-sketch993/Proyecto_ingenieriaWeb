<?php
session_start();

// Crear variable si no existe
if (!isset($_SESSION['favoritos'])) {
    $_SESSION['favoritos'] = [];
}

// Agregar a favoritos
if (isset($_GET['add']) && is_numeric($_GET['add'])) {
    $id = intval($_GET['add']);

    if (!in_array($id, $_SESSION['favoritos'])) {
        $_SESSION['favoritos'][] = $id;
    }

    header("Location: detalle_producto.php?id=" . $id);
    exit();
}

// Quitar de favoritos
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $id = intval($_GET['remove']);

    if (($key = array_search($id, $_SESSION['favoritos'])) !== false) {
        unset($_SESSION['favoritos'][$key]);
    }

    header("Location: detalle_producto.php?id=" . $id);
    exit();
}

// Si llega aquí, ir a lista
header("Location: favoritos_lista.php");
exit();
