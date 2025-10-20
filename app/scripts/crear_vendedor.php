<?php
include('../src/controllers/config/bd.php');
$conexion = BD::crearInstancia();

$username = 'vendedor';
$passwordPlano = 'abarrotes123';
$rol = 'abarrotero';

$passwordEncriptada = password_hash($passwordPlano, PASSWORD_DEFAULT);

// Borra si ya existe
$conexion->prepare("DELETE FROM usuarios WHERE username = ?")->execute([$username]);

// Inserta el nuevo vendedor
$stmt = $conexion->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)");
$stmt->execute([$username, $passwordEncriptada, $rol]);

echo "✅ Usuario vendedor creado correctamente con contraseña encriptada.";
?>
