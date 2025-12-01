<?php
// ---- CONFIGURACIÓN INICIAL ----
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Rutas
require_once __DIR__ . '/../src/controllers/config/bd.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Gregwar\Captcha\CaptchaBuilder;

$mensaje = '';

// Redirección si ya hay sesión
if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'gerente') {
        header("Location: ../src/controllers/admin/index.php");
    } elseif ($_SESSION['rol'] === 'abarrotero') {
        header("Location: ../src/controllers/seller/index.php");
    }
    exit;
}

// Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Función auxiliar para generar un Captcha LIMPIO y LEGIBLE
function generarCaptchaLimpio()
{
    $builder = new CaptchaBuilder();

    // --- CONFIGURACIÓN PARA HACERLO FÁCIL DE LEER ---
    $builder->setDistortion(false);       // Quita la deformación ondulada
    $builder->setMaxBehindLines(0);       // Quita las líneas de atrás
    $builder->setMaxFrontLines(0);        // Quita las líneas de enfrente
    $builder->setBackgroundColor(255, 255, 255); // Fondo blanco puro
    $builder->setIgnoreAllEffects(true);  // Ignora efectos raros
    // ------------------------------------------------

    $builder->build(150, 40); // Ancho: 150px, Alto: 40px

    $_SESSION['captcha_phrase'] = $builder->getPhrase();
    $_SESSION['captcha_img'] = $builder->inline();
}

// ---- PROCESAR LOGIN (POST) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captcha_user = $_POST['captcha'] ?? '';
    $username     = trim($_POST['username'] ?? '');
    $password     = trim($_POST['password'] ?? '');
    $csrf_token   = $_POST['csrf_token'] ?? '';

    // 1. Validar si la sesión de captcha existe
    if (!isset($_SESSION['captcha_phrase'])) {
        $mensaje = "La sesión caducó. Intenta de nuevo.";
        generarCaptchaLimpio();
    }
    // 2. VALIDAR CSRF
    elseif (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $mensaje = "Error de seguridad (Token inválido).";
        generarCaptchaLimpio();
    }
    // 3. VALIDAR CAPTCHA
    else {
        $frase_correcta = $_SESSION['captcha_phrase'];
        $builderValidacion = new CaptchaBuilder($frase_correcta);

        if (!$builderValidacion->testPhrase($captcha_user)) {
            $mensaje = "🤖 Código incorrecto. Intenta con este nuevo.";
            // IMPORTANTE: Regenerar si falló para evitar reintentos con el mismo
            generarCaptchaLimpio();
        }
        // 4. VALIDAR DATOS VACÍOS
        elseif (!$username || !$password) {
            $mensaje = "Escribe tu usuario y contraseña.";
            // No regeneramos captcha aquí para no molestar al usuario si solo olvidó el pass
        }
        // 5. VALIDAR CREDENCIALES
        else {
            $conexionBD = BD::crearInstancia();
            $stmt = $conexionBD->prepare("SELECT * FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['password'])) {
                if ($usuario['status'] == 1) {

                    // --- ÉXITO: INICIAR MFA ---
                    $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                    $expiracion = date("Y-m-d H:i:s", strtotime('+5 minutes'));

                    $update = $conexionBD->prepare("UPDATE usuarios SET mfa_codigo = ?, mfa_expiracion = ?, mfa_intentos = 0 WHERE id = ?");
                    $update->execute([$codigo, $expiracion, $usuario['id']]);

                    try {
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host       = getenv('MAIL_HOST');
                        $mail->SMTPAuth   = true;
                        $mail->Username   = getenv('MAIL_USER');
                        $mail->Password   = getenv('MAIL_PASSWORD');
                        $mail->Port       = getenv('MAIL_PORT');
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->CharSet    = 'UTF-8';

                        $mail->setFrom(getenv('MAIL_USER'), 'Seguridad Abarrotes AM');
                        $mail->addAddress($usuario['email']);
                        $mail->isHTML(true);
                        $mail->Subject = 'Código MFA';
                        $mail->Body    = "<h2>Tu código es: $codigo</h2>";

                        $mail->send();

                        $_SESSION['mfa_user_id'] = $usuario['id'];
                        // Limpiamos captcha de la sesión para ahorrar memoria
                        unset($_SESSION['captcha_phrase']);
                        unset($_SESSION['captcha_img']);

                        header("Location: verificar_mfa.php");
                        exit;
                    } catch (Exception $e) {
                        $mensaje = "❌ Error enviando correo. Contacta soporte.";
                        // Si falla el correo, regeneramos captcha por seguridad
                        generarCaptchaLimpio();
                    }
                } else {
                    $mensaje = "Cuenta inactiva.";
                    generarCaptchaLimpio();
                }
            } else {
                $mensaje = "❌ Usuario o contraseña incorrectos.";
                // Regenerar captcha si falló la contraseña para evitar fuerza bruta
                generarCaptchaLimpio();
            }
        }
    }
}
// SI ES LA PRIMERA VEZ QUE ENTRAMOS (GET) O SI NO HAY CAPTCHA
else {
    if (!isset($_SESSION['captcha_img'])) {
        generarCaptchaLimpio();
    }
}

require_once __DIR__ . '/../templates/login_view.php';
