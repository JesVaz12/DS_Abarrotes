<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// Verificar rol
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../../../public/index.php");
    exit;
}

$usuarioSesion = $_SESSION['usuario'];
$mensaje = '';

// Obtener datos actuales
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt->execute([$usuarioSesion]);
$usuario = $stmt->fetch();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoUsuario = trim($_POST['username'] ?? '');
    $nuevaPassword = $_POST['password'] ?? '';

    // ...
    if ($nuevoUsuario && strlen($nuevoUsuario) <= 50) {
        if ($nuevaPassword) {
            if (strlen($nuevaPassword) < 8 || strlen($nuevaPassword) > 72) {
                $mensaje = "⚠ La contraseña debe tener entre 8 y 72 caracteres.";
            } else {
                $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
                $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, password = ? WHERE username = ?");
                $stmt->execute([$nuevoUsuario, $hash, $usuarioSesion]);
                $mensaje = "✅ Datos actualizados correctamente.";
            }
        } else {
            // ...
            $stmt = $conexion->prepare("UPDATE usuarios SET username = ? WHERE username = ?");
            $stmt->execute([$nuevoUsuario, $usuarioSesion]);
        }

        $_SESSION['usuario'] = $nuevoUsuario;
        $usuarioSesion = $nuevoUsuario;
        $mensaje = "✅ Datos actualizados correctamente.";
    } else {
        $mensaje = "⚠ El nombre de usuario no puede estar vacío ni exceder los 50 caracteres.";
    }
}

include('../../../templates/layouts/cabecera.php');
require_once '../../../templates/admin/views_configuracion.php';
