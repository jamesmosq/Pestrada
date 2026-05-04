<?php
/**
 * Clase Database mejorada
 * Gestiona la conexión a la base de datos usando PDO
 * Implementa mejores prácticas de seguridad y manejo de errores
 */

// Cargar configuración centralizada
require_once __DIR__ . '/../../../config.php';

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;
    public $conn;

    /**
     * Constructor - Inicializa las credenciales desde config.php
     */
    public function __construct() {
        $this->host = DB_HOST;
        $this->db_name = DB_NAME_TODO;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->charset = DB_CHARSET;
    }

    /**
     * Establece y retorna la conexión PDO
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false // Conexiones no persistentes
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch(PDOException $exception) {
            // Log del error sin exponer detalles sensibles
            error_log("Database Connection Error: " . $exception->getMessage());

            // En producción, no mostrar detalles del error
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
                throw new Exception("Error al conectar con la base de datos");
            } else {
                throw new Exception("Error de conexión: " . $exception->getMessage());
            }
        }

        return $this->conn;
    }

    /**
     * Cierra la conexión a la base de datos
     */
    public function closeConnection() {
        $this->conn = null;
    }
}