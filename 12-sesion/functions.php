<?php
/**
 * Funciones de Sesión y Autenticación Mejoradas
 * Implementa mejores prácticas de seguridad
 */

// Cargar configuración centralizada
require_once __DIR__ . '/../config.php';

/**
 * Verifica si el usuario está logueado
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Verifica las credenciales del usuario contra la base de datos
 * @param string $username
 * @param string $password
 * @return array|false Retorna datos del usuario o false
 */
function verifyCredentials($username, $password) {
    try {
        $db = getDBConnection(DB_NAME_LOGIN);

        // Preparar consulta para prevenir inyección SQL
        $stmt = $db->prepare("SELECT id, username, password, nombre_completo, email
                              FROM usuarios
                              WHERE username = :username AND activo = 1
                              LIMIT 1");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar que el usuario existe y la contraseña es correcta
        if ($user && password_verify($password, $user['password'])) {
            // Actualizar último acceso
            updateLastAccess($user['id']);

            return $user;
        }

        return false;

    } catch (PDOException $e) {
        error_log("Error en verifyCredentials: " . $e->getMessage());
        return false;
    }
}

/**
 * Registra el inicio de sesión y configura las variables de sesión
 * @param array $user Datos del usuario
 * @return bool
 */
function loginUser($user) {
    // Regenerar ID de sesión para prevenir fijación de sesión
    session_regenerate_id(true);

    // Guardar información del usuario en la sesión
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nombre_completo'] = $user['nombre_completo'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['login_time'] = time();
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    return true;
}

/**
 * Actualiza el último acceso del usuario
 * @param int $userId
 */
function updateLastAccess($userId) {
    try {
        $db = getDBConnection(DB_NAME_LOGIN);
        $stmt = $db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error en updateLastAccess: " . $e->getMessage());
    }
}

/**
 * Cierra la sesión del usuario
 */
function logoutUser() {
    // Limpiar todas las variables de sesión
    $_SESSION = [];

    // Destruir la cookie de sesión
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // Destruir la sesión
    session_destroy();
}

/**
 * Requiere autenticación - redirige al login si no está logueado
 * @param string $redirectUrl URL de redirección después del login
 */
function requireAuth($redirectUrl = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirectUrl");
        exit;
    }
}

/**
 * Verifica la validez de la sesión (timeout y seguridad)
 * @return bool
 */
function validateSession() {
    // Verificar que la sesión existe
    if (!isLoggedIn()) {
        return false;
    }

    // Verificar timeout de sesión (por defecto 1 hora)
    if (isset($_SESSION['login_time'])) {
        $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
        if (time() - $_SESSION['login_time'] > $sessionLifetime) {
            logoutUser();
            return false;
        }
    }

    // Verificar que la IP no ha cambiado (opcional, puede ser problemático con IPs dinámicas)
    // if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    //     logoutUser();
    //     return false;
    // }

    return true;
}

/**
 * Sanitiza la entrada del usuario
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Valida el formato de email
 * @param string $email
 * @return bool
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Registra un nuevo usuario
 * @param string $username
 * @param string $email
 * @param string $password
 * @param string $nombreCompleto
 * @return bool|string true si éxito, mensaje de error si falla
 */
function registerUser($username, $email, $password, $nombreCompleto = '') {
    try {
        // Validaciones
        if (empty($username) || empty($email) || empty($password)) {
            return "Todos los campos son obligatorios";
        }

        if (!validateEmail($email)) {
            return "Email inválido";
        }

        if (strlen($password) < 6) {
            return "La contraseña debe tener al menos 6 caracteres";
        }

        $db = getDBConnection(DB_NAME_LOGIN);

        // Verificar si el usuario ya existe
        $stmt = $db->prepare("SELECT id FROM usuarios WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            return "El usuario o email ya existe";
        }

        // Hashear la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertar nuevo usuario
        $stmt = $db->prepare("INSERT INTO usuarios (username, email, password, nombre_completo)
                              VALUES (:username, :email, :password, :nombre_completo)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':nombre_completo', $nombreCompleto);

        if ($stmt->execute()) {
            return true;
        }

        return "Error al registrar usuario";

    } catch (PDOException $e) {
        error_log("Error en registerUser: " . $e->getMessage());
        return "Error del sistema. Intenta más tarde.";
    }
}

/**
 * Muestra el formulario de login con protección CSRF
 * @param string $error Mensaje de error opcional
 */
function displayLoginForm($error = '') {
    // Generar token CSRF
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 400px;
                margin: 50px auto;
                padding: 20px;
            }
            .form-group {
                margin-bottom: 15px;
            }
            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            input[type="text"],
            input[type="password"] {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-sizing: border-box;
            }
            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                width: 100%;
            }
            input[type="submit"]:hover {
                background-color: #45a049;
            }
            .error {
                color: red;
                margin-bottom: 15px;
                padding: 10px;
                background-color: #ffebee;
                border-radius: 4px;
            }
        </style>
    </head>
    <body>
        <h2>Iniciar Sesión</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo escape($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="form-group">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <input type="submit" value="Iniciar sesión">
        </form>
    </body>
    </html>
    <?php
}

/**
 * Verifica el token CSRF
 * @param string $token
 * @return bool
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
