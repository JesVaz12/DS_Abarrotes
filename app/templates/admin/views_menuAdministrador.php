<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel del Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f9f3f3;
            font-family: Arial, sans-serif;
        }
        .panel-container {
            max-width: 1000px;
            margin: 60px auto;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .card-header-title {
            background: linear-gradient(135deg, #6a5d7b 0%, #a497bf 100%);
            color: white;
            padding: 20px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
            font-size: 22px;
        }
        .acciones, .caducados {
            padding: 20px;
        }
        .acciones a {
            display: block;
            margin: 10px 0;
            padding: 15px;
            border-radius: 10px;
            background-color: #6a5d7b;
            color: white;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .acciones a:hover {
            background-color: #55486a;
        }
        .bottom-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .bottom-buttons a {
            width: 48%;
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            border-radius: 10px;
            text-decoration: none;
        }
        .caducados h4 {
            margin-bottom: 15px;
            text-align: center;
            color: #550000;
            font-weight: bold;
        }
        .caducados table {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
        }
        .caducados th, .caducados td {
            padding: 10px;
            border: 1px solid #ccc;
            background: #fff;
            text-align: center;
        }
        .caducados th {
            background: #f2f2f2;
            font-weight: 600;
        }
        .caducados a {
            color: #dc3545;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="panel-container">
    <div class="card">
        <div class="card-header-title">
            Panel del Administrador
        </div>

        <div class="row g-0">
            <div class="col-md-6 acciones border-end">
                <h5 class="mb-3">Opciones administrativas</h5>
                <a href="usuarios.php">👤 Gestión de usuarios</a>
                <a href="bajaProducto.php">🗑️ Dar de baja un producto</a>
                <a href="aprobarInventario.php">✅ Aprobar inventario</a>
                <a href="reporteCaducados.php">📄 Reporte de productos caducados</a>
                <a href="mostrarInventario.php">📦 Ver inventario completo</a>

                <div class="bottom-buttons">
                    <a href="configuracion.php">⚙ Configuración</a>
                    <a href="../../logout.php">⏏ Cerrar sesión</a>
                </div>
            </div>

            <div class="col-md-6 caducados">
                <h4>Productos caducados</h4>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Caducidad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $hoy = date('Y-m-d');
                            $stmt = $conexionBD->prepare("SELECT id, nombre, fecha_caducidad FROM productos WHERE fecha_caducidad <= ?");
                            $stmt->execute([$hoy]);
                            $productos = $stmt->fetchAll();

                            if (count($productos) > 0) {
                                foreach ($productos as $row) {
                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>{$row['nombre']}</td>";
                                    echo "<td>{$row['fecha_caducidad']}</td>";
                                    echo "<td><a href='bajaProducto.php?id={$row['id']}'>Dar de baja</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay productos caducados.</td></tr>";
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='4'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include(__DIR__ . '/../layouts/pie.php'); ?>
