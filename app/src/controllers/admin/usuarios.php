<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// Seguridad: Solo gerentes
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../../../public/index.php");
    exit;
}

$mensaje = '';
$modo_edicion = false;
$usuario_editado = null;

// 1. CREAR USUARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_usuario'])) {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol      = $_POST['rol'] ?? '';

    if ($username && $password && $rol && $email) {
        if (strlen($username) > 50 || strlen($rol) > 20) {
            $mensaje = "⚠ El nombre de usuario excede el límite.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "⚠ Email no válido.";
        } elseif (strlen($password) < 8 || strlen($password) > 72) {
            $mensaje = "⚠ Contraseña inválida (8-72 caracteres).";
        } else {
            // Verificar duplicados
            $verifica = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ? OR email = ?");
            $verifica->execute([$username, $email]);

            if ($verifica->fetchColumn() > 0) {
                $mensaje = "⚠ Usuario o correo ya existen.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conexion->prepare("INSERT INTO usuarios (username, email, password, rol) VALUES (?, ?, ?, ?)");

                if ($stmt->execute([$username, $email, $hash, $rol])) {
                    $mensaje = "✅ Usuario creado.";
                } else {
                    $mensaje = "❌ Error en BD.";
                }
            }
        }
    } else {
        $mensaje = "⚠ Completa todos los campos.";
    }
}

// 2. PREPARAR EDICIÓN
if (isset($_GET['editar'])) {
    $modo_edicion = true;
    $id_editar = $_GET['editar'];
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id_editar]);
    $usuario_editado = $stmt->fetch();
}

// 3. GUARDAR CAMBIOS (UPDATE) - ¡AHORA CON EMAIL!
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $id = $_POST['id'] ?? '';
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? ''; // <--- Nuevo campo recibido
    $rol = $_POST['rol'] ?? '';
    $new_password = $_POST['password'] ?? '';

    if ($id && $username && $rol && $email) {
        // Validar que el email NO esté siendo usado por OTRO usuario
        // "Busca si existe alguien con este email PERO que no sea yo (id != $id)"
        $checkEmail = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $checkEmail->execute([$email, $id]);

        if ($checkEmail->fetch()) {
            $mensaje = "⚠ Ese correo ya está registrado por otro usuario.";
            // Mantenemos el modo edición para que no se pierda el formulario
            $modo_edicion = true;
            $usuario_editado = ['id' => $id, 'username' => $username, 'email' => $email, 'rol' => $rol];
        } else {
            // Procedemos a actualizar
            if (!empty($new_password)) {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, email = ?, rol = ?, password = ? WHERE id = ?");
                $stmt->execute([$username, $email, $rol, $hash, $id]);
            } else {
                $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, email = ?, rol = ? WHERE id = ?");
                $stmt->execute([$username, $email, $rol, $id]);
            }
            $mensaje = "✅ Usuario actualizado correctamente.";
            // Redirigimos para limpiar
            header("Location: usuarios.php");
            exit;
        }
    } else {
        $mensaje = "⚠ Todos los campos son obligatorios.";
    }
}

// 4. ELIMINAR (SOFT DELETE)
if (isset($_GET['desactivar'])) {
    $id = $_GET['desactivar'];
    $stmt = $conexion->prepare("UPDATE usuarios SET status = 0 WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: usuarios.php");
    exit;
}

// 5. LISTAR
$usuarios = $conexion->query("SELECT * FROM usuarios WHERE status = 1 ORDER BY id ASC")->fetchAll();

include('../../../templates/layouts/cabecera.php');
require_once '../../../templates/admin/views_usuarios.php';
