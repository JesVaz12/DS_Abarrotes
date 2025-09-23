<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php");
    exit;
}

// Aprobar producto o edición
if (isset($_GET['aprobar'])) {
    $id = intval($_GET['aprobar']);

    $stmt = $conexion->prepare("SELECT * FROM inventario_solicitudes WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    if ($producto) {
        if (!empty($producto['id_producto_original'])) {
            // Es una edición: actualizar producto existente
            $update = $conexion->prepare("UPDATE productos SET nombre = ?, descripcion = ?, fecha_caducidad = ?, categoria = ?, stock = ?, precio = ? WHERE id = ?");
            $update->execute([
                $producto['nombre'],
                $producto['descripcion'],
                $producto['fecha_caducidad'],
                $producto['categoria'],
                $producto['stock'],
                $producto['precio'],
                $producto['id_producto_original']
            ]);
        } else {
            // Es un producto nuevo
            $insert = $conexion->prepare("INSERT INTO productos (nombre, descripcion, fecha_caducidad, categoria, stock, precio)
                                          VALUES (?, ?, ?, ?, ?, ?)");
            $insert->execute([
                $producto['nombre'],
                $producto['descripcion'],
                $producto['fecha_caducidad'],
                $producto['categoria'],
                $producto['stock'],
                $producto['precio']
            ]);
        }

        // Eliminar solicitud aprobada
        $conexion->prepare("DELETE FROM inventario_solicitudes WHERE id = ?")->execute([$id]);
    }

    header("Location: aprobarInventario.php");
    exit;
}

// Rechazar solicitud
if (isset($_GET['rechazar'])) {
    $id = intval($_GET['rechazar']);
    $conexion->prepare("DELETE FROM inventario_solicitudes WHERE id = ?")->execute([$id]);
    header("Location: aprobarInventario.php");
    exit;
}

// Obtener solicitudes
$solicitudes = $conexion->query("SELECT * FROM inventario_solicitudes ORDER BY creado_en DESC")->fetchAll();

include('../templates/cabecera.php');
require_once 'views/views_aprobarInventario.php';
?>

