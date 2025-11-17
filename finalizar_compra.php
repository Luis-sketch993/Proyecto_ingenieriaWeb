<?php
session_start();
// Define el título de la página antes de incluir el header
$page_title = "Finalizar Compra";
include 'header.php'; // Incluimos el header modularizado

// Si el carrito está vacío, redirigir
if (empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit();
}

$carrito = $_SESSION['carrito'];
$subtotal = 0;
foreach ($carrito as $producto) {
    $subtotal += $producto['precio'] * $producto['cantidad'];
}

$costo_envio = 15.00; 
$total_final = $subtotal + $costo_envio;
?>

<div class="container checkout-container">
    <h1 class="text-center mb-5 fw-bold">Finalizar Compra</h1>

    <div class="row">
        
        <div class="col-lg-7 order-md-1">
            <div class="checkout-card">
                <h3 class="mb-4">Información de Envío</h3>
                <!-- El formulario apunta a procesar_pedido.php -->
                <form action="procesar_pedido.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required 
                               value="<?= htmlspecialchars($_SESSION['usuario'] ?? '') ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" required>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección de entrega</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3" required></textarea>
                    </div>

                    <!-- SECCIÓN DE MÉTODO DE PAGO -->
                    <h3 class="mt-5 mb-4">Método de Pago</h3>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="pago_pasarela" value="pasarela" checked>
                            <label class="form-check-label" for="pago_pasarela">
                                Tarjeta de Crédito / Débito (Simulación de Pasarela)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="pago_yape" value="yape">
                            <label class="form-check-label" for="pago_yape">
                                Pago con Yape / Plin (Confirmación Manual)
                            </label>
                        </div>
                    </div>
                    <!-- FIN SECCIÓN DE PAGO -->

                    <button type="submit" class="btn btn-finalize btn-lg w-100 mt-4">
                        Pagar y Confirmar S/. <?= number_format($total_final, 2) ?>
                    </button>
                    
                </form>
            </div>
        </div>

        <div class="col-lg-5 order-md-2">
            <div class="checkout-card">
                <h3 class="mb-4 text-center">Tu Pedido</h3>
                
                <div class="order-summary-card">
                    
                    <?php foreach ($carrito as $producto): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small"><?= htmlspecialchars($producto['nombre']) ?> (x<?= $producto['cantidad'] ?>)</span>
                        <span class="fw-bold small">S/. <?= number_format($producto['precio'] * $producto['cantidad'], 2) ?></span>
                    </div>
                    <?php endforeach; ?>
                    
                    <hr class="my-3">
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold">S/. <?= number_format($subtotal, 2) ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Costo de envío</span>
                        <span class="text-success">S/. <?= number_format($costo_envio, 2) ?></span>
                    </div>

                    <div class="d-flex justify-content-between mt-4 fw-bold fs-4 text-dark">
                        <span>Total a pagar</span>
                        <span>S/. <?= number_format($total_final, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; // Incluimos el footer modularizado ?>