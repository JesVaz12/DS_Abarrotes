<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abarrotero') {
    header("Location: ../../../public/index.php");
    exit;
}



$stmt = $conexion->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll();

include('../../../templates/layouts/cabVendedor.php');
require_once '../../../templates/seller/views_mostrarInventario.php';
?>

