<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../public/index.php");
    exit;
}

$usuarioSesion = $_SESSION['usuario'];
$mensaje = '';

// Obtener datos actuales del usuario
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt->execute([$usuarioSesion]);
$usuario = $stmt->fetch();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoUsuario = trim($_POST['username'] ?? '');
    $nuevaPassword = $_POST['password'] ?? '';

    if ($nuevoUsuario && strlen($nuevoUsuario) <= 50) {
        if ($nuevaPassword) {
            $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, password = ? WHERE username = ?");
            $stmt->execute([$nuevoUsuario, $hash, $usuarioSesion]);
        } else {
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

include('../../../templates/layouts/cabVendedor.php');
require_once '../../../templates/seller/views_configuracion.php';
?>

