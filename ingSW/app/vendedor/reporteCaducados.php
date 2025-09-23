<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// Verifica que sea vendedor o gerente
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['abarrotero', 'gerente'])) {
    header("Location: ../index.php");
    exit;
}

include($_SESSION['rol'] === 'gerente' ? '../templates/cabecera.php' : '../templates/cabVendedor.php');

$hoy = date('Y-m-d');
$stmt = $conexion->prepare("SELECT * FROM productos WHERE fecha_caducidad <= ?");
$stmt->execute([$hoy]);
$productos = $stmt->fetchAll();

require_once 'views/views_reporteCaducados.php';
?>


