<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel del Administrador</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../../../public/css/estilos-globales.css">
    <link rel="stylesheet" href="../../../public/css/estilos-panel.css">

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

    <?php include(__DIR__ . '/../layouts/pie.php'); ?>

</body>

</html>