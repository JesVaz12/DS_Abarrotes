<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// Seguridad: Solo gerentes
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../../../public/index.php");
    exit;
}

// LÓGICA PARA RESTAURAR (ACTIVAR)
if (isset($_GET['restaurar'])) {
    $id = $_GET['restaurar'];
    // Lo volvemos a poner en status 1
    $stmt = $conexion->prepare("UPDATE usuarios SET status = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: usuarios_eliminados.php"); // Recargamos esta misma página
    exit;
}

// CONSULTA: Solo traer los INACTIVOS (status = 0)
$usuarios = $conexion->query("SELECT * FROM usuarios WHERE status = 0 ORDER BY id ASC")->fetchAll();

include('../../../templates/layouts/cabecera.php');
// Usaremos una vista nueva para esto
require_once '../../../templates/admin/views_usuarios_eliminados.php';
