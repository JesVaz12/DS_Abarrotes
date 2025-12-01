<?php
session_start();
require_once '../src/controllers/config/bd.php';
$mensaje = '';
$tipo_mensaje = '';
$email = $_GET['email'] ?? '';
$tiempo_restante = 0;
$codigo_valido = false;

// 1. VALIDAR SI EL CÓDIGO SIGUE VIVO AL CARGAR LA PÁGINA
if ($email) {
    $conexion = BD::crearInstancia();
    // Buscamos la fecha de expiración de este usuario
    $stmt = $conexion->prepare("SELECT expiracion_codigo FROM usuarios WHERE email = ? AND codigo_recuperacion IS NOT NULL");
    $stmt->execute([$email]);
    $datos = $stmt->fetch();

    if ($datos) {
        $ahora = time();
        $expiracion = strtotime($datos['expiracion_codigo']);
        // Calculamos cuántos segundos faltan
        $tiempo_restante = $expiracion - $ahora;

        if ($tiempo_restante > 0) {
            $codigo_valido = true;
        } else {
            $mensaje = "⌛ El código ha expirado. Por favor solicita uno nuevo.";
            $tipo_mensaje = "warning";
        }
    } else {
        $mensaje = "❌ No hay ninguna solicitud de recuperación activa para este correo.";
        $tipo_mensaje = "danger";
    }
}

// 2. PROCESAR EL FORMULARIO (Solo si se envía)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $codigo_valido) {
    $codigo_ingresado = trim($_POST['codigo']);
    $nueva_password   = $_POST['password'];
    $email_post       = $_POST['email'];

    // Verificar otra vez en backend por seguridad
    $stmt = $conexion->prepare("SELECT id, expiracion_codigo FROM usuarios WHERE email = ? AND codigo_recuperacion = ?");
    $stmt->execute([$email_post, $codigo_ingresado]);
    $usuario = $stmt->fetch();

    if ($usuario && date("Y-m-d H:i:s") <= $usuario['expiracion_codigo']) {
        $hash = password_hash($nueva_password, PASSWORD_DEFAULT);
        $update = $conexion->prepare("UPDATE usuarios SET password = ?, codigo_recuperacion = NULL, expiracion_codigo = NULL WHERE id = ?");
        $update->execute([$hash, $usuario['id']]);

        $mensaje = "✅ ¡Contraseña restablecida con éxito! Redirigiendo...";
        $tipo_mensaje = "success";
        $codigo_valido = false; // Ocultar formulario
        header("refresh:3;url=index.php");
    } else {
        $mensaje = "❌ Código incorrecto o expirado.";
        $tipo_mensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña</title>
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

        .timer-box {
            font-size: 1.2rem;
            font-weight: bold;
            color: #dc3545;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="card">
        <h3 class="text-center mb-3" style="color: #6a5d7b;">Restablecer</h3>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?= $tipo_mensaje ?>"><?= $mensaje ?></div>
        <?php endif; ?>

        <?php if ($codigo_valido && $tipo_mensaje !== 'success'): ?>

            <div class="timer-box" id="contador">
                Tiempo restante: <span id="tiempo">--:--</span>
            </div>

            <form method="POST" id="formulario-reset">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                <div class="mb-3">
                    <label class="form-label">Código de Verificación</label>
                    <input type="text" class="form-control" name="codigo" required maxlength="6" placeholder="000000" autocomplete="off">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" name="password" required minlength="8" placeholder="********">
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-success">Guardar Nueva Contraseña</button>
                </div>
            </form>
        <?php endif; ?>

        <div id="seccion-expirada" class="text-center" style="<?= (!$codigo_valido && $tipo_mensaje !== 'success') ? '' : 'display: none;' ?>">
            <?php if ($tipo_mensaje !== 'warning' && $tipo_mensaje !== 'danger'): ?>
                <div class="alert alert-warning">⌛ El tiempo ha expirado.</div>
            <?php endif; ?>

            <p class="text-muted small">El código de seguridad ha caducado por tu seguridad.</p>
            <a href="recuperar.php" class="btn btn-primary w-100 mb-2">
                🔄 Generar Nuevo Código
            </a>
            <a href="index.php" class="btn btn-secondary w-100">Cancelar</a>
        </div>

        <?php if ($codigo_valido && $tipo_mensaje !== 'success'): ?>
            <div class="text-center mt-2" id="links-auxiliares">
                <a href="index.php" class="text-decoration-none small text-secondary">Cancelar</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Obtenemos el tiempo restante desde PHP (en segundos)
        let segundosRestantes = <?= $tiempo_restante ?>;

        function actualizarReloj() {
            const contadorDisplay = document.getElementById('tiempo');
            const formulario = document.getElementById('formulario-reset');
            const seccionExpirada = document.getElementById('seccion-expirada');
            const linksAux = document.getElementById('links-auxiliares');
            const contadorBox = document.getElementById('contador');

            if (segundosRestantes <= 0) {
                // TIEMPO AGOTADO
                if (formulario) formulario.style.display = 'none'; // Ocultar form
                if (linksAux) linksAux.style.display = 'none'; // Ocultar links
                if (contadorBox) contadorBox.style.display = 'none'; // Ocultar reloj
                seccionExpirada.style.display = 'block'; // Mostrar botón de nuevo código
                return;
            }

            // Formato MM:SS
            let minutos = Math.floor(segundosRestantes / 60);
            let segundos = segundosRestantes % 60;

            // Agregar cero a la izquierda si es necesario (09 en vez de 9)
            segundos = segundos < 10 ? '0' + segundos : segundos;

            contadorDisplay.textContent = minutos + ":" + segundos;
            segundosRestantes--;
        }

        // Si hay tiempo, iniciamos el intervalo
        if (segundosRestantes > 0) {
            setInterval(actualizarReloj, 1000); // Ejecutar cada 1 segundo
            actualizarReloj(); // Ejecutar inmediatamente la primera vez
        }
    </script>
</body>

</html>