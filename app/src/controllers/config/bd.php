<?php

class BD {
    public static $instancia = null;

    public static function crearInstancia() {
        if (!isset(self::$instancia)) {
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            // --- INICIO DE MODIFICACIÓN ---
            // Lee las variables de entorno de Docker (o usa los valores de XAMPP como respaldo)
            $host = getenv('DB_HOST') ?: 'localhost';
            $dbname = getenv('DB_DATABASE') ?: 'itemcontrol';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASSWORD') ?: ''; // La contraseña de root en XAMPP suele ser vacía
            $dsn = "mysql:host=$host;dbname=$dbname";
            // --- FIN DE MODIFICACIÓN ---

            try {
                // Intentamos crear la conexión a la base de datos
                self::$instancia = new PDO($dsn, $user, $pass, $opciones);

            } catch (PDOException $e) {
                // Si la conexión falla, atrapamos el error aquí
                error_log("Error de conexión: " . $e->getMessage());
                die("<h3>El sitio no está disponible por el momento. Intente más tarde.</h3>");
            }
        } // <-- Llave de cierre del "if"

        // El return va DENTRO del método
        return self::$instancia;

    } // <-- Llave de cierre del método "crearInstancia"
} // <-- Llave de cierre de la "class BD"
?>