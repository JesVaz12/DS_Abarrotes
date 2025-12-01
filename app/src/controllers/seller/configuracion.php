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

// 1. Obtener datos actuales del usuario (incluyendo ID y Email)
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt->execute([$usuarioSesion]);
$usuario = $stmt->fetch();

// Seguridad: Si no se encuentra, cerrar sesión
if (!$usuario) {
    session_destroy();
    header("Location: ../../../public/index.php");
    exit;
}

// 2. Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoUsuario  = trim($_POST['username'] ?? '');
    $nuevoEmail    = trim($_POST['email'] ?? ''); // <--- Nuevo Campo
    $nuevaPassword = $_POST['password'] ?? '';
    $idUsuario     = $usuario['id'];

    // Validaciones
    if (empty($nuevoUsuario) || strlen($nuevoUsuario) > 50) {
        $mensaje = "⚠ El nombre de usuario no es válido.";
    } elseif (!filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "⚠ El formato del correo electrónico no es válido.";
    } else {

        // 3. VALIDAR DUPLICADOS
        // Verificar que el usuario o email no lo tenga OTRA persona (id != mi_id)
        $verifica = $conexion->prepare("SELECT count(*) FROM usuarios WHERE (username = ? OR email = ?) AND id != ?");
        $verifica->execute([$nuevoUsuario, $nuevoEmail, $idUsuario]);

        if ($verifica->fetchColumn() > 0) {
            $mensaje = "⚠ El nombre de usuario o el correo ya están ocupados por alguien más.";
        } else {
            // 4. ACTUALIZAR DATOS
            if (!empty($nuevaPassword)) {
                // Con cambio de contraseña
                $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
                $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([$nuevoUsuario, $nuevoEmail, $hash, $idUsuario]);
            } else {
                // Sin cambio de contraseña
                $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, email = ? WHERE id = ?");
                $stmt->execute([$nuevoUsuario, $nuevoEmail, $idUsuario]);
            }

            // Actualizar variables de sesión y vista
            $_SESSION['usuario'] = $nuevoUsuario;
            $usuario['username'] = $nuevoUsuario;
            $usuario['email']    = $nuevoEmail; // Actualizamos para que se vea en el input

            $mensaje = "✅ Datos actualizados correctamente.";
        }
    }
}

include('../../../templates/layouts/cabVendedor.php');
require_once '../../../templates/seller/views_configuracion.php';
