<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php");
    exit;
}

$mensaje = '';
$modo_edicion = false;
$usuario_editado = null;

// Crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_usuario'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if ($username && $password && $rol) {
        if (strlen($username) > 50 || strlen($rol) > 20) {
            $mensaje = "⚠ El nombre de usuario o rol excede el límite permitido.";
        } else {
            $verifica = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ?");
            $verifica->execute([$username]);
            if ($verifica->fetchColumn() > 0) {
                $mensaje = "⚠ El usuario ya existe.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conexion->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)");
                $stmt->execute([$username, $hash, $rol]);
                $mensaje = "✅ Usuario creado correctamente.";
            }
        }
    } else {
        $mensaje = "⚠ Completa todos los campos.";
    }
}

// Mostrar formulario de edición
if (isset($_GET['editar'])) {
    $modo_edicion = true;
    $id_editar = $_GET['editar'];
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id_editar]);
    $usuario_editado = $stmt->fetch();
}

// Guardar cambios de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $id = $_POST['id'] ?? '';
    $username = $_POST['username'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $new_password = $_POST['password'] ?? '';

    if ($id && $username && $rol) {
        if (strlen($username) > 50 || strlen($rol) > 20 || strlen($new_password) > 255) {
            $mensaje = "⚠ Algún campo excede el límite permitido.";
        } else {
            if (!empty($new_password)) {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, rol = ?, password = ? WHERE id = ?");
                $stmt->execute([$username, $rol, $hash, $id]);
            } else {
                $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, rol = ? WHERE id = ?");
                $stmt->execute([$username, $rol, $id]);
            }
            $mensaje = "✅ Usuario actualizado correctamente.";
            header("Location: usuarios.php");
            exit;
        }
    } else {
        $mensaje = "⚠ Todos los campos son obligatorios.";
    }
}

// Eliminar usuario
//if (isset($_GET['eliminar'])) {
  //  $id = $_GET['eliminar'];
   // $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
   // $stmt->execute([$id]);
   // header("Location: usuarios.php");
   // exit;
//}

// Cambiar estatus de usuario (Desactivar/Activar)
if (isset($_GET['desactivar'])) {
    $id = $_GET['desactivar'];
    // Se establece el estatus a 0 (inactivo)
    $stmt = $conexion->prepare("UPDATE usuarios SET status = 0 WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: usuarios.php");
    exit;
}

if (isset($_GET['activar'])) {
    $id = $_GET['activar'];
    // Se establece el estatus a 1 (activo)
    $stmt = $conexion->prepare("UPDATE usuarios SET status = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: usuarios.php");
    exit;
}

// Obtener usuarios
$usuarios = $conexion->query("SELECT * FROM usuarios ORDER BY id ASC")->fetchAll();

include('../templates/cabecera.php');
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gestión de Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .card-panel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }
        h2 {
            background: linear-gradient(135deg, #6a5d7b 0%, #a497bf 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 25px;
        }
        .btn-agregar {
            background-color: #6a5d7b;
            color: white;
            border: none;
        }
        .btn-agregar:hover {
            background-color: #55486a;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .alerta {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>

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
