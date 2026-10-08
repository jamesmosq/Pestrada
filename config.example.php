<?php
// PLANTILLA: copia este archivo como config.php y ajusta DB_PASS.
// config.php está en .gitignore para que cada quien tenga su propia contraseña.
/**
 * Archivo de Configuración Central
 * Aquí se definen todas las constantes y configuraciones del proyecto
 */

// Configuración de la Base de Datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Contraseña de MySQL en TU equipo (en WAMP suele ser vacía)
define('DB_NAME_TAREAS', 'tareas_crud');
define('DB_NAME_TODO', 'todo_list');
define('DB_NAME_LOGIN', 'login_db');
define('DB_NAME_MVC', 'sena_mvc');
define('DB_CHARSET', 'utf8mb4');

// Configuración de Sesiones
define('SESSION_LIFETIME', 3600); // 1 hora en segundos
define('SESSION_NAME', 'PESTRADA_SESSION');

// Configuración de la Aplicación
define('APP_NAME', 'Pestrada - Curso PHP');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/Pestrada/');
define('ENVIRONMENT', 'development'); // Cambiar a 'production' en servidor real

// Configuración de Zona Horaria
date_default_timezone_set('America/Bogota'); // Ajustar según ubicación

// Configuración de Errores (DESARROLLO)
// En producción, cambiar a:
// error_reporting(0);
// ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// Configuración de Sesión Segura
// Solo se puede configurar ANTES de session_start(); si la sesión ya
// está activa, ini_set() lanza un warning que rompe los header().
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Cambiar a 1 si usas HTTPS
}

// Autoload de clases (opcional, para proyectos más grandes)
spl_autoload_register(function ($class) {
    $directories = [
        __DIR__ . '/15-Proyectos/todo-list-poo/models/',
        __DIR__ . '/15-Proyectos/todo-list-poo/controllers/',
        __DIR__ . '/15-Proyectos/todo-list-poo/config/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

/**
 * Función auxiliar para conexión PDO
 * @param string $dbName Nombre de la base de datos
 * @return PDO
 */
function getDBConnection($dbName) {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . $dbName . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        error_log("Error de conexión a BD: " . $e->getMessage());
        die("Error de conexión a la base de datos");
    }
}

/**
 * Función auxiliar para escapar HTML
 * @param string $string
 * @return string
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Función para redireccionar
 * @param string $url
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Nota: isLoggedIn() se define en 12-sesion/functions.php.
// No declararla aquí: PHP no permite dos funciones con el mismo nombre.
