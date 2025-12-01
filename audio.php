<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>Audio - Second Use</title>
</head>
<body>

<!-- 🔹 Navbar 1 -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarTop">
      <span class="navbar-text me-auto text-white">
        <i class="bi bi-box-seam-fill fs-5"></i> Envío gratis en compras de más de S/.5000
      </span>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Acerca de</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Asistencia</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Navbar 2 -->
<nav class="navbar navbar-expand-lg navbar-white bg-white">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarMiddle">

      <h1 class="d-flex align-items-center gap-2">
          <a href="index.php" class="text-decoration-none text-dark">Second Use</a>
          <img src="image/logo2.jpeg" alt="Logo" style="height: 70px;">
      </h1>

      <ul class="navbar-nav ms-auto align-items-center">
        
        <li class="nav-item"><a class="nav-link" href="favoritos_lista.php"><i class="bi bi-heart"></i> Favoritos</a></li>

        <li class="nav-item">
			<a class="nav-link" href="carrito.php"><i class="bi bi-cart"></i> Carrito</a>
		</li>
    
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Navbar 3 -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarBottom"
      aria-controls="navbarBottom" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarBottom">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="computadoras.php">Computadoras</a></li>
        <li class="nav-item"><a class="nav-link" href="tabletas.php">Tabletas</a></li>
        <li class="nav-item"><a class="nav-link" href="drones.php">Drones y cámaras</a></li>
        <li class="nav-item"><a class="nav-link active" href="audio.php">Audio</a></li>
        <li class="nav-item"><a class="nav-link" href="celulares.php">Celulares</a></li>
        <li class="nav-item"><a class="nav-link" href="tv.php">T.V. y cine en casa</a></li>
        <li class="nav-item"><a class="nav-link" href="tecnologia.php">Tecnología portátil</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Contenido principal -->
<div class="container my-5">
  <h2 class="text-center mb-4">Audio</h2>
  <div class="row g-4">

    <!-- Producto 101 -->
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <img src="image/audio1.avif" class="card-img-top" alt="Audífonos Balo">
        <div class="card-body d-flex flex-column">
          <p class="card-text flex-grow-1">Audífonos Balo inalámbricos con cancelación de ruido</p>
          
          <a href="detalle_producto.php?id=101" class="btn btn-primary w-100 mt-2">
            <i class="bi bi-cart-plus"></i> Ver detalles  
          </a>
        </div>
      </div>
    </div>

    <!-- Producto 102 -->
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <img src="image/audio2.avif" class="card-img-top" alt="Space Buds">
        <div class="card-body d-flex flex-column">
          <p class="card-text flex-grow-1">Audífonos inalámbricos Space Buds True Earbud</p>
          
          <a href="detalle_producto.php?id=102" class="btn btn-primary w-100 mt-2">
            <i class="bi bi-cart-plus"></i> Ver detalles
          </a>
        </div>
      </div>
    </div>

    <!-- Producto 103 -->
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <img src="image/audio3.avif" class="card-img-top" alt="Drums Pro">
        <div class="card-body d-flex flex-column">
          <p class="card-text flex-grow-1">Drums Pro audífonos inalámbricos sobre el oído</p>
         
          <a href="detalle_producto.php?id=103" class="btn btn-primary w-100 mt-2">
            <i class="bi bi-cart-plus"></i> Ver detalles
          </a>
        </div>
      </div>
    </div>

    <!-- Producto 104 -->
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <img src="image/audio4.avif" class="card-img-top" alt="Audífonos Cancelación Ruido">
        <div class="card-body d-flex flex-column">
          <p class="card-text flex-grow-1">Audífonos inalámbricos con cancelación del ruido</p>
        
          <a href="detalle_producto.php?id=104" class="btn btn-primary w-100 mt-2">
            <i class="bi bi-cart-plus"></i> Ver detalles
          </a>
        </div>
      </div>
    </div>

    <!-- Producto 105 -->
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <img src="image/audio5.avif" class="card-img-top" alt="Audífonos Pantony">
        <div class="card-body d-flex flex-column">
          <p class="card-text flex-grow-1">Audífonos inalámbricos Pantony de botón certificados</p>
          
          <a href="detalle_producto.php?id=105" class="btn btn-primary w-100 mt-2">
            <i class="bi bi-cart-plus"></i> Ver detalles
          </a>
        </div>
      </div>
    </div>

    <!-- Producto 106 -->
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <img src="image/audio6.avif" class="card-img-top" alt="Audífonos Blancos">
        <div class="card-body d-flex flex-column">
          <p class="card-text flex-grow-1">Audífonos inalámbricos blancos de botón</p>
      
          <a href="detalle_producto.php?id=106" class="btn btn-primary w-100 mt-2">
            <i class="bi bi-cart-plus"></i> Ver detalles
          </a>
        </div>
      </div>
    </div>

    <!-- Producto 107 -->
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <img src="image/audio7.avif" class="card-img-top" alt="Audífonos Gamer">
        <div class="card-body d-flex flex-column">
          <p class="card-text flex-grow-1">Audífonos para videojuegos con sonido envolvente 10.2</p>
    
          <a href="detalle_producto.php?id=107" class="btn btn-primary w-100 mt-2">
            <i class="bi bi-cart-plus"></i> Ver detalles
          </a>
        </div>
      </div>
    </div>

    <!-- Producto 108 -->
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <img src="image/audio8.avif" class="card-img-top" alt="Audífonos MX5">
        <div class="card-body d-flex flex-column">
          <p class="card-text flex-grow-1">Audífonos con cable MX5</p>
        
          <a href="detalle_producto.php?id=108" class="btn btn-primary w-100 mt-2">
            <i class="bi bi-cart-plus"></i> Ver detalles
          </a>
        </div>
      </div>
    </div>

  </div>
</div>


<!-- 🔹 Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

      <?php include 'footer.php'; ?>


</body>
</html>
