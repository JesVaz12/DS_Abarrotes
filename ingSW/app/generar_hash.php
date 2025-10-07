<?php
/* * Este script es solo para generar un hash de contraseña.
 * ¡Bórralo de tu servidor después de usarlo!
 */

$passwordTemporal = 'admin123';
$hash = password_hash($passwordTemporal, PASSWORD_DEFAULT);

echo "Tu nueva contraseña encriptada es: <br><br>";
echo "<strong>" . htmlspecialchars($hash) . "</strong>";
echo "<br><br>Copia esta línea completa y pégala en el campo 'password' de la base de datos.";
?>