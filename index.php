<?php
session_start();

include 'conexion.php';

$sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos";
$result = $conn->query($sql);

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
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

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
							<i class="bi bi-cart"></i> carrito</a>
							<span class="ms-1">Carrito</span>
						
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
			<a href="productos.html" class="btn btn-dark">Comprar</a>
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

		  <a href="celulares.html" class="btn btn-dark">Tienda</a>
	
	    </div>
	  </div>

	  <div class="image-wrapper">
	    <img src="image/audi.jpg" alt="Imagen 2">
	    <div class="image-text">
	      <h3>Lleva tu sonido donde quieras</h3>
	      <p>Mejores marcas en audífonos</p>
		  <a href="audio.html" class="btn btn-dark">Comprar</a>
	      
		  
 	    </div>
	  </div>
	</div>


<div class="cont4 d-flex flex-wrap gap-3 justify-content-center">
  <?php while($row = $result->fetch_assoc()): ?>
    <div class="card" style="width: 15rem;">
		<a href="detalle_producto.php?id=<?= $row['id'] ?>">
        <img src="<?= htmlspecialchars($row['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nombre']) ?>">
      </a>
      <div class="card-body">
        <p class="card-text fw-bold"><?= htmlspecialchars($row['nombre']) ?></p>
        <p class="text-muted">S/. <?= number_format($row['precio'], 2) ?></p>
        <a href="detalle_producto.php?id=<?= $row['id'] ?>" class="btn btn-primary">Ver detalles</a>
      </div>
    </div>
  <?php endwhile; ?>
</div>
					

<div class="categorias">
  <div class="categoria">
    <img src="image/lap.avif" alt="Computadoras">
    <p>Computadoras</p>
  </div>
  <div class="categoria">
    <img src="image/celu.webp" alt="Celulares">
    <p>Celulares</p>
  </div>
  <div class="categoria">
    <img src="image/dronn.avif" alt="Drones y cámaras">
    <p>Drones y cámaras</p>
  </div>
  <div class="categoria">
    <img src="image/immg.png" alt="Oferta">
    <p>Oferta</p>
  </div>
  <div class="categoria">
    <img src="image/img.jpeg" alt="Tabletas">
    <p>Tabletas</p>
  </div>
  <div class="categoria">
    <img src="image/los.webp" alt="Más vendidos">
    <p>Más vendidos</p>
  </div>
  <div class="categoria">
    <img src="image/tv.jpeg" alt="T.V. y cine en casa">
    <p>T.V. y cine en casa</p>
  </div>
  <div class="categoria">
    <img src="image/sm.webp" alt="Tecnología portátil">
    <p>Tecnología portátil</p>
  </div>
  <div class="categoria">
    <img src="image/bs.webp" alt="Bocinas">
    <p>Bocinas</p>
  </div>
  <div class="categoria">
    <img src="image/df.webp" alt="Audífonos">
    <p>Audífonos</p>
  </div>

  <div>
	<?php
		include 'footer.php';
	?>

  </div>


</body>
</html>
