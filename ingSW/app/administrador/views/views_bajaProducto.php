<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel del Administrador</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/estilos-globales.css">
    <link rel="stylesheet" href="../css/estilos-panel.css">

</head>
<body>

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
