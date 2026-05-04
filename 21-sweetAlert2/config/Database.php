
<?php

class Database
{
    // Datos de conexion
    private static string $host    = 'localhost';
    private static string $db      = 'sena_mvc';
    private static string $user    = 'root';
    private static string $pass    = 'base1234';
    private static string $charset = 'utf8';

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
