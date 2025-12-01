<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Usuarios Eliminados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/css/estilos-globales.css">
</head>

<body>

    <div class="container mt-4">
        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-danger">🗑️ Papelera de Usuarios</h2>
                <a href="usuarios.php" class="btn btn-primary">
                    ⬅ Volver a Usuarios Activos
                </a>
            </div>

            <?php if (count($usuarios) > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= $u['id']; ?></td>
                                <td><?= htmlspecialchars($u['username']); ?></td>
                                <td><?= htmlspecialchars($u['email']); ?></td>
                                <td><?= ucfirst($u['rol']); ?></td>
                                <td>
                                    <a href="usuarios_eliminados.php?restaurar=<?= $u['id']; ?>"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('¿Restaurar este usuario? Volverá a aparecer en la lista principal.')">
                                        ♻️ Restaurar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    No hay usuarios en la papelera.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include(__DIR__ . '/../layouts/pie.php'); ?>
</body>

</html>