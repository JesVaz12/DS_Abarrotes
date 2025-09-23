<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php");
    exit;
}

// Dar de baja producto por ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: bajaProducto.php");
    exit;
}

// Obtener productos existentes
$stmt = $conexion->query("SELECT * FROM productos ORDER BY id ASC");
$productos = $stmt->fetchAll();

include('../templates/cabecera.php');
require_once 'views/views_bajaProducto.php';
?>

