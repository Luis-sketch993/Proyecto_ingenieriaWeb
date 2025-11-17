<?php
session_start();
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_tarjeta = $_POST['numero_tarjeta'] ?? '';
    
    if (strlen(str_replace(' ', '', $numero_tarjeta)) !== 16) {
        $_SESSION['error'] = "Número de tarjeta inválido. Debe tener 16 dígitos.";
        header("Location: simular_pago_tarjeta.php?pedido=" . $pedido_id);
        exit();
    }
    
    try {
        $conn->begin_transaction();
        $estado_pagado = 'Pagado';
        $stmt_update = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        $stmt_update->bind_param("si", $estado_pagado, $pedido_id);
        
        if (!$stmt_update->execute()) {
             throw new Exception("Error al actualizar el estado del pedido a 'Pagado'.");
        }
        
        $conn->commit();
        header("Location: pedido_exito.php?pedido=" . $pedido_id . "&metodo=tarjeta");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error simulado al procesar el pago: " . $e->getMessage();
        header("Location: simular_pago_tarjeta.php?pedido=" . $pedido_id);
        exit();
    }
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
    background: #f4f7ff;
    font-family: "Poppins", sans-serif;
}

/* Contenedor que centra la pasarela debajo del header */
.payment-container {
    min-height: calc(100vh - 120px); /* resta la altura del header */
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
}

/* Tarjeta de pago centrada */
.payment-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    width: 100%;
    max-width: 420px;
    text-align: center;
    border-top: 5px solid #0d6efd;
}

.payment-card h2 {
    font-weight: 700;
    color: #0d6efd;
    margin-bottom: 1rem;
}

.total-label {
    font-size: 1.1rem;
    color: #333;
}

.total-amount {
    font-size: 2.3rem;
    font-weight: 800;
    color: #dc3545;
    margin: 0.5rem 0;
}

.form-control {
    border-radius: 10px;
    padding: 0.6rem 1rem;
}

.btn-primary {
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    padding: 0.8rem;
}

.alert {
    border-radius: 10px;
    font-size: 0.95rem;
}
</style>

<!-- 🧭 Contenedor centrado debajo del header -->
<div class="payment-container">
    <div class="payment-card">
        <h2>Pasarela de Pago</h2>

        <p class="total-label">Total a pagar:</p>
        <p class="total-amount">S/. <?= $total_en_texto ?></p>
        <p class="text-muted mb-4">Pedido #<?= htmlspecialchars($pedido_id) ?></p>

        <?php if ($error_message): ?>
            <div class="alert alert-danger mb-4"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST" action="simular_pago_tarjeta.php?pedido=<?= $pedido_id ?>">
            <div class="mb-3 text-start">
                <label for="numero_tarjeta" class="form-label">Número de Tarjeta</label>
                <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta"
                       placeholder="XXXX XXXX XXXX XXXX" required maxlength="19"
                       oninput="this.value = this.value.replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim();">
            </div>

            <div class="mb-3 text-start">
                <label for="nombre_titular" class="form-label">Nombre del Titular</label>
                <input type="text" class="form-control" id="nombre_titular" name="nombre_titular" required>
            </div>

            <div class="row">
                <div class="col-6 mb-3 text-start">
                    <label for="fecha_exp" class="form-label">Fecha Exp.</label>
                    <input type="text" class="form-control" id="fecha_exp" name="fecha_exp"
                           placeholder="MM/AA" required maxlength="5"
                           oninput="if (this.value.length === 2 && event.data !== '/') this.value += '/';">
                </div>
                <div class="col-6 mb-3 text-start">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv"
                           placeholder="123" required maxlength="4">
                </div>
            </div>

            <div class="alert alert-info small text-start">
                🔒 <strong>Simulación:</strong> cualquier tarjeta de 16 dígitos será procesada como exitosa.
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3 shadow-sm">
                Pagar S/. <?= $total_en_texto ?>
            </button>
        </form>
    </div>
</div>


<?php include 'footer.php'; ?>
