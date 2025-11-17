<?php
// Inicia la sesión al inicio de cada página que use el header
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Función de utilidad para determinar si un enlace es activo
function is_active($page) {
    return (basename($_SERVER['PHP_SELF']) == $page) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" type="text/css" href="styles.css">
    <title><?php echo isset($page_title) ? $page_title : 'Second Use'; ?></title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarTop">
            <span class="navbar-text me-auto text-white">
            <i class="bi bi-box-seam-fill fs-5"></i> Envío gratis en compras de más de S/.5000
            </span>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link <?= is_active('index.php') ?>" href="index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="acerca_de.php">Acerca de</a></li>
                <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
                <li class="nav-item"><a class="nav-link" href="asistencia.php">Asistencia</a></li>
            </ul>
        </div>
  </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-white bg-white">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarMiddle">
      <h1 class="d-flex align-items-center gap-2">
          <a href="index.php" class="text-decoration-none text-dark">Second Use</a>
          <img src="image/logo2.jpeg" alt="Logo" style="height: 70px;">
      </h1>
      <ul class="navbar-nav ms-auto">
        <li class="navbar-item">
          <a class="nav-link" href="favoritos.php"><i class="bi bi-heart"></i> Favoritos</a>
        </li>
        <li class="nav-item">
		          <a class="nav-link" href="carrito.php"><i class="bi bi-cart"></i> Carrito</a>
		    </li>
      </ul>
    </div>
  </div>
</nav>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarBottom"
        aria-controls="navbarBottom" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarBottom">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link <?= is_active('productos.html') ?>" href="productos.html">Productos</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active('computadoras.html') ?>" href="computadoras.html">Computadoras</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active('tabletas.html') ?>" href="tabletas.html">Tabletas</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active('drones.html') ?>" href="drones.html">Drones y cámaras</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active('audio.php') ?>" href="audio.php">Audio</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active('celulares.html') ?>" href="celulares.html">Celulares</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active('tv.html') ?>" href="tv.html">T.V. y cine en casa</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active('tecnologia.html') ?>" href="tecnologia.html">Tecnología portátil</a></li>
            </ul>
        </div>
  </div>
</nav>