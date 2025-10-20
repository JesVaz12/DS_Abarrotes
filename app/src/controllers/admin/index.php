<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../config/bd.php');
$conexionBD = BD::crearInstancia();

// Validación de rol
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../../../public/index.php");
    exit;
}

include('../../../templates/layouts/cabecera.php');
// ESTO ES LO CORRECTO
require_once '../../../templates/admin/views_MenuAdministrador.php';
?>

