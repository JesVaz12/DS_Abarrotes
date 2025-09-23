<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../config/bd.php');
$conexionBD = BD::crearInstancia();

// Validación de rol
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php");
    exit;
}

include('../templates/cabecera.php');
require_once 'views/views_MenuAdministrador.php';
?>

