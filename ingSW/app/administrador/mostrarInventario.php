<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// ✅ Opcional: restringir solo para roles autorizados
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['gerente', 'abarrotero'])) {
    header("Location: ../index.php");
    exit;
}

include('../templates/cabecera.php');

// Obtener productos del inventario
$stmt = $conexion->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll();

require_once 'views/views_mostrarInventario.php';
?>


