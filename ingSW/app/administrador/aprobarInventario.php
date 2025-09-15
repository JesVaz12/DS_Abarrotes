<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php");
    exit;
}


// Aprobar producto o edición
if (isset($_GET['aprobar'])) {
    $id = intval($_GET['aprobar']);

    $stmt = $conexion->prepare("SELECT * FROM inventario_solicitudes WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    if ($producto) {
        if (!empty($producto['id_producto_original'])) {
            // Es una edición: actualizar producto existente
            $update = $conexion->prepare("UPDATE productos SET nombre = ?, descripcion = ?, fecha_caducidad = ?, categoria = ?, stock = ?, precio = ? WHERE id = ?");
            $update->execute([
                $producto['nombre'],
                $producto['descripcion'],
                $producto['fecha_caducidad'],
                $producto['categoria'],
                $producto['stock'],
                $producto['precio'],
                $producto['id_producto_original']
            ]);
        } else {
            // Es un producto nuevo
            $insert = $conexion->prepare("INSERT INTO productos (nombre, descripcion, fecha_caducidad, categoria, stock, precio)
                                          VALUES (?, ?, ?, ?, ?, ?)");
            $insert->execute([
                $producto['nombre'],
                $producto['descripcion'],
                $producto['fecha_caducidad'],
                $producto['categoria'],
                $producto['stock'],
                $producto['precio']
            ]);
        }

        // Eliminar solicitud aprobada
        $conexion->prepare("DELETE FROM inventario_solicitudes WHERE id = ?")->execute([$id]);
    }

    header("Location: aprobarInventario.php");
    exit;
}

// Rechazar solicitud
if (isset($_GET['rechazar'])) {
    $id = intval($_GET['rechazar']);
    $conexion->prepare("DELETE FROM inventario_solicitudes WHERE id = ?")->execute([$id]);
    header("Location: aprobarInventario.php");
    exit;
}

// Obtener solicitudes
$solicitudes = $conexion->query("SELECT * FROM inventario_solicitudes ORDER BY creado_en DESC")->fetchAll();

include('../templates/cabecera.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Aprobar Inventario</title>
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
            text-align: center;
        }
        .btn-aprobar {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
        }
        .btn-aprobar:hover {
            background-color: #218838;
        }
        .btn-rechazar {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
        }
        .btn-rechazar:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card-panel">
        <h2>Solicitudes de Inventario</h2>

        <?php if (count($solicitudes) > 0): ?>
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
                    <th>Tipo</th>
                    <th>Propuesto por</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= htmlspecialchars($s['nombre']) ?></td>
                    <td><?= htmlspecialchars($s['descripcion']) ?></td>
                    <td><?= $s['fecha_caducidad'] ?></td>
                    <td><?= htmlspecialchars($s['categoria']) ?></td>
                    <td><?= $s['stock'] ?></td>
                    <td>$<?= number_format($s['precio'], 2) ?></td>
                    <td><?= empty($s['id_producto_original']) ? 'Nuevo' : 'Edición' ?></td>
                    <td><?= htmlspecialchars($s['creado_por']) ?></td>
                    <td>
                        <a href="aprobarInventario.php?aprobar=<?= $s['id'] ?>" class="btn btn-aprobar btn-sm">✅ Aprobar</a>
                        <a href="aprobarInventario.php?rechazar=<?= $s['id'] ?>" class="btn btn-rechazar btn-sm" onclick="return confirm('¿Rechazar esta solicitud?')">❌ Rechazar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="text-center">No hay solicitudes pendientes.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary mt-3">⬅ Volver al panel</a>
    </div>
</div>

<?php include('../templates/pie.php'); ?>
</body>
</html>
