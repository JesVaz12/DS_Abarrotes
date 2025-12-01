<?php
session_start();
// 1. Cargamos la conexión y PHPMailer
require_once '../src/controllers/config/bd.php';
require_once '../vendor/autoload.php'; // Esto carga la librería que acabas de instalar

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $conexion = BD::crearInstancia();

        // 2. Verificar si el usuario existe
        $stmt = $conexion->prepare("SELECT id, username FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            // 3. Generar código y fecha de expiración
            $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiracion = date("Y-m-d H:i:s", strtotime('+1 minutes'));

            // 4. Guardar el código en la Base de Datos
            $update = $conexion->prepare("UPDATE usuarios SET codigo_recuperacion = ?, expiracion_codigo = ? WHERE email = ?");
            $update->execute([$codigo, $expiracion, $email]);

            // 5. Configurar el envío de correo
            $mail = new PHPMailer(true);

            try {
                // Configuración del Servidor (Usando Variables de Entorno)
                $mail->isSMTP();
                $mail->Host       = getenv('MAIL_HOST');      // Lee del entorno
                $mail->SMTPAuth   = true;
                $mail->Username   = getenv('MAIL_USER');      // Lee del entorno
                $mail->Password   = getenv('MAIL_PASSWORD');  // Lee del entorno
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = getenv('MAIL_PORT');
                $mail->CharSet    = 'UTF-8';

                // Destinatarios
                $mail->setFrom(getenv('MAIL_USER'), 'Soporte Abarrotes AM');
                $mail->addAddress($email);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Código de Recuperación de Contraseña';
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; color: #333;'>
                        <h2 style='color: #6a5d7b;'>Recuperación de Acceso</h2>
                        <p>Hola <b>{$usuario['username']}</b>,</p>
                        <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
                        <p>Tu código de verificación es:</p>
                        <h1 style='letter-spacing: 5px; color: #444;'>$codigo</h1>
                        <p><small>Este código expira en 1 minuto.</small></p>
                    </div>
                ";

                $mail->send();

                // Redirigir a la pantalla donde pone el código
                header("Location: restablecer.php?email=" . urlencode($email));
                exit;
            } catch (Exception $e) {
                $mensaje = "❌ Error al enviar correo. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $mensaje = "❌ Ese correo no está registrado en el sistema.";
        }
    } else {
        $mensaje = "⚠ Por favor ingresa un correo válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
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
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        .btn-purple {
            background-color: #6a5d7b;
            color: white;
            border: none;
        }

        .btn-purple:hover {
            background-color: #55486a;
            color: white;
        }
    </style>
</head>

<body>
    <div class="card">
        <h3 class="text-center mb-4" style="color: #6a5d7b;">Recuperar Acceso</h3>

        <?php if ($mensaje): ?>
            <div class="alert alert-danger"><?= $mensaje ?></div>
        <?php endif; ?>

        <p class="text-muted text-center small">Ingresa tu correo y te enviaremos un código de acceso.</p>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" name="email" required placeholder="ejemplo@correo.com">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-purple">Enviar Código</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="index.php" class="text-secondary small text-decoration-none">Volver al Login</a>
        </div>
    </div>
</body>

</html>