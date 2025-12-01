<?php
session_start();
session_destroy();
header("Location: login.php?success=Sesión cerrada correctamente");
exit();
?>
