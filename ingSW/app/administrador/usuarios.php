<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php");
    exit;
}

$mensaje = '';
$modo_edicion = false;
$usuario_editado = null;

// Crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_usuario'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';

    // ...
    if ($username && $password && $rol) {
        if (strlen($username) > 50 || strlen($rol) > 20) {
            $mensaje = "⚠ El nombre de usuario o rol excede el límite permitido.";
        } elseif (strlen($password) < 8 || strlen($password) > 72) {
            $mensaje = "⚠ La contraseña debe tener entre 8 y 72 caracteres.";
        } else {
            // ...
            $verifica = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ?");
            $verifica->execute([$username]);
            if ($verifica->fetchColumn() > 0) {
                $mensaje = "⚠ El usuario ya existe.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conexion->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)");
                $stmt->execute([$username, $hash, $rol]);
                $mensaje = "✅ Usuario creado correctamente.";
            }
        }
    } else {
        $mensaje = "⚠ Completa todos los campos.";
    }
}

// Mostrar formulario de edición
if (isset($_GET['editar'])) {
    $modo_edicion = true;
    $id_editar = $_GET['editar'];
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id_editar]);
    $usuario_editado = $stmt->fetch();
}

// Guardar cambios de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $id = $_POST['id'] ?? '';
    $username = $_POST['username'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $new_password = $_POST['password'] ?? '';

    if ($id && $username && $rol) {
        if (strlen($username) > 50 || strlen($rol) > 20 || strlen($new_password) > 255) {
            $mensaje = "⚠ Algún campo excede el límite permitido.";
        } else {
            if (!empty($new_password)) {
                if (strlen($new_password) < 8 || strlen($new_password) > 72) {
                    $mensaje = "⚠ La nueva contraseña debe tener entre 8 y 72 caracteres.";
                } else {
                    $hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, rol = ?, password = ? WHERE id = ?");
                    $stmt->execute([$username, $rol, $hash, $id]);
                }
            } else {
                $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, rol = ? WHERE id = ?");
                $stmt->execute([$username, $rol, $id]);
            }
            $mensaje = "✅ Usuario actualizado correctamente.";
            header("Location: usuarios.php");
            exit;
        }
    } else {
        $mensaje = "⚠ Todos los campos son obligatorios.";
    }
}

// Eliminar usuario
//if (isset($_GET['eliminar'])) {
//  $id = $_GET['eliminar'];
// $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
// $stmt->execute([$id]);
// header("Location: usuarios.php");
// exit;
//}

// Cambiar estatus de usuario (Desactivar/Activar)
if (isset($_GET['desactivar'])) {
    $id = $_GET['desactivar'];
    // Se establece el estatus a 0 (inactivo)
    $stmt = $conexion->prepare("UPDATE usuarios SET status = 0 WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: usuarios.php");
    exit;
}

if (isset($_GET['activar'])) {
    $id = $_GET['activar'];
    // Se establece el estatus a 1 (activo)
    $stmt = $conexion->prepare("UPDATE usuarios SET status = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: usuarios.php");
    exit;
}

// Obtener usuarios
$usuarios = $conexion->query("SELECT * FROM usuarios ORDER BY id ASC")->fetchAll();

include('../templates/cabecera.php');
require_once 'views/views_usuarios.php';
