<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            text-align: center;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-success {
            background-color: #6a5d7b;
            border: none;
        }
        .btn-success:hover {
            background-color: #55486a;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="card-panel">
        <h2>Agregar Nuevo Producto</h2>

        <?php if ($mensaje): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="post" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre del producto</label>
                <input type="text" name="nombre" class="form-control" maxlength="100" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Categoría</label>
                <select name="categoria" class="form-control" required>
                    <option value="">-- Selecciona una categoría --</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['nombre']) ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Fecha de caducidad</label>
                <input type="date" name="fecha_caducidad" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" min="1" max="99999" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Precio</label>
                <input type="number" step="0.01" min="0" max="99999.99" name="precio" class="form-control" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3" maxlength="255"></textarea>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-success px-4">Enviar para aprobación</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include('../templates/pieVendedor.php'); ?>
</body>
</html>
