<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// ✅ Opcional: restringir solo para roles autorizados
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['gerente', 'abarrotero'])) {
    header("Location: ../../../public/index.php");
    exit;
}


// Obtener productos del inventario
$stmt = $conexion->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll();

include('../../../templates/layouts/cabecera.php');
require_once '../../../templates/admin/views_mostrarInventario.php';
?>


