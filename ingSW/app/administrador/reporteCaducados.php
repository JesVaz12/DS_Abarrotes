<?php
session_start();
require_once('../libs/fpdf.php');
include('../config/bd.php');
$conexion = BD::crearInstancia();

// Verifica que sea gerente
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php");
    exit;
}

// Obtener productos caducados
$hoy = date('Y-m-d');
$stmt = $conexion->prepare("SELECT * FROM productos WHERE fecha_caducidad <= ?");
$stmt->execute([$hoy]);
$productos = $stmt->fetchAll();

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

// Título
$pdf->Cell(0,10,'REPORTE DE PRODUCTOS CADUCADOS',0,1,'C');

// Subtítulo
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Generado por: ' . $_SESSION['usuario'] . ' - Fecha: ' . date('d/m/Y'),0,1,'L');
$pdf->Ln(5);

// Encabezados de tabla
$pdf->SetFont('Arial','B',11);
$pdf->Cell(15,10,'ID',1);
$pdf->Cell(50,10,'Nombre',1);
$pdf->Cell(30,10,'Caducidad',1);
$pdf->Cell(40,10,'Categoria',1);
$pdf->Cell(25,10,'Stock',1);
$pdf->Cell(30,10,'Precio',1);
$pdf->Ln();

// Contenido de la tabla
$pdf->SetFont('Arial','',10);
if (count($productos) > 0) {
    foreach ($productos as $p) {
        $pdf->Cell(15,10,$p['id'],1);
        $pdf->Cell(50,10,utf8_decode($p['nombre']),1);
        $pdf->Cell(30,10,$p['fecha_caducidad'],1);
        $pdf->Cell(40,10,utf8_decode($p['categoria']),1);
        $pdf->Cell(25,10,$p['stock'],1);
        $pdf->Cell(30,10,"$".number_format($p['precio'],2),1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0,10,'No hay productos caducados.',1,1,'C');
}

// Descargar PDF
$pdf->Output('D', 'reporte_caducados.pdf');
exit;
