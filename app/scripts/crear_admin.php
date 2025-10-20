<?php
include('../src/controllers/config/bd.php');
$conexionBD = BD::crearInstancia();

// Eliminar si ya existe
$conexionBD->prepare("DELETE FROM usuarios WHERE username = ?")->execute(['admin']);

// Insertar de nuevo con contraseña encriptada
$username = 'admin';
$passwordPlano = 'admin123';
$password = password_hash($passwordPlano, PASSWORD_DEFAULT);
$rol = 'gerente';

$sql = "INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)";
$stmt = $conexionBD->prepare($sql);
$stmt->execute([$username, $password, $rol]);

echo "✅ Usuario admin creado con contraseña encriptada.";
