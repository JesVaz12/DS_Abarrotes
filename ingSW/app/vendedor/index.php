<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../config/bd.php');
$conexionBD = BD::crearInstancia();

// Verifica que el usuario sea abarrotero
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abarrotero') {
    header("Location: ../index.php");
    exit;
}

include('../templates/cabVendedor.php');
require_once 'views/views_MenuVendedor.php';
?>

