<?php
// ---- CONFIGURACIÓN INICIAL Y DE SEGURIDAD ----
///AAAAAAAA

// Muestra todos los errores de PHP. Útil para la depuración en desarrollo.
// Se recomienda desactivarlo en un entorno de producción.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inicia o reanuda una sesión de usuario. Es necesario para usar la variable $_SESSION.
session_start();

// Sube de 'public' y entra a 'src/controllers/config/bd.php'
require_once __DIR__ . '/../src/controllers/config/bd.php';

// ... (código de session_start())

// Sube de 'public' y entra a 'src/controllers/config/bd.php'
require_once __DIR__ . '/../src/controllers/config/bd.php';

// Crea una instancia (conexión) a la base de datos...
$conexionBD = BD::crearInstancia();

// ... (el resto de tu código)
// Crea una instancia (conexión) a la base de datos usando un método estático de la clase BD.
$conexionBD = BD::crearInstancia();

// ---- REDIRECCIÓN SI EL USUARIO YA ESTÁ AUTENTICADO ----

// Verifica si ya existe una sesión activa con un rol definido.
if (isset($_SESSION['rol'])) {
    // Si el rol es 'gerente', redirige al panel de administrador.
    if ($_SESSION['rol'] === 'gerente') {
        header("Location: ../src/controllers/admin/index.php");
        // Si el rol es 'abarrotero', redirige al panel de vendedor.
    } elseif ($_SESSION['rol'] === 'abarrotero') {
        header("Location: ../src/controllers/seller/index.php");
    }
    // Detiene la ejecución del script después de la redirección.
    exit;
}

// ---- PROTECCIÓN CONTRA CSRF (Cross-Site Request Forgery) ----

// Si no existe un token CSRF en la sesión, genera uno nuevo.
// Este token se usará para verificar que el formulario fue enviado desde este sitio.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token seguro de 32 bytes.
}

// ---- MANEJO DEL FORMULARIO DE INICIO DE SESIÓN ----

// Inicializa la variable para mensajes de error o estado.
$mensaje = '';

// ---- PROTECCIÓN CONTRA FUERZA BRUTA ----

// Inicializa el contador de intentos de inicio de sesión si no existe.
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
// Inicializa el tiempo del último intento si no existe.
if (!isset($_SESSION['last_attempt_time'])) {
    $_SESSION['last_attempt_time'] = time();
}

// Bloquea el inicio de sesión si hay 5 o más intentos fallidos en los últimos 5 minutos (300 segundos).
if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_attempt_time']) < 300) {
    $mensaje = "Demasiados intentos fallidos. Inténtalo en unos minutos.";
    // Procesa el formulario solo si no está bloqueado y el método de la solicitud es POST.
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---- RECOLECCIÓN Y LIMPIEZA DE DATOS DEL FORMULARIO ----

    // Obtiene el nombre de usuario y elimina espacios en blanco al inicio y al final.
    $username = trim($_POST['username'] ?? '');
    // Obtiene la contraseña y elimina espacios en blanco.
    $password = trim($_POST['password'] ?? '');
    // Obtiene el token CSRF enviado desde el formulario.
    $csrf_token = $_POST['csrf_token'] ?? '';

    // ---- VALIDACIÓN DE DATOS ----

    // 1. Verifica que los campos no estén vacíos.
    if (!$username || !$password || !$csrf_token) {
        $mensaje = "Completa todos los campos.";
        // 2. Compara el token del formulario con el de la sesión para prevenir ataques CSRF.
    } elseif (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $mensaje = "Solicitud inválida (token CSRF no coincide).";
        // 3. Valida la longitud máxima del nombre de usuario.
    } elseif (strlen($username) > 50) {
        $mensaje = "El nombre de usuario no puede superar los 50 caracteres.";
        // 4. Valida el formato del nombre de usuario (solo alfanuméricos y guion bajo).
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $mensaje = "El usuario solo debe contener letras, números y guiones bajos.";
        // 5. Valida la longitud de la contraseña (entre 8 y 72 caracteres).
    } elseif (strlen($password) < 8 || strlen($password) > 72) {
        $mensaje = "La contraseña debe tener entre 8 y 72 caracteres.";

        // ---- PROCESO DE AUTENTICACIÓN ----

    } else {
        // Prepara la consulta SQL para buscar al usuario. Se usan sentencias preparadas para prevenir inyección SQL.
        $sql = "SELECT * FROM usuarios WHERE username = ?";
        $stmt = $conexionBD->prepare($sql);
        // Ejecuta la consulta pasando el nombre de usuario como parámetro.
        $stmt->execute([$username]);
        // Obtiene el resultado de la consulta como un array asociativo.
        $usuario = $stmt->fetch();

        // Verifica si se encontró un usuario y si la contraseña proporcionada coincide con el hash almacenado.
        if ($usuario && password_verify($password, $usuario['password'])) {

            // ---- VERIFICACIÓN DE ESTATUS DEL USUARIO ----
            // Una vez que las credenciales son correctas, verifica si el usuario está activo.
            if ($usuario['status'] == 1) {
                // ---- AUTENTICACIÓN EXITOSA ----

                // Regenera el ID de la sesión para prevenir ataques de fijación de sesión.
                session_regenerate_id(true);

                // Almacena los datos del usuario en la sesión.
                $_SESSION['usuario'] = $usuario['username'];
                $_SESSION['rol'] = $usuario['rol'];

                // Reinicia el contador de intentos fallidos.
                $_SESSION['login_attempts'] = 0;

                // Define la página de redirección según el rol del usuario.
                // Línea(Corregida)
                $redirect = $usuario['rol'] === 'gerente' ? "../src/controllers/admin/index.php" : "../src/controllers/seller/index.php";
                // Redirige al usuario a su panel correspondiente.
                header("Location: $redirect");
                // Detiene la ejecución del script.
                exit;
            } else {
                // ---- USUARIO INACTIVO ----
                // Las credenciales son correctas, pero el usuario está inactivo (status = 0).
                $mensaje = "Tu cuenta está inactiva. Contacta al administrador.";
            }
        } else {
            // ---- AUTENTICACIÓN FALLIDA ----

            // Incrementa el contador de intentos fallidos.
            $_SESSION['login_attempts']++;
            // Actualiza el tiempo del último intento.
            $_SESSION['last_attempt_time'] = time();
            // Establece un mensaje de error genérico para no dar pistas a atacantes.
            $mensaje = "Usuario o contraseña incorrectos.";
        }
    }
}
require_once __DIR__ . '/../templates/login_view.php';
