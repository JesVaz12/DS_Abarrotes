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
        // RUTA PHP CORREGIDA (usando __DIR__ para ser robusta)
        include(__DIR__ . ($_SESSION['rol'] === 'gerente' ? '/../layouts/pie.php' : '/../layouts/pieVendedor.php'));
        ?>
    </body>

</html>