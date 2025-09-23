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

<body>
<div class="container">
    <div class="card-panel">
        <h2>Gestión de Usuarios</h2>

        <?php if ($mensaje): ?>
            <div class="alerta text-danger"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($modo_edicion && $usuario_editado): ?>
            <!-- Formulario de edición -->
            <form method="post" class="row g-3 mb-4">
                <input type="hidden" name="actualizar_usuario" value="1">
                <input type="hidden" name="id" value="<?= $usuario_editado['id'] ?>">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($usuario_editado['username']) ?>" maxlength="50" required>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="rol" required>
                        <option value="gerente" <?= $usuario_editado['rol'] === 'gerente' ? 'selected' : '' ?>>Gerente</option>
                        <option value="abarrotero" <?= $usuario_editado['rol'] === 'abarrotero' ? 'selected' : '' ?>>Abarrotero</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="password" class="form-control" name="password" placeholder="Nueva contraseña (opcional)" maxlength="255">
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-warning">💾 Guardar cambios</button>
                </div>
            </form>
        <?php else: ?>
            <!-- Formulario de nuevo usuario -->
            <form method="post" class="row g-3 mb-4">
                <input type="hidden" name="nuevo_usuario" value="1">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="username" placeholder="Usuario" maxlength="50" required>
                </div>
                <div class="col-md-3">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="rol" required>
                        <option value="" disabled selected>Seleccionar rol</option>
                        <option value="gerente">Gerente</option>
                        <option value="abarrotero">Abarrotero</option>
                    </select>
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-agregar">➕ Agregar usuario</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Lista de usuarios -->
       <table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u['id']; ?></td>
                <td><?= htmlspecialchars($u['username']); ?></td>
                <td><?= ucfirst($u['rol']); ?></td>
                <td>
                    <span class="badge <?= $u['status'] == 1 ? 'bg-success' : 'bg-secondary'; ?>">
                        <?= $u['status'] == 1 ? 'Activo' : 'Inactivo'; ?>
                    </span>
                </td>
                <td>
                    <a href="usuarios.php?editar=<?= $u['id']; ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                    
                    <?php if ($u['status'] == 1): ?>
                        <a href="usuarios.php?desactivar=<?= $u['id']; ?>" class="btn btn-sm btn-warning" onclick="return confirm('¿Desactivar este usuario?')">❌ Desactivar</a>
                    <?php else: ?>
                        <a href="usuarios.php?activar=<?= $u['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('¿Activar este usuario?')">✔️ Activar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

        <a href="index.php" class="btn btn-secondary mt-3">⬅ Volver al panel</a>
    </div>
</div>

<?php include('../templates/pie.php'); ?>
</body>
</html>
