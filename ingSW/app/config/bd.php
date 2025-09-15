<?php

class BD {
    public static $instancia = null;

    public static function crearInstancia() {
        if (!isset(self::$instancia)) {
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            self::$instancia = new PDO('mysql:host=localhost;dbname=itemcontrol', 'root', '', $opciones);
            // Puedes quitar el echo para producción
            // echo "Conexión exitosa a la base de datos.";
        }
        return self::$instancia;
    }
}
?>
