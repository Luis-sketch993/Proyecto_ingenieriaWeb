<?php
session_start();
require 'conexion.php'; 

// Validar que llegue el ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Sanitizar el ID
$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($id === false) {
    header("Location: index.php");
    exit();
}

// 1. Consultar el producto principal
$sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, p.imagen, p.categoria_id, c.nombre AS categoria
        FROM productos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$producto = $result->fetch_assoc();
$categoria_id = $producto['categoria_id'];


// 2. Consultar productos relacionados (misma categoría, excluyendo el actual)
$sql_related = "SELECT id, nombre, precio, imagen 
                FROM productos 
                WHERE categoria_id = ? AND id != ?
                ORDER BY RAND() 
                LIMIT 3";
$stmt_related = $conn->prepare($sql_related);
$stmt_related->bind_param("ii", $categoria_id, $id);
$stmt_related->execute();
$result_related = $stmt_related->get_result();

// Nota: La conexión se cierra al final del script si se usa 'footer.php',
// pero aquí la cerramos manualmente ya que estamos fuera de la modularización.
$conn->close(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($producto['nombre']) ?> | Second Use</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand">← Volver al inicio</a>
            <span class="navbar-text me-3 text-white">Second Use</span>
        </div>
    </nav>
    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4 text-secondary">
            <span>Inicio / Productos / <?= htmlspecialchars($producto['categoria']) ?> / <?= htmlspecialchars($producto['nombre']) ?></span>
            <span><a href="#" class="text-secondary text-decoration-none"> < Previo | Próximo > </a></span>
        </div>
        
        <div class="product-card row">
            
            <div class="col-lg-5 text-center product-image">
                <img src="<?= htmlspecialchars($producto['imagen']) ?>"
                    class="img-fluid" 
                    alt="<?= htmlspecialchars($producto['nombre']) ?>">
            </div>

            <div class="col-lg-5 product-info ms-lg-auto">
                <p class="text-uppercase text-secondary mb-1">PRODUCTOS / <?= htmlspecialchars($producto['categoria']) ?></p>
                <h1 class="product-title fw-bold fs-2"><?= htmlspecialchars($producto['nombre']) ?></h1>
                <p class="product-category text-muted">SKU: 00<?= htmlspecialchars($producto['id']) ?></p>

                <div class="product-price h2 fw-bold">S/. <?= number_format($producto['precio'], 2) ?></div>
                <p class="product-stock text-success fw-bold mb-4">
                    <?php if ($producto['stock'] > 0): ?>
                        En stock: <?= htmlspecialchars($producto['stock']) ?> unidades
                    <?php else: ?>
                        Producto agotado
                    <?php endif; ?>
                </p>

                <form method="POST" action="carrito.php?agregar=<?= $producto['id'] ?>">
                    <label for="cantidad" class="form-label fw-bold">Cantidad *</label>
                    
                    <div class="quantity-selector mb-4">
                        <button type="button" onclick="document.querySelector('#qty').stepDown()">-</button>
                        <input type="number" name="cantidad" id="qty" value="1" min="1" max="<?= htmlspecialchars($producto['stock']) ?>" required class="form-control">
                        <button type="button" onclick="document.querySelector('#qty').stepUp()">+</button>
                    </div>

                    <div class="product-buttons d-grid gap-2">
                        <button type="submit" class="btn btn-lg btn-add-to-cart">
                            Agregar al carrito
                        </button>
                        <a href="finalizar_compra.php" class="btn btn-lg btn-buy-now">
                            Realizar compra
                        </a>
                    </div>
                </form>
                
                <div class="accordion mt-5" id="productAccordion">
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Información del producto
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Política de devolución y reembolso
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                <p>Detalles sobre cómo el cliente puede devolver el producto. Plazo de 30 días desde la recepción. El producto debe estar sin usar, con el empaque original intacto y presentando el comprobante de compra. El reembolso se procesará en 7 días hábiles.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Información de envío
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#productAccordion">
                            <div class="accordion-body">
                                <p>Métodos de envío disponibles: Envío Estándar: 3-5 días hábiles (S/. 15.00). Envío Express: 24 horas (S/. 30.00). Envío GRATIS en compras superiores a S/. 5,000.00.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($result_related->num_rows > 0): ?>
    <section class="related-products-section">
        <div class="container">
            <h2>Productos relacionados</h2>
            <div class="row g-4 justify-content-center">
                
                <?php while($related_item = $result_related->fetch_assoc()): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="detalle_producto.php?id=<?= htmlspecialchars($related_item['id']) ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card related-product-card h-100">
                            <img src="<?= htmlspecialchars($related_item['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($related_item['nombre']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($related_item['nombre']) ?></h5>
                                <p class="card-price">S/. <?= number_format($related_item['precio'], 2) ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endwhile; ?>

            </div>
        </div>
    </section>
    <?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>