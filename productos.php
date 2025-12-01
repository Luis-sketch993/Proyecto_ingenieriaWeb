<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>Productos - Second Use</title>
</head>
<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  	<div class="container-fluid">

        <div class="collapse navbar-collapse" id="navbarNav">

            <span class="navbar-text me-auto text-white">
            <i class="bi bi-box-seam-fill fs-5"></i>Envío gratis en compras de más de S/.5000
            </span>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                <a class="nav-link active" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Acerca de</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Contacto</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Asistencia</a>
                </li>
            </ul>
        </div>
  </div>
</nav>


<nav class="navbar navbar-expand-lg navbar-white bg-white">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarNav">
      <h1 class="d-flex align-items-center gap-2"> 
          <a href="index.php" class="text-decoration-none text-dark">Second Use</a>
          <img src="image/logo2.jpeg" alt="Logo" style="height: 70px;">

      </h1>
      <ul class="navbar-nav ms-auto">
        
       <ul class="navbar-nav ms-auto align-items-center">

		        	<li class="nav-item">
		          		<a class="nav-link" href="favoritos_lista.php"><i class="bi bi-heart"></i> Favoritos</a>
						
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
            
            </ul>
        </div>
  </div>
</nav>


	<div class="container my-5">
		<h2 class="text-center mb-4">Productos</h2>
		<div class="row g-4">
			<!-- Producto 1 -->
			<div class="col-md-3">
				<div class="card h-100">
					<img src="image/I.webp" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="card-title">iPhone 17 Pro Max</h5>
						<p class="card-text">Pantalla Super Retina XDR de 6.9 pulgadas, procesador A19 Pro y una cámara teleobjetivo con un zoom óptico de 8x.</p>
						<a href="detalle_producto.php?id=1" class="btn btn-primary w-100">Ver detalles</a>
					</div>
				</div>
			</div>

			<!-- Producto 2 -->
			<div class="col-md-3">
				<div class="card h-100">
					<img src="image/audi.jpg" class="card-img-top" alt="...">
					<div class="card-body">
						<h5 class="card-title">Audífonos Sony WH-1000XM5</h5>
						<p class="card-text">Audífonos Noise Cancelling con Bluetooth WH-1000XM5.</p>
						<a href="detalle_producto.php?id=2" class="btn btn-primary w-100">Ver detalles</a>
					</div>
				</div>
			</div>

            <!-- Producto 3 -->
			<div class="col-md-3">
                <div class="card h-100">
					<img src="image/tablet3.jpg" class="card-img-top" alt="Xiaomi Pad 6">
					<div class="card-body">
						<h5 class="card-title">Xiaomi Pad 6</h5>
						<p class="card-text">Pantalla 144Hz, Snapdragon 8+, gran batería de 8840 mAh.</p>
						<a href="detalle_producto.php?id=22" class="btn btn-primary w-100">Ver detalles</a>
					</div>
				</div>
			</div>

            <!-- Producto 4 -->
            <div class="col-md-3">
               <div class="card h-100">
                   <img src="image/laptop4.avif" class="card-img-top" alt="HP Pavilion">
                   <div class="card-body">
                       <h5 class="card-title">Laptop HP Pavilion 14-dv0502la</h5>
                       <p class="card-text">Ideal para oficina y estudio. Intel i5, SSD de 256GB.</p>
                       <a href="detalle_producto.php?id=23" class="btn btn-primary w-100">Ver detalles</a>
                   </div>
               </div>
           </div>

            
            <!-- Producto 5 -->
            <div class="col-md-3">
                <div class="card h-100">
                    <img src="image/laptop3.jpeg" class="card-img-top" alt="Asus ROG Strix">
                    <div class="card-body">
                        <h5 class="card-title">Asus ROG Strix G17</h5>
                        <p class="card-text">Laptop gamer con Ryzen 9 y RTX 4070. 
                            16GB DDR5-5600 SO-DIMM (Expandible a 64GB)
                        </p>
                        <a href="detalle_producto.php?id=24" class="btn btn-primary w-100">Ver detalles</a>
                    </div>
                </div>
            </div>
       

                <!-- Producto 6 -->
            <div class="col-md-3">
                <div class="card h-100">
                    <img src="image/tablet4.webp" class="card-img-top" alt="Lenovo Tab P11 Pro">
                    <div class="card-body">
                        <h5 class="card-title">Tablet LENOVO 11" K11 8GB 128GB</h5>
                        <p class="card-text">Ideal para trabajo y entretenimiento con teclado desmontable.</p>
                        <a href="detalle_producto.php?id=25" class="btn btn-primary w-100">Ver detalles</a>
                    </div>
                </div>
            </div>
        </div>      
    </div>
	



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


<?php include 'footer.php'; ?>


</body>
</html>
