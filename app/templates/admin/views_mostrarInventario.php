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
                        <tr>
                            <td colspan="7" class="text-center">No hay productos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <a href="index.php" class="btn btn-secondary mt-3">⬅ Volver al panel</a>
        </div>
    </div>

   <?php include(__DIR__ . '/../layouts/pie.php'); ?>

</body>

</html>