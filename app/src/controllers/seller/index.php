<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../config/bd.php');
$conexionBD = BD::crearInstancia();

// Verifica que el usuario sea abarrotero
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abarrotero') {
    header("Location: ../../../public/index.php");
    exit;
}

include('../../../templates/layouts/cabVendedor.php');
require_once '../../../templates/seller/views_MenuVendedor.php';
?>

