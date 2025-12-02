<?php
session_start();
require 'conexion.php';

// Si el usuario no está logueado, redirigir a login ANTES de incluir header.php
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario'])) {
    $_SESSION['error'] = "Debes iniciar sesión para finalizar tu compra.";
    header("Location: login.php");
    exit();
}

// Si el carrito está vacío, redirigir
if (empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit();
}

// Define el título de la página antes de incluir el header
$page_title = "Finalizar Compra";

// Calcular totales ANTES de incluir header
$carrito = $_SESSION['carrito'];
$subtotal = 0;
foreach ($carrito as $producto) {
    $subtotal += $producto['precio'] * $producto['cantidad'];
}

$costo_envio = 15.00; 
$total_final = $subtotal + $costo_envio;

// AHORA incluimos el header
include 'header.php';
?>

<div class="container checkout-container">
    <h1 class="text-center mb-5 fw-bold">Finalizar Compra</h1>

    <div class="row">
        
        <div class="col-lg-7 order-md-1">
            <div class="checkout-card">
                <h3 class="mb-4">Información de Envío</h3>
                
                <form id="checkout-form-main" method="POST" onsubmit="return false;">
                    
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
                        <div class="form-check payment-option">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="pago_pasarela" value="pasarela" checked onchange="updatePaymentUI()">
                            <label class="form-check-label" for="pago_pasarela">
                                <i class="bi bi-credit-card"></i> Tarjeta de Crédito / Débito (Simulación de Pasarela)
                            </label>
                        </div>
                        <div class="form-check payment-option">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="pago_yape" value="yape" onchange="updatePaymentUI()">
                            <label class="form-check-label" for="pago_yape">
                                <i class="bi bi-qr-code"></i> Pago con Yape / Plin (Confirmación Manual)
                            </label>
                        </div>
                    </div>

                    <!-- Información dinámica según método seleccionado -->
                    <div id="payment-info" class="alert alert-info mb-3" role="alert">
                        <i class="bi bi-info-circle-fill"></i> 
                        <span id="payment-message">Se te redirigirá a la pasarela de pago segura para completar tu compra.</span>
                    </div>

                    <!-- Botón que redirige directamente según el método -->
                    <button type="button" class="btn btn-finalize btn-lg w-100 mt-4" id="btn-pagar" onclick="redirectToPayment()">
                        <i class="bi bi-lock-fill"></i> Pagar y Confirmar S/. <?= number_format($total_final, 2) ?>
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

<style>
.payment-option {
    padding: 1rem;
    margin-bottom: 0.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-option:hover {
    border-color: #667eea;
    background-color: #f8f9ff;
}

.payment-option input:checked + label {
    color: #667eea;
    font-weight: 600;
}

.payment-option input:checked {
    border-color: #667eea;
}

.payment-option label {
    margin-bottom: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.payment-option i {
    font-size: 1.2rem;
}

#payment-info {
    border-left: 4px solid #0d6efd;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.btn-finalize {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-finalize:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-finalize:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>

<script>
function updatePaymentUI() {
    const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
    const messageEl = document.getElementById('payment-message');
    const btnPagar = document.getElementById('btn-pagar');
    
    if (metodoPago === 'pasarela') {
        messageEl.textContent = '💳 Se te redirigirá a la pasarela de pago segura para completar tu compra con tarjeta.';
        btnPagar.innerHTML = '<i class="bi bi-credit-card-fill"></i> Pagar con Tarjeta S/. <?= number_format($total_final, 2) ?>';
    } else if (metodoPago === 'yape') {
        messageEl.textContent = '💚 Se te redirigirá para escanear el código QR de Yape/Plin y completar tu pago.';
        btnPagar.innerHTML = '<i class="bi bi-qr-code"></i> Pagar con Yape/Plin S/. <?= number_format($total_final, 2) ?>';
    }
}

function redirectToPayment() {
    const telefono = document.getElementById('telefono').value.trim();
    const direccion = document.getElementById('direccion').value.trim();
    const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
    
    if (!telefono) {
        alert('⚠️ Por favor, ingresa tu número de teléfono.');
        document.getElementById('telefono').focus();
        return;
    }
    
    if (!direccion) {
        alert('⚠️ Por favor, ingresa tu dirección de entrega.');
        document.getElementById('direccion').focus();
        return;
    }
    
    const btnPagar = document.getElementById('btn-pagar');
    const btnText = btnPagar.innerHTML;
    btnPagar.innerHTML = '<i class="bi bi-hourglass-split"></i> Procesando...';
    btnPagar.disabled = true;
    
    const formData = new FormData();
    formData.append('telefono', telefono);
    formData.append('direccion', direccion);
    formData.append('metodo_pago', metodoPago);
    
    fetch('procesar_pedido.php', {
        method: 'POST',
        body: formData
    })
    // 1. Leer la respuesta como JSON
    .then(response => {
        // Manejar errores de respuesta HTTP (ej: 500)
        if (!response.ok) {
            // Intentar leer el texto si el estatus no fue OK, aunque esperamos JSON
            return response.text().then(text => {
                throw new Error('Error de servidor (' + response.status + '): ' + text.substring(0, 100) + '...');
            });
        }
        return response.json(); 
    })
    .then(data => {
        // 2. Comprobar el estado devuelto por el JSON
        if (data.status === 'success') {
            // ÉXITO: Redireccionar el navegador manualmente
            window.location.href = data.redirect_url;
        } else {
            // FALLO: Mostrar el mensaje de error del JSON (data.message)
            alert('❌ Error al procesar: ' + (data.message || 'Error desconocido del servidor.'));
            btnPagar.innerHTML = btnText;
            btnPagar.disabled = false;
        }
    })
    .catch(error => {
        // 3. Manejo de errores de red o parseo de JSON
        console.error('Fetch Error:', error);
        alert('❌ Error grave de conexión o formato de respuesta: ' + error.message);
        btnPagar.innerHTML = btnText;
        btnPagar.disabled = false;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    updatePaymentUI();
});
</script>

<?php include 'footer.php'; ?>