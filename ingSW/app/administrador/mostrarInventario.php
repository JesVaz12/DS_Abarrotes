<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// ✅ Opcional: restringir solo para roles autorizados
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['gerente', 'abarrotero'])) {
    header("Location: ../index.php");
    exit;
}

include('../templates/cabecera.php');

// Obtener productos del inventario
$stmt = $conexion->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inventario Completo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .card-panel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-top: 40px;
        }
        h2 {
            background: linear-gradient(135deg, #6a5d7b 0%, #a497bf 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 25px;
        }
        .table td, .table th {
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card-panel">
        <h2>Inventario General</h2>

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
                <?php if (count($productos) > 0): ?>
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
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No hay productos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-secondary mt-3">⬅ Volver al panel</a>
    </div>
</div>

<?php include('../templates/pie.php'); ?>
</body>
</html>
