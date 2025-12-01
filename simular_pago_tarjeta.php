<?php
session_start();

// Validar que el usuario esté logueado ANTES de incluir header.php
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para realizar un pago.";
    header("Location: login.php");
    exit();
}

$page_title = "Pasarela de Pago Segura";
require 'conexion.php'; 
include 'header.php'; 

$pedido_id = isset($_GET['pedido']) ? intval($_GET['pedido']) : 0;

if ($pedido_id === 0) {
    $_SESSION['error'] = "ID de pedido no encontrado.";
    header("Location: index.php"); 
    exit();
}

$total_final = 0;
$total_en_texto = 'N/A';
try {
    $stmt = $conn->prepare("SELECT total, estado FROM pedidos WHERE id = ?");
    $stmt->bind_param("i", $pedido_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $total_final = (float)$row['total'];
        $total_en_texto = number_format($total_final, 2);

        if ($row['estado'] === 'Pagado') {
            $_SESSION['info'] = "Este pedido ya fue pagado. Dirigiéndote a la página de éxito.";
            header("Location: pedido_exito.php?pedido=" . $pedido_id . "&metodo=tarjeta"); 
            exit();
        }

    } else {
        throw new Exception("Pedido no encontrado.");
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error al cargar el total del pedido. " . $e->getMessage();
    header("Location: finalizar_compra.php"); 
    exit();
}

$error_message = '';
if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!-- 🌈 Estilos personalizados -->
<style>
/* Fondo general */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    font-family: "Poppins", sans-serif;
    min-height: 100vh;
}

/* Contenedor que centra la pasarela debajo del header */
.payment-container {
    min-height: calc(100vh - 120px);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem 1rem;
}

/* Tarjeta de pago centrada */
.payment-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    padding: 3rem 2rem;
    width: 100%;
    max-width: 450px;
    text-align: center;
    border-top: 6px solid #0d6efd;
    animation: slideIn 0.4s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.payment-card h2 {
    font-weight: 800;
    color: #0d6efd;
    margin-bottom: 0.5rem;
    font-size: 1.8rem;
}

.total-label {
    font-size: 0.95rem;
    color: #666;
    margin-bottom: 0.3rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.total-amount {
    font-size: 3rem;
    font-weight: 900;
    color: #dc3545;
    margin: 0.5rem 0 1rem 0;
}

.pedido-id {
    color: #999;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.form-control {
    border-radius: 10px;
    padding: 0.75rem 1rem;
    border: 2px solid #eee;
    font-family: "Poppins", sans-serif;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1.05rem;
    padding: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
    color: white;
}

.btn-primary:active {
    transform: translateY(0);
}

.alert {
    border-radius: 10px;
    font-size: 0.95rem;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}

.security-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #28a745;
    font-weight: 600;
    margin-bottom: 1rem;
}

@media (max-width: 576px) {
    .payment-card {
        padding: 2rem 1.5rem;
    }
    
    .total-amount {
        font-size: 2.3rem;
    }
}
</style>

<!-- 🧭 Contenedor centrado debajo del header -->
<div class="payment-container">
    <div class="payment-card">
        <h2>💳 Pasarela de Pago Segura</h2>

        <p class="total-label">Total a pagar:</p>
        <p class="total-amount">S/. <?= $total_en_texto ?></p>
        <p class="pedido-id">Pedido #<?= htmlspecialchars($pedido_id) ?></p>

        <div class="security-badge">
            <i class="bi bi-shield-check"></i> Conexión segura
        </div>

        <?php if ($error_message): ?>
            <div class="alert alert-danger mb-4">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="procesar_pago_tarjeta.php">
            <input type="hidden" name="pedido_id" value="<?= $pedido_id ?>">
            
            <div class="mb-3 text-start">
                <label for="numero_tarjeta" class="form-label">
                    <i class="bi bi-credit-card me-1"></i>Número de Tarjeta
                </label>
                <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta"
                       placeholder="1234 5678 9012 3456" required maxlength="19"
                       oninput="this.value = this.value.replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim();">
                <small class="text-muted">16 dígitos</small>
            </div>

            <div class="mb-3 text-start">
                <label for="nombre_titular" class="form-label">
                    <i class="bi bi-person me-1"></i>Nombre del Titular
                </label>
                <input type="text" class="form-control" id="nombre_titular" name="nombre_titular" 
                       placeholder="Juan Pérez" required>
            </div>

            <div class="row">
                <div class="col-6 mb-3 text-start">
                    <label for="fecha_exp" class="form-label">
                        <i class="bi bi-calendar me-1"></i>Expiración
                    </label>
                    <input type="text" class="form-control" id="fecha_exp" name="fecha_exp"
                           placeholder="12/25" required maxlength="5"
                           oninput="if (this.value.length === 2 && event.data !== '/') this.value += '/';">
                    <small class="text-muted">MM/AA</small>
                </div>
                <div class="col-6 mb-3 text-start">
                    <label for="cvv" class="form-label">
                        <i class="bi bi-lock me-1"></i>CVV
                    </label>
                    <input type="text" class="form-control" id="cvv" name="cvv"
                           placeholder="123" required maxlength="4" inputmode="numeric">
                    <small class="text-muted">3-4 dígitos</small>
                </div>
            </div>

            <div class="alert alert-info small text-start mb-3">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Modo de simulación:</strong> Utiliza cualquier tarjeta de 16 dígitos para completar la prueba.
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3 shadow-sm">
                <i class="bi bi-check-circle me-2"></i>Pagar S/. <?= $total_en_texto ?>
            </button>
        </form>
    </div>
</div>


<?php include 'footer.php'; ?>
