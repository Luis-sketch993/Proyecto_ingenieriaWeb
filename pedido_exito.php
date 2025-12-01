<?php
session_start();

// --- CORRECCIÓN ---
// Este es el lugar correcto para vaciar el carrito.
unset($_SESSION['carrito']);

$page_title = "Pedido Exitoso";
require 'conexion.php'; 
include 'header.php'; 

$pedido_id = isset($_GET['pedido']) ? intval($_GET['pedido']) : 0;
$metodo = isset($_GET['metodo']) ? htmlspecialchars($_GET['metodo']) : 'desconocido';

if ($pedido_id === 0) {
    // Si no hay ID de pedido, redirigir
    header("Location: index.php"); 
    exit();
}

$total_final = 'N/A';
$estado_pedido = 'N/A';

try {
    // Obtener datos del pedido
    $stmt = $conn->prepare("SELECT total, estado FROM pedidos WHERE id = ?");
    $stmt->bind_param("i", $pedido_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $total_final = number_format((float)$row['total'], 2);
        $estado_pedido = $row['estado'];
    } else {
        throw new Exception("Pedido no encontrado.");
    }
} catch (Exception $e) {
    error_log("Error en pedido_exito: " . $e->getMessage());
    $_SESSION['error'] = "No se pudo cargar la información de tu pedido.";
    header("Location: index.php"); 
    exit();
}

// Mensajes dinámicos
$titulo_pago = ($metodo === 'tarjeta') ? '¡Pago Aprobado!' : '¡Pedido Confirmado!';
$icono_clase = ($metodo === 'tarjeta') ? 'bi-check-circle-fill' : 'bi-patch-check-fill';
?>

<!-- 
    CSS MEJORADO: 
    Agregamos estilos directamente aquí para asegurar que la página se vea bien 
    incluso si el CSS de Bootstrap no carga desde el header.
-->
<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    font-family: "Poppins", sans-serif;
    min-height: 100vh;
}

.success-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 120px);
    padding: 2rem 1rem;
}

.success-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    border-top: 6px solid #28a745;
    padding: 3rem 2rem;
    width: 100%;
    max-width: 600px;
    text-align: center;
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.success-icon {
    font-size: 4rem;
    color: #28a745;
    margin-bottom: 1rem;
}

.success-card h1 {
    font-weight: 800;
    color: #28a745;
    margin-bottom: 1rem;
    font-size: 2rem;
}

.success-card p {
    color: #555;
    font-size: 1.05rem;
    line-height: 1.6;
}

.order-details {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 2rem;
    margin: 2rem 0;
    border-left: 4px solid #28a745;
}

.detail-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1rem;
}

.detail-row:last-child {
    margin-bottom: 0;
}

.detail-item {
    text-align: left;
}

.detail-label {
    font-size: 0.85rem;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.3rem;
    font-weight: 600;
}

.detail-value {
    font-size: 1.1rem;
    color: #333;
    font-weight: 600;
}

.detail-value.highlight {
    color: #28a745;
    font-size: 1.3rem;
}

.alert {
    border-radius: 10px;
    font-size: 0.95rem;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    margin: 1.5rem 0;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1.05rem;
    padding: 1rem 2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.success-message {
    background: linear-gradient(135deg, #e8f5f3 0%, #f0fffe 100%);
    color: #28a745;
    padding: 1rem;
    border-radius: 10px;
    margin-top: 1.5rem;
    font-weight: 600;
}

@media (max-width: 576px) {
    .success-card {
        padding: 2rem 1.5rem;
    }
    
    .success-card h1 {
        font-size: 1.6rem;
    }
    
    .detail-row {
        grid-template-columns: 1fr;
    }
}
</style>


<div class="container py-5 success-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="success-card">
                
                <div class="success-icon">
                    <i class="bi <?= $icono_clase ?>"></i>
                </div>
                
                <h1><?= $titulo_pago ?></h1>
                
                <p>
                    Tu pedido <strong>#<?= htmlspecialchars($pedido_id) ?></strong> por <strong>S/. <?= $total_final ?></strong> ha sido procesado con éxito.
                </p>
                
                <!-- Detalles del Pedido -->
                <div class="order-details">
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">💰 Total Pagado</div>
                            <div class="detail-value highlight">S/. <?= $total_final ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">💳 Método de Pago</div>
                            <div class="detail-value"><?= ucfirst($metodo) ?></div>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <div class="detail-label">📊 Estado</div>
                            <div class="detail-value"><?= htmlspecialchars($estado_pedido) ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">📧 Correo de Confirmación</div>
                            <div class="detail-value"><?= htmlspecialchars($_SESSION['email'] ?? 'N/A') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje de confirmación -->
                <div class="success-message">
                    <i class="bi bi-envelope-check me-2"></i>
                    Hemos enviado la confirmación a tu correo electrónico.
                </div>

                <!-- Información adicional -->
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>¡Tu pedido está confirmado!</strong><br>
                    Recibirás tu compra en la dirección que proporcionaste. Puedes seguimiento de tu pedido desde tu cuenta.
                </div>

                <!-- Botón para volver a la tienda -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                    <a href="index.php" class="btn btn-primary px-5">
                        <i class="bi bi-arrow-left me-2"></i>Volver a la Tienda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>