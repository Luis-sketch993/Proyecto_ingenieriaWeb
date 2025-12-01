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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <title><?php echo isset($page_title) ? $page_title : 'Second Use'; ?></title>
</head>
<body>

<!-- BARRA SUPERIOR -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  	<div class="container-fluid">           
	    	<div class="collapse navbar-collapse" id="navbarNav">
	    		<span class="navbar-text me-auto text-white">
	  		 		<i class="bi bi-box-seam-fill fs-5"></i> Envío gratis en compras de más de S/.5000
				</span>

	      		<ul class="navbar-nav ms-auto">
			        <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
			        <li class="nav-item"><a class="nav-link" href="#">Acerca de</a></li>
			        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
			        <li class="nav-item"><a class="nav-link" href="#">Asistencia</a></li>
	      		</ul>
	    	</div>
	  	</div>
	</nav>

<nav class="navbar navbar-expand-lg navbar-white bg-white">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarMiddle">
      <h1 class="d-flex align-items-center gap-2">
          <a href="index.php" class="text-decoration-none text-dark d-flex align-items-center gap-2">
            Second Use
            <img src="image/logo2.jpeg" alt="Logo" style="height: 70px;">
          </a>
      </h1>
      <ul class="navbar-nav ms-auto align-items-center">

        <?php if (isset($_SESSION['usuario'])): ?>
          <li class="nav-item">
            <span class="nav-link text-dark">
              <i class="bi bi-person-circle"></i> ¡Hola, <?= htmlspecialchars($_SESSION['usuario']) ?>!
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">
              <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="login.php">
              <i class="bi bi-person-circle"></i> Entrar
            </a>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="favoritos_lista.php"><i class="bi bi-heart"></i> Favoritos</a>
        </li>

        <li class="nav-item">
          <a class="nav-link d-inline-flex align-items-center position-relative" href="carrito.php">
           
            <span class="ms-1 bi bi-cart">Carrito</span>

            <?php
            $contador = 0;
            if (isset($_SESSION['carrito'])) {
              foreach ($_SESSION['carrito'] as $item) {
                $contador += $item['cantidad'];
              }
            }

            if ($contador > 0): ?>
            <span class="badge rounded-pill bg-danger ms-2" style="font-size: 0.75rem; vertical-align: middle;">
              <?= $contador ?>
            </span>
            <?php endif; ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<!-- MENÚ PRINCIPAL -->
	<nav class="navbar navbar-expand-lg bg-body-tertiary">
	  <div class="container-fluid">
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarNav">
	      <ul class="navbar-nav">
	        <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
	        <li class="nav-item"><a class="nav-link" href="computadoras.php">Computadoras</a></li>
	        <li class="nav-item"><a class="nav-link" href="tabletas.php">Tabletas</a></li>
	        <li class="nav-item"><a class="nav-link" href="drones.php">Drones y cámaras</a></li>
	        <li class="nav-item"><a class="nav-link" href="audio.php">Audio</a></li>
	        <li class="nav-item"><a class="nav-link" href="celulares.php">Celulares</a></li>
	        <li class="nav-item"><a class="nav-link" href="tv.php">T.V. y cine en casa</a></li>
	        <li class="nav-item"><a class="nav-link" href="tecnologia.php">Tecnología portátil</a></li>
	        <li class="nav-item"><a class="nav-link" href="oferta.php">Oferta</a></li>
	      </ul>
	    </div>
	  </div>
	</nav>