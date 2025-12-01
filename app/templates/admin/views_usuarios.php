<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/css/estilos-globales.css">
    <link rel="stylesheet" href="/public/css/estilos-panel.css">
</head>

<body>
    <div class="container">
        <div class="card-panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Gestión de Usuarios</h2>
                <a href="usuarios_eliminados.php" class="btn btn-secondary">🗑️ Ver Papelera</a>
            </div>

            <?php if ($mensaje): ?>
                <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <?php if ($modo_edicion && $usuario_editado): ?>
                <div class="card p-3 mb-4 bg-light border">
                    <h5 class="mb-3">✏️ Editando: <b><?= htmlspecialchars($usuario_editado['username']) ?></b></h5>
                    <form method="post" class="row g-3">
                        <input type="hidden" name="actualizar_usuario" value="1">
                        <input type="hidden" name="id" value="<?= $usuario_editado['id'] ?>">

                        <div class="col-md-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($usuario_editado['username']) ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario_editado['email']) ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Rol</label>
                            <select class="form-select" name="rol" required>
                                <option value="gerente" <?= $usuario_editado['rol'] === 'gerente' ? 'selected' : '' ?>>Gerente</option>
                                <option value="abarrotero" <?= $usuario_editado['rol'] === 'abarrotero' ? 'selected' : '' ?>>Abarrotero</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Clave (Opcional)</label>
                            <input type="password" class="form-control" name="password" placeholder="Nueva contraseña">
                        </div>

                        <div class="col-12 text-end">
                            <a href="usuarios.php" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                        </div>
                    </form>
                </div>

            <?php else: ?>
                <form method="post" class="row g-3 mb-4 align-items-end">
                    <input type="hidden" name="nuevo_usuario" value="1">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="username" placeholder="Usuario" required>
                    </div>
                    <div class="col-md-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="col-md-3">
                        <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="rol" required>
                            <option value="" disabled selected>Rol</option>
                            <option value="gerente">Gerente</option>
                            <option value="abarrotero">Abarrotero</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-success">➕</button>
                    </div>
                </form>
            <?php endif; ?>

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= ucfirst($u['rol']) ?></td>
                            <td>
                                <a href="usuarios.php?editar=<?= $u['id'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                                <a href="usuarios.php?desactivar=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Enviar a papelera?')">🗑️ Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="index.php" class="btn btn-secondary mt-3">⬅ Volver al panel</a>
        </div>
    </div>
    <?php include(__DIR__ . '/../layouts/pie.php'); ?>
</body>

</html>