<?php
// IMPORTANTE: no dejar lineas en blanco antes de "<?php".
// Cualquier salida antes de header() provoca "headers already sent".

// Las credenciales viven en config.php (raiz del repo), no en el codigo.
// Es la misma idea que el archivo .env de Laravel.
require_once __DIR__ . '/../../../config.php';

class Database
{
    // Datos de conexion (tomados de config.php)
    private static string $host    = DB_HOST;
    private static string $db      = DB_NAME_MVC;
    private static string $user    = DB_USER;
    private static string $pass    = DB_PASS;
    private static string $charset = DB_CHARSET;

    // Guarda la unica instancia de conexion (Singleton)
    private static ?PDO $connection = null;

    // Constructor privado: impide hacer "new Database()" desde afuera
    private function __construct() {}

    // Unico punto de acceso a la conexion
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {

            $dsn = "mysql:host=" . self::$host
                . ";dbname="   . self::$db
                . ";charset="  . self::$charset;

            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            self::$connection = new PDO($dsn, self::$user, self::$pass, $opciones);
        }

        return self::$connection;
    }
}
