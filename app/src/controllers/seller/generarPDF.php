<?php
session_start();
require_once('../../../vendor/fpdf/fpdf.php'); // Asegúrate de tener FPDF instalado en /libs/
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../public/index.php");
    exit;
}

$hoy = date('Y-m-d');
$stmt = $conexion->prepare("SELECT * FROM productos WHERE fecha_caducidad <= ?");
$stmt->execute([$hoy]);
$productos = $stmt->fetchAll();

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'REPORTE DE PRODUCTOS CADUCADOS',0,1,'C');

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Generado por: ' . $_SESSION['usuario'] . ' - Fecha: ' . date('d/m/Y'),0,1,'L');
$pdf->Ln(5);

// Tabla
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,10,'ID',1);
$pdf->Cell(40,10,'Nombre',1);
$pdf->Cell(30,10,'Caducidad',1);
$pdf->Cell(35,10,'Categoria',1);
$pdf->Cell(20,10,'Stock',1);
$pdf->Cell(30,10,'Precio',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);
foreach ($productos as $p) {
    $pdf->Cell(10,10,$p['id'],1);
    $pdf->Cell(40,10,utf8_decode($p['nombre']),1);
    $pdf->Cell(30,10,$p['fecha_caducidad'],1);
    $pdf->Cell(35,10,utf8_decode($p['categoria']),1);
    $pdf->Cell(20,10,$p['stock'],1);
    $pdf->Cell(30,10,'$'.number_format($p['precio'], 2),1);
    $pdf->Ln();
}

$pdf->Output('I', 'reporte_caducados.pdf');
