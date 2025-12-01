<?php
session_start();
require_once '../src/controllers/config/bd.php';

// Seguridad: Si no vienes del login, te saco
if (!isset($_SESSION['mfa_user_id'])) {
    header("Location: index.php");
    exit;
}

$mensaje = '';
$tipo_mensaje = '';
$usuario_id = $_SESSION['mfa_user_id'];
$conexion = BD::crearInstancia();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_ingresado = trim($_POST['codigo']);

    // Obtener info del usuario
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch();

    // 1. CHECAR INTENTOS
    if ($usuario['mfa_intentos'] >= 3) {
        $mensaje = "⛔ Demasiados intentos fallidos. Por seguridad, vuelve a iniciar sesión.";
        $tipo_mensaje = "danger";
        unset($_SESSION['mfa_user_id']); // Matar sesión temporal
        header("refresh:4;url=index.php");
    }
    // 2. CHECAR SI EXPIRÓ
    elseif (date("Y-m-d H:i:s") > $usuario['mfa_expiracion']) {
        $mensaje = "⌛ El código ha caducado. Vuelve a iniciar sesión.";
        $tipo_mensaje = "warning";
        unset($_SESSION['mfa_user_id']);
        header("refresh:3;url=index.php");
    }
    // 3. CÓDIGO CORRECTO
    elseif ($codigo_ingresado === $usuario['mfa_codigo']) {
        // Limpiar BD
        $update = $conexion->prepare("UPDATE usuarios SET mfa_codigo = NULL, mfa_expiracion = NULL, mfa_intentos = 0 WHERE id = ?");
        $update->execute([$usuario_id]);

        // INICIAR SESIÓN REAL
        $_SESSION['usuario'] = $usuario['username'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['id_usuario'] = $usuario['id'];
        unset($_SESSION['mfa_user_id']); // Adiós sesión temporal

        // Redirigir
        if ($usuario['rol'] === 'gerente') {
            header("Location: ../src/controllers/admin/index.php");
        } else {
            header("Location: ../src/controllers/seller/index.php");
        }
        exit;
    } else {
        // CÓDIGO INCORRECTO
        $intentos = $usuario['mfa_intentos'] + 1;
        $restantes = 3 - $intentos;

        // Registrar fallo en BD
        $update = $conexion->prepare("UPDATE usuarios SET mfa_intentos = ? WHERE id = ?");
        $update->execute([$intentos, $usuario_id]);

        if ($restantes <= 0) {
            header("Location: verificar_mfa.php"); // Recargar para mostrar bloqueo
            exit;
        }

        $mensaje = "❌ Código incorrecto. Te quedan <b>$restantes</b> intentos.";
        $tipo_mensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Verificación de Seguridad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f3f3;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-purple {
            background-color: #6a5d7b;
            color: white;
        }

        .btn-purple:hover {
            background-color: #55486a;
            color: white;
        }
    </style>
</head>

<body>
    <div class="card text-center">
        <h3 class="mb-3" style="color: #6a5d7b;">Verificación MFA</h3>
        <p class="text-muted small">Ingresa el código que enviamos a tu correo.</p>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?= $tipo_mensaje ?>"><?= $mensaje ?></div>
        <?php endif; ?>

        <?php if (strpos($mensaje, 'Demasiados') === false): ?>
            <form method="POST">
                <div class="mb-4">
                    <input type="text" name="codigo" class="form-control text-center fs-3"
                        maxlength="6" placeholder="000000" autofocus required autocomplete="off">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-purple">Verificar Acceso</button>
                </div>
            </form>
        <?php endif; ?>

        <div class="mt-3">
            <a href="index.php" class="text-decoration-none small text-secondary">Cancelar</a>
        </div>
    </div>
</body>

</html>