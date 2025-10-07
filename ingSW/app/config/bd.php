<?php

class BD {
    public static $instancia = null;

    public static function crearInstancia() {
        if (!isset(self::$instancia)) {
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                // Intentamos crear la conexión a la base de datos
                self::$instancia = new PDO('mysql:host=localhost;dbname=itemcontrol', 'root', '', $opciones);

            } catch (PDOException $e) {
                // Si la conexión falla, atrapamos el error aquí
                
                // Guardamos el error real en el log del servidor para que solo tú lo veas
                error_log("Error de conexión: " . $e->getMessage());
                
                // Mostramos un mensaje genérico al usuario y detenemos todo
                die("<h3>El sitio no está disponible por el momento. Intente más tarde.</h3>");
            }
        }
        return self::$instancia;
    }
}
?>