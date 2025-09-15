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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Productos Caducados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <style>
        .card-panel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-top: 40px;
        }
        h2 {
            background: linear-gradient(135deg, #6a5d7b, #a497bf);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 25px;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="card-panel">
        <h2>Reporte de Productos Caducados</h2>

        <?php if (count($productos) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Caducidad</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= htmlspecialchars($p['descripcion']) ?></td>
                    <td><?= $p['fecha_caducidad'] ?></td>
                    <td><?= htmlspecialchars($p['categoria']) ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td>$<?= number_format($p['precio'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="text-center">No hay productos caducados hasta la fecha.</p>
        <?php endif; ?>
            <div class="text-center mt-3">
    <a href="generarPDF.php" class="btn btn-danger me-2" target="_blank">📄 Generar PDF</a>
    <a href="index.php" class="btn btn-secondary">⬅ Volver al panel</a>
</div>

    </div>
</div>

<?php
include($_SESSION['rol'] === 'gerente' ? '../templates/pie.php' : '../templates/pieVendedor.php');
?>
</body>
</html>
