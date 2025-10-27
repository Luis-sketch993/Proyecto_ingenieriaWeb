<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
	<link rel="stylesheet" type="text/css" href="styles.css">

	<title>Second Use | Tecnología reacondicionada</title>
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

	<!-- LOGO Y SESIÓN -->
	<nav class="navbar navbar-expand-lg bg-white">
	  	<div class="container-fluid">
	    	<div class="collapse navbar-collapse" id="navbarNav">
		      	<h1 class="d-flex align-items-center gap-2">
			        <a href="index.php" class="text-decoration-none text-dark d-flex align-items-center gap-2">
			          Second Use
			          <img src="image/logo.jpeg" alt="Logo" style="height: 70px;">
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
		          		<a class="nav-link" href="#"><i class="bi bi-heart"></i> Favoritos</a>
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
	        <li class="nav-item"><a class="nav-link" href="productos.html">Productos</a></li>
	        <li class="nav-item"><a class="nav-link" href="computadoras.html">Computadoras</a></li>
	        <li class="nav-item"><a class="nav-link" href="tabletas.html">Tabletas</a></li>
	        <li class="nav-item"><a class="nav-link" href="drones.html">Drones y cámaras</a></li>
	        <li class="nav-item"><a class="nav-link" href="audio.html">Audio</a></li>
	        <li class="nav-item"><a class="nav-link" href="celulares.html">Celulares</a></li>
	        <li class="nav-item"><a class="nav-link" href="tv.html">T.V. y cine en casa</a></li>
	        <li class="nav-item"><a class="nav-link" href="tecnologia.html">Tecnología portátil</a></li>
	        <li class="nav-item"><a class="nav-link" href="oferta.html">Oferta</a></li>
	      </ul>
	    </div>
	  </div>
	</nav>

	<!-- CARRUSEL -->
	<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
	  <div class="carousel-inner">
	    <div class="carousel-item active">
	      <img src="image/Lp.jpg" class="d-block w-100" alt="Laptop">
	    </div>
	    <div class="carousel-item">
	      <img src="image/xbox.jpg" class="d-block w-100" alt="Xbox">
	    </div>
	    <div class="carousel-item">
	      <img src="image/au.jpg" class="d-block w-100" alt="Audio">
	    </div>

	    <div class="carousel-caption text-start" style="position: absolute; top: 50%; left: 5%; transform: translateY(-50%); max-width: 50%;">
		    <h5 class="fs-1">Tecnología confiable, al alcance de todos</h5>
		    <p>Compra seguro, usa confiado y paga seguro</p>
		    <button class="btn btn-dark">Comprar</button>
	    </div>
	  </div>
	</div>

	<!-- SECCIONES DE PRODUCTOS -->
	<div class="cont3">
	  <div class="image-wrapper">
	    <img src="image/I.webp" alt="Imagen 1">
	    <div class="image-text">
	      <h3>Iphone 17 Pro Max</h3>
	      <p>Rendimiento fuera de serie</p>
	      <button class="btn btn-dark">Comprar</button>
	    </div>
	  </div>

	  <div class="image-wrapper">
	    <img src="image/audi.jpg" alt="Imagen 2">
	    <div class="image-text">
	      <h3>Lleva tu sonido donde quieras</h3>
	      <p>Mejores marcas en audífonos</p>
	      <button class="btn btn-dark">Comprar</button>
	    </div>
	  </div>
	</div>

	<!-- TARJETAS DE PRODUCTOS -->
	<div class="cont4 d-flex flex-wrap gap-3 justify-content-center">
		<?php for ($i = 1; $i <= 4; $i++): ?>
			<div class="card" style="width: 18rem;">
			  <img src="image/I.webp" class="card-img-top" alt="Producto">
			  <div class="card-body">
			    <h5 class="card-title">Producto <?= $i ?></h5>
			    <p class="card-text">Descripción breve del producto reacondicionado número <?= $i ?>.</p>
			    <a href="#" class="btn btn-primary">Ver más</a>
			  </div>
			</div>
		<?php endfor; ?>
	</div>

</body>
</html>
