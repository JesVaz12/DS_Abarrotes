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
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Dar de Baja Producto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .card-panel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
        }
        .btn-baja {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
        }
        .btn-baja:hover {
            background-color: #b02a37;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="card-panel">
        <h2>Dar de Baja Productos</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Caducidad</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= $producto['id'] ?></td>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td><?= $producto['fecha_caducidad'] ?></td>
                            <td><?= $producto['categoria'] ?? '-' ?></td>
                            <td><?= $producto['stock'] ?? '0' ?></td>
                            <td>
                                <a href="bajaProducto.php?id=<?= $producto['id'] ?>" class="btn btn-baja" onclick="return confirm('¿Estás seguro de dar de baja este producto?')">Dar de baja</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No hay productos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-secondary mt-3">⬅ Volver al panel</a>
    </div>
</div>

<?php include('../templates/pie.php'); ?>
</body>
</html>
