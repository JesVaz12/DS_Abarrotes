<!doctype html>
<html lang="es">

<head>
    <title>Panel de Vendedor</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #fdfaf6;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #f4f4f4 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
        }

        .nav-link.text-danger {
            font-weight: bold;
        }

        .navbar img {
            height: 36px;
            width: 36px;
            border-radius: 50%;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-outline-success {
            border-radius: 10px;
            color: #6a5d7b;
            border-color: #6a5d7b;
        }

        .btn-outline-success:hover {
            background-color: #6a5d7b;
            color: white;
        }

        .container {
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="navbar-nav d-flex align-items-center">
                <img src="../../../public/imagenes/perfil.png" alt="Perfil" title="Vendedor" class="me-2">
                <a class="nav-link" href="index.php">Inicio</a>
                <a class="nav-link text-danger" href="../../logout.php">Cerrar sesión</a>
            </div>
            <form class="d-flex ms-auto me-3" action="buscar_articulos.php" method="get">
                <input class="form-control me-2" type="search" name="q" placeholder="Buscar artículos" aria-label="Buscar">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
        </div>
    </nav>