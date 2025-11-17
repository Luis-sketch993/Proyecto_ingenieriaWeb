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
    background-color: #f4f7ff; /* Un fondo suave */
    font-family: "Poppins", sans-serif;
}
.success-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 120px); /* Ajusta si tu header es diferente */
    padding: 2rem;
}
.success-card {
    background: #fff;
    border-radius: 20px; /* Bordes más redondeados */
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    border-top: 5px solid #198754; /* Borde superior verde */
    padding: 2.5rem; /* Más espacio interno */
    width: 100%;
    max-width: 800px; /* Ancho máximo para la tarjeta */
    text-align: center;
}
.success-card .display-1 {
    font-size: 5rem; /* Icono grande */
}
.success-card h1 {
    font-weight: 700; /* Título más grueso */
    color: #198754; /* Color verde */
}
.success-card .fs-5 {
    font-size: 1.15rem; /* Texto de párrafo más legible */
    color: #555;
}
.small-info-box {
    background-color: #f8f9fa; /* Fondo ligero para los detalles */
    border-radius: 10px;
    padding: 1.5rem;
    text-align: left; /* Alineado a la izquierda dentro de la caja */
}
.small-info-box p {
    margin-bottom: 0.5rem;
}
.small-info-box .fw-bold {
    color: #333;
}
/* Estilos para forzar el layout de Bootstrap en caso de que falle */
.container {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.justify-content-center {
    justify-content: center !important;
}
/* Definimos col-lg-8 para que funcione */
@media (min-width: 992px) {
    .col-lg-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
    }
}
.btn-primary {
    font-weight: 600;
    font-size: 1.1rem;
    padding: 0.8rem 1.5rem;
    border-radius: 10px;
}
</style>


<div class="container py-5 success-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- 
                HTML MEJORADO: 
                - Se usa 'rounded-4' (Bootstrap 5) en lugar de 'rounded-xl'
                - Se usa <strong> para la negrita
            -->
            <div class="card text-center shadow-lg p-md-5 p-4 border-success border-3 rounded-4 success-card">
                
                <i class="bi <?= $icono_clase ?> text-success display-1 mb-4"></i>
                <h1 class="fw-bolder mb-3 text-success"><?= $titulo_pago ?></h1>
                
                <p class="fs-5 mb-4">
                    Tu pedido <strong>#<?= htmlspecialchars($pedido_id) ?></strong> por <strong>S/. <?= $total_final ?></strong> ha sido procesado con éxito.
                </p>
                
                <hr class="my-4">

                <div class="row text-start small-info-box">
                    <div class="col-md-6 mb-3">
                        <p class="fw-bold mb-1">Total Pagado:</p>
                        <p class="mb-0 text-success fs-4 fw-bold">S/. <?= $total_final ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="fw-bold mb-1">Método de Pago:</p>
                        <p class="mb-0 fs-5"><?= ucfirst($metodo) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="fw-bold mb-1">Estado del Pedido:</p>
                        <p class="mb-0 text-primary fs-5 fw-bold"><?= htmlspecialchars($estado_pedido) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="fw-bold mb-1">Recibirás un correo a:</p>
                        <p class="mb-0 fs-5"><?= htmlspecialchars($_SESSION['email'] ?? 'N/A') ?></p>
                    </div>
                </div>

                <div class="alert alert-info mt-4">
                    📦 Recibirás tu pedido en la dirección proporcionada. ¡Gracias por tu compra!
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                    <a href="index.php" class="btn btn-primary btn-lg px-4 shadow">Volver a la Tienda</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>