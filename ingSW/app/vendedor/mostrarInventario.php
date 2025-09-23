<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abarrotero') {
    header("Location: ../index.php");
    exit;
}

include('../templates/cabVendedor.php');

$stmt = $conexion->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll();

require_once 'views/views_mostrarInventario.php';
?>

