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
    
<div class="container">
    <div class="card-panel">
        <h2>Editar Inventario (Solicitud)</h2>

        <form method="post" action="solicitarEdicion.php">
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
                            <td><input type="text" name="descripcion[<?= $p['id'] ?>]" value="<?= htmlspecialchars($p['descripcion']) ?>" class="form-control" maxlength="255"></td>
                            <td><input type="date" name="fecha_caducidad[<?= $p['id'] ?>]" value="<?= $p['fecha_caducidad'] ?>" class="form-control"></td>
                            <td><input type="text" name="categoria[<?= $p['id'] ?>]" value="<?= htmlspecialchars($p['categoria']) ?>" class="form-control" maxlength="50"></td>
                            <td><input type="number" name="stock[<?= $p['id'] ?>]" value="<?= $p['stock'] ?>" class="form-control" min="0"></td>
                            <td><input type="number" step="0.01" name="precio[<?= $p['id'] ?>]" value="<?= $p['precio'] ?>" class="form-control" min="0"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary px-4">Enviar cambios para aprobación</button>
                <a href="index.php" class="btn btn-secondary">⬅ Volver</a>
            </div>
        </form>
    </div>
</div>

<?php include('../templates/pieVendedor.php'); ?>
</body>
</html>
