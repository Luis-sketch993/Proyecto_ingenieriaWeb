<?php
session_start();

// ✅ Si el usuario ya inició sesión, lo mandamos directamente al "index.php" (o al index principal)
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión | Second Use</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="login.css">
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
      <h3 class="text-center mb-4">Iniciar Sesión</h3>

       <!-- MENSAJE DE ERROR -->
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center">
          <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

     
      <form action="validar_login.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <p class="mt-3 text-center">¿No tienes cuenta? <a href="register.php">Regístrate</a></p>

        <button type="submit" class="btn btn-dark w-100">Entrar</button>
      </form>
    </div>
  </div>

</body>
</html>
