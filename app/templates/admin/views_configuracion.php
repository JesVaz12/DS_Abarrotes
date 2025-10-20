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
        <div class="container d-flex justify-content-center">
            <div class="card-panel">
                <h2>Mi Cuenta</h2>

                <?php if ($mensaje): ?>
                    <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Nombre de usuario</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($usuario['username']) ?>" maxlength="50" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña (opcional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Solo si deseas cambiarla"
                            minlength="8" maxlength="72" title="La contraseña debe tener entre 8 y 72 caracteres">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success px-4">Guardar cambios</button>
                        <a href="index.php" class="btn btn-secondary">⬅ Volver</a>
                    </div>
                </form>
            </div>
        </div>

        <?php include(__DIR__ . '/../layouts/pie.php'); ?>
    </body>

</html>