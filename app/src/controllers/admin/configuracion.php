<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// Verificar rol (Solo Gerente en este archivo)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../../../public/index.php");
    exit;
}

$usuarioSesion = $_SESSION['usuario'];
$mensaje = '';

// 1. OBTENER DATOS ACTUALES (Incluyendo ID y Email)
// Usamos el username de la sesión para encontrar al usuario en la BD
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt->execute([$usuarioSesion]);
$usuario = $stmt->fetch();

// Si por alguna razón no se encuentra el usuario, cerrar sesión (Seguridad)
if (!$usuario) {
    session_destroy();
    header("Location: ../../../public/index.php");
    exit;
}

// 2. PROCESAR FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoUsuario  = trim($_POST['username'] ?? '');
    $nuevoEmail    = trim($_POST['email'] ?? ''); // <--- Nuevo Campo
    $nuevaPassword = $_POST['password'] ?? '';
    $idUsuario     = $usuario['id']; // Usamos el ID recuperado de la BD

    // Validaciones básicas
    if (empty($nuevoUsuario) || strlen($nuevoUsuario) > 50) {
        $mensaje = "⚠ El nombre de usuario no es válido.";
    } elseif (!filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "⚠ El formato del correo electrónico no es válido.";
    } else {

        // 3. VALIDAR DUPLICADOS (Usuario o Email usado por OTRO)
        // Buscamos si existe alguien con ese user O email, PERO que no sea yo (id != $idUsuario)
        $verifica = $conexion->prepare("SELECT count(*) FROM usuarios WHERE (username = ? OR email = ?) AND id != ?");
        $verifica->execute([$nuevoUsuario, $nuevoEmail, $idUsuario]);

        if ($verifica->fetchColumn() > 0) {
            $mensaje = "⚠ El nombre de usuario o el correo ya están en uso por otra persona.";
        } else {
            // 4. ACTUALIZAR DATOS
            if (!empty($nuevaPassword)) {
                if (strlen($nuevaPassword) < 8 || strlen($nuevaPassword) > 72) {
                    $mensaje = "⚠ La contraseña debe tener entre 8 y 72 caracteres.";
                } else {
                    $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
                    $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, email = ?, password = ? WHERE id = ?");
                    $stmt->execute([$nuevoUsuario, $nuevoEmail, $hash, $idUsuario]);
                    $mensaje = "✅ Datos y contraseña actualizados.";
                }
            } else {
                // Actualizar sin tocar la contraseña
                $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, email = ? WHERE id = ?");
                $stmt->execute([$nuevoUsuario, $nuevoEmail, $idUsuario]);
                $mensaje = "✅ Datos actualizados correctamente.";
            }

            // Actualizar la sesión si cambió el nombre de usuario
            if ($mensaje !== "⚠ La contraseña debe tener entre 8 y 72 caracteres.") {
                $_SESSION['usuario'] = $nuevoUsuario;
                // Refrescamos la variable local para que el formulario muestre el dato nuevo
                $usuario['username'] = $nuevoUsuario;
                $usuario['email'] = $nuevoEmail;
            }
        }
    }
}

include('../../../templates/layouts/cabecera.php');
require_once '../../../templates/admin/views_configuracion.php';
