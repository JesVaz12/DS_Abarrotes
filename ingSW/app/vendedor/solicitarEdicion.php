<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// Asegura que solo un abarrotero pueda ejecutar esto
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abarrotero') {
    header("Location: ../index.php");
    exit;
}

// Verifica que llegaron datos del formulario
if (!isset($_POST['descripcion'], $_POST['fecha_caducidad'], $_POST['categoria'], $_POST['stock'], $_POST['precio'])) {
    header("Location: mostrarInventario.php");
    exit;
}

$descripcion = $_POST['descripcion'];
$fecha_caducidad = $_POST['fecha_caducidad'];
$categoria = $_POST['categoria'];
$stock = $_POST['stock'];
$precio = $_POST['precio'];
$usuario = $_SESSION['usuario'];

foreach ($descripcion as $id => $desc) {
    // Solo insertar si el ID es válido
    if (is_numeric($id)) {
        $stmt = $conexion->prepare("INSERT INTO inventario_solicitudes (nombre, descripcion, fecha_caducidad, categoria, stock, precio, creado_por)
            SELECT nombre, ?, ?, ?, ?, ?, ? FROM productos WHERE id = ?");
        $stmt->execute([
            $desc,
            $fecha_caducidad[$id],
            $categoria[$id],
            $stock[$id],
            $precio[$id],
            $usuario,
            $id
        ]);
    }
}

header("Location: mostrarInventario.php?msg=solicitud_enviada");
exit;
?>
