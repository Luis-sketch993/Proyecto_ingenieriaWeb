<?php
session_start();

// Validar que el usuario esté logueado ANTES de incluir header.php
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para realizar un pago.";
    header("Location: login.php");
    exit();
}

$page_title = "Pagar con Yape/Plin";
require 'conexion.php'; 
include 'header.php'; 

$pedido_id = isset($_GET['pedido']) ? intval($_GET['pedido']) : 0;

if ($pedido_id === 0) {
    $_SESSION['error'] = "ID de pedido no encontrado.";
    header("Location: index.php"); 
    exit();
}

// Variables para mostrar en la página
$total_final = 0;
$total_en_texto = 'N/A';
$estado_pedido = '';

try {
    // Obtener el total y el estado del pedido
    $stmt = $conn->prepare("SELECT total, estado FROM pedidos WHERE id = ?");
    $stmt->bind_param("i", $pedido_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $total_final = (float)$row['total'];
        $total_en_texto = number_format($total_final, 2);
        $estado_pedido = $row['estado'];

        // Si el pedido ya está pagado, redirigir a éxito
        if ($estado_pedido === 'Pagado') {
            $_SESSION['info'] = "Este pedido ya fue pagado.";
            header("Location: pedido_exito.php?pedido=" . $pedido_id . "&metodo=yape"); 
            exit();
        }

    } else {
        throw new Exception("Pedido no encontrado.");
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error al cargar los datos del pedido: " . $e->getMessage();
    header("Location: finalizar_compra.php"); 
    exit();
}
?>

<!-- Estilos para la tarjeta de pago Yape/Plin -->
<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    font-family: "Poppins", sans-serif;
    min-height: 100vh;
}

.payment-container {
    min-height: calc(100vh - 120px);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem 1rem;
}

.payment-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    padding: 3rem 2rem;
    width: 100%;
    max-width: 500px;
    text-align: center;
    border-top: 6px solid #00a09d;
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
    color: #00a09d;
    margin-bottom: 0.5rem;
    font-size: 1.8rem;
}

.total-label {
    color: #666;
    font-size: 0.95rem;
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

.qr-section-title {
    font-weight: 700;
    color: #333;
    margin: 1.5rem 0 1rem 0;
    font-size: 1.05rem;
}

.qr-container {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 2rem;
    margin: 1.5rem 0;
    border: 3px dashed #00a09d;
    position: relative;
}

.qr-code {
    width: 100%;
    max-width: 280px;
    height: auto;
    border-radius: 10px;
    margin: 0 auto;
    display: block;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.instruction-box {
    background: linear-gradient(135deg, #e8f5f3 0%, #f0fffe 100%);
    border-left: 4px solid #00a09d;
    padding: 1.2rem;
    border-radius: 8px;
    text-align: left;
    margin: 1.5rem 0;
    font-size: 0.95rem;
}

.instruction-box strong {
    display: block;
    margin-bottom: 0.8rem;
    color: #00a09d;
    font-size: 1rem;
}

.instruction-box ol {
    margin: 0;
    padding-left: 1.8rem;
    color: #555;
}

.instruction-box li {
    margin-bottom: 0.6rem;
    line-height: 1.5;
}

.alert {
    border-radius: 10px;
    font-size: 0.95rem;
    margin: 1.5rem 0;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}

.confirmation-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

.btn-confirm {
    background: linear-gradient(135deg, #00a09d 0%, #008a88 100%);
    border: none;
    color: white;
    font-weight: 700;
    padding: 1rem;
    border-radius: 10px;
    width: 100%;
    margin-top: 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 160, 157, 0.3);
}

.btn-confirm:hover {
    background: linear-gradient(135deg, #008a88 0%, #006b69 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 160, 157, 0.4);
}

.btn-confirm:active {
    transform: translateY(0);
}

@media (max-width: 576px) {
    .payment-card {
        padding: 2rem 1.5rem;
    }
    
    .total-amount {
        font-size: 2.3rem;
    }
    
    .qr-code {
        max-width: 240px;
    }
}
</style>

<div class="payment-container">
    <div class="payment-card">
        <h2>💚 Pago con Yape / Plin</h2>
        
        <p class="total-label">Total a pagar:</p>
        <p class="total-amount">S/. <?= $total_en_texto ?></p>
        <p class="pedido-id">Pedido #<?= htmlspecialchars($pedido_id) ?></p>

        <!-- Contenedor QR -->
        <p class="qr-section-title">📱 Escanea el código QR para pagar:</p>
        
        <div class="qr-container">
            <img src="image/yape2.jpg" 
                 alt="Código QR de pago Yape/Plin" class="qr-code img-fluid">
        </div>

        <!-- Instrucciones paso a paso -->
        <div class="instruction-box">
            <strong>📋 ¿Cómo pagar?</strong>
            <ol>
                <li>Abre tu app de <strong>Yape</strong> o <strong>Plin</strong> en tu celular</li>
                <li>Toca la opción <strong>Escanear</strong></li>
                <li>Apunta hacia el código QR de arriba</li>
                <li>Verifica que el monto sea de <strong>S/. <?= $total_en_texto ?></strong></li>
                <li>Confirma y completa tu pago</li>
                <li>Regresa a esta pantalla y haz clic en el botón de abajo</li>
            </ol>
        </div>

        <!-- Alerta informativa -->
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>⏱️ Importante:</strong> Tienes 10 minutos para completar tu pago. Después de pagar, haz clic en "Confirmar Pago" para completar tu compra.
        </div>

        <!-- Formulario de confirmación -->
        <div class="confirmation-section">
            <form method="POST" action="confirmar_pago_yape.php">
                <input type="hidden" name="pedido_id" value="<?= $pedido_id ?>">
                <button type="submit" class="btn btn-confirm">
                    <i class="bi bi-check-circle-fill me-2"></i>Ya pagué con Yape/Plin - Confirmar
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>