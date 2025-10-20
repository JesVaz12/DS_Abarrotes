<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Panel del Vendedor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f9f3f3;
            font-family: Arial, sans-serif;
        }

        .panel-container {
            max-width: 1100px;
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

        .acciones,
        .caducados {
            padding: 20px;
        }

        .acciones a {
            display: block;
            margin: 10px 0;
            padding: 12px;
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
            flex: 1;
            margin: 0 5px;
            padding: 10px;
            background-color: #333;
            color: white;
            border-radius: 10px;
            text-align: center;
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

        .caducados th,
        .caducados td {
            padding: 8px;
            border: 1px solid #ccc;
            background: #fff;
            text-align: center;
        }

        .caducados th {
            background: #f2f2f2;
            font-weight: 600;
        }

        .form-control {
            font-size: 13px;
            padding: 5px;
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

    <div class="panel-container">
        <div class="card">
            <div class="card-header-title">
                Panel del Vendedor
            </div>

            <div class="row g-0">
                <!-- Panel de acciones -->
                <div class="col-md-5 acciones border-end">
                    <h5 class="mb-3 text-center">Opciones del sistema</h5>
                    <a href="agregarProducto.php">➕ Agregar nuevo producto</a>
                    <a href="mostrarInventario.php">📦 Ver inventario</a>
                    <a href="reporteCaducados.php">📄 Generar reporte de caducidad</a>

                    <div class="bottom-buttons">
                        <a href="configuracion.php">⚙ Configuración</a>
                        <a href="../../logout.php">⏏ Cerrar sesión</a>
                    </div>
                </div>

                <!-- Productos caducados editables -->
                <div class="col-md-7 caducados">
                    <h4>Productos próximos a caducar</h4>

                    <form action="actualizarInventario.php" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Caducidad</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Baja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $hoy = date('Y-m-d');
                                    $stmt = $conexionBD->prepare("SELECT * FROM productos WHERE fecha_caducidad <= DATE_ADD(?, INTERVAL 7 DAY)");
                                    $stmt->execute([$hoy]);
                                    $productos = $stmt->fetchAll();

                                    if (count($productos) > 0) {
                                        foreach ($productos as $producto) {
                                            $id = $producto['id'];
                                            echo "<tr>";
                                            echo "<td>{$id}</td>";
                                            echo "<td>" . htmlspecialchars($producto['nombre']) . "</td>";
                                            echo "<td>{$producto['fecha_caducidad']}</td>";
                                            echo "<td><input type='text' name='descripcion[$id]' value='" . htmlspecialchars($producto['descripcion']) . "' class='form-control'></td>";
                                            echo "<td><input type='number' step='0.01' name='precio[$id]' value='{$producto['precio']}' class='form-control' style='width:80px'></td>";
                                            echo "<td><input type='checkbox' name='baja[]' value='{$id}'></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No hay productos próximos a caducar.</td></tr>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='6'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-success px-4">🔄 Actualizar inventario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include(__DIR__ . '/../layouts/pieVendedor.php'); ?>
</body>

</html>