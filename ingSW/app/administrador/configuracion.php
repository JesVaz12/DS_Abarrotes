<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

// Verificar rol
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php");
    exit;
}

$usuarioSesion = $_SESSION['usuario'];
$mensaje = '';

// Obtener datos actuales
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt->execute([$usuarioSesion]);
$usuario = $stmt->fetch();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoUsuario = trim($_POST['username'] ?? '');
    $nuevaPassword = $_POST['password'] ?? '';

    if ($nuevoUsuario && strlen($nuevoUsuario) <= 50) {
        if ($nuevaPassword) {
            $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("UPDATE usuarios SET username = ?, password = ? WHERE username = ?");
            $stmt->execute([$nuevoUsuario, $hash, $usuarioSesion]);
        } else {
            $stmt = $conexion->prepare("UPDATE usuarios SET username = ? WHERE username = ?");
            $stmt->execute([$nuevoUsuario, $usuarioSesion]);
        }

        $_SESSION['usuario'] = $nuevoUsuario;
        $usuarioSesion = $nuevoUsuario;
        $mensaje = "✅ Datos actualizados correctamente.";
    } else {
        $mensaje = "⚠ El nombre de usuario no puede estar vacío ni exceder los 50 caracteres.";
    }
}

include('../templates/cabecera.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración de Cuenta (Administrador)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-panel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-top: 40px;
            max-width: 600px;
        }
        h2 {
            background: linear-gradient(135deg, #6a5d7b, #a497bf);
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 25px;
        }
    </style>
</head>

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
                <input type="password" name="password" class="form-control" placeholder="Solo si deseas cambiarla">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">Guardar cambios</button>
                <a href="index.php" class="btn btn-secondary">⬅ Volver</a>
            </div>
        </form>
    </div>
</div>

<?php include('../templates/pie.php'); ?>
</body>
</html>
