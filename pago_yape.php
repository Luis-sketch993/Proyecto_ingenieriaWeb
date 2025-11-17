<?php
session_start();
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

<!-- Estilos para la tarjeta de pago (similar a la de tarjeta) -->
<style>
body {
    background: #f4f7ff;
    font-family: "Poppins", sans-serif;
}
.payment-container {
    min-height: calc(100vh - 120px); /* resta la altura del header */
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
}
.payment-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    width: 100%;
    max-width: 450px;
    text-align: center;
    border-top: 5px solid #00a09d; /* Color tipo Yape/Plin */
}
.payment-card h2 {
    font-weight: 700;
    color: #00a09d;
    margin-bottom: 1rem;
}
.total-amount {
    font-size: 2.3rem;
    font-weight: 800;
    color: #dc3545;
    margin: 0.5rem 0;
}
.qr-code {
    width: 100%;
    max-width: 280px;
    height: auto;
    border: 2px solid #eee;
    border-radius: 10px;
    margin: 1.5rem auto;
}
</style>

<div class="payment-container">
    <div class="payment-card">
        <h2>Pagar con Yape / Plin</h2>
        
        <p class="total-label">Total a pagar:</p>
        <p class="total-amount">S/. <?= $total_en_texto ?></p>
        <p class="text-muted mb-3">Pedido #<?= htmlspecialchars($pedido_id) ?></p>

        <p class="fw-bold">Escanea el código QR para pagar:</p>
        
        <!-- 
            ¡IMPORTANTE! 
            Debes reemplazar 'URL_DE_TU_IMAGEN_QR' por la URL real de tu imagen QR de Yape o Plin.
            Puedes subirla a tu carpeta 'image' y enlazarla.
        -->
        <img src="image/yape2.jpg" 
             alt="Código QR de pago" class="qr-code img-fluid">

        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle-fill"></i>
            Después de pagar, haz clic en el botón de abajo para confirmar tu pedido.
        </div>

        <!-- 
            Este formulario enviará a una NUEVA página 'confirmar_pago_yape.php' 
            que debes crear para cambiar el estado del pedido a 'Pagado'.
        -->
        <form method="POST" action="confirmar_pago_yape.php">
            <input type="hidden" name="pedido_id" value="<?= $pedido_id ?>">
            <button type: "submit" class="btn btn-primary w-100 mt-3 shadow-sm" style="background-color: #00a09d; border-color: #00a09d;">
                <i class="bi bi-check-circle-fill"></i> Ya pagué, confirmar pedido
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>