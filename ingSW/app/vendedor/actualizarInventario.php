<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abarrotero') {
    header("Location: ../index.php");
    exit;
}

$descripcion = $_POST['descripcion'] ?? [];
$precio = $_POST['precio'] ?? [];
$baja = $_POST['baja'] ?? [];

foreach ($descripcion as $id => $desc) {
    $nuevoPrecio = $precio[$id] ?? 0;

    if (in_array($id, $baja)) {
        // Dar de baja
        $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        // Actualizar descripción y precio
        $stmt = $conexion->prepare("UPDATE productos SET descripcion = ?, precio = ? WHERE id = ?");
        $stmt->execute([$desc, $nuevoPrecio, $id]);
    }
}

header("Location: index.php");
exit;
