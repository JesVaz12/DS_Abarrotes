<?php
session_start();

// Limpia todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Redirige a la página de login (la ruta correcta)
header("Location: /public/index.php");
exit;
?>