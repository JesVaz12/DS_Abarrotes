<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abarrotero') {
    header("Location: ../index.php");
    exit;
}

include('../templates/cabVendedor.php');

$stmt = $conexion->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inventario Editable</title>
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
        .form-control {
            font-size: 14px;
            padding: 5px;
        }
        .btn-primary {
            background-color: #6a5d7b;
            border: none;
        }
        .btn-primary:hover {
            background-color: #55486a;
        }
    </style>
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
