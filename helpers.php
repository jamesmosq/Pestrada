<?php
/**
 * Funciones Helper Globales
 * Utilidades comunes para todo el proyecto
 */

/**
 * Debug - Imprime variables de forma legible (solo en desarrollo)
 * @param mixed $data
 * @param bool $die Terminar ejecución después de mostrar
 */
function debug($data, $die = false) {
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
        return; // No mostrar debug en producción
    }

    echo '<pre style="background: #f4f4f4; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">';
    print_r($data);
    echo '</pre>';

    if ($die) {
        die();
    }
}

/**
 * Dump and Die - Igual que debug pero siempre termina la ejecución
 * @param mixed $data
 */
function dd($data) {
    debug($data, true);
}

/**
 * Redirección mejorada con delay opcional
 * @param string $url URL de destino
 * @param int $delay Segundos de delay (0 = inmediato)
 */
function redirectTo($url, $delay = 0) {
    if ($delay > 0) {
        header("Refresh: $delay; url=$url");
    } else {
        header("Location: $url");
        exit;
    }
}

/**
 * Obtiene el valor de una variable GET/POST de forma segura
 * @param string $key
 * @param mixed $default Valor por defecto
 * @param string $method 'GET', 'POST', o 'REQUEST'
 * @return mixed
 */
function input($key, $default = null, $method = 'REQUEST') {
    $source = $_REQUEST;

    if ($method === 'GET') {
        $source = $_GET;
    } elseif ($method === 'POST') {
        $source = $_POST;
    }

    return isset($source[$key]) ? sanitize($source[$key]) : $default;
}

/**
 * Sanitiza una cadena de texto
 * @param string $string
 * @return string
 */
function sanitize($string) {
    if (is_array($string)) {
        return array_map('sanitize', $string);
    }

    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    return $string;
}

/**
 * Genera una URL base relativa
 * @param string $path
 * @return string
 */
function url($path = '') {
    $baseUrl = defined('BASE_URL') ? BASE_URL : '/';
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

/**
 * Genera una ruta absoluta de archivo
 * @param string $path
 * @return string
 */
function path($path = '') {
    return __DIR__ . '/' . ltrim($path, '/');
}

/**
 * Retorna el nombre de la página actual
 * @return string
 */
function currentPage() {
    return basename($_SERVER['PHP_SELF']);
}

/**
 * Verifica si estamos en una página específica
 * @param string $page
 * @return bool
 */
function isPage($page) {
    return currentPage() === $page;
}

/**
 * Formatea una fecha
 * @param string|int $date Fecha string o timestamp
 * @param string $format Formato de salida
 * @return string
 */
function formatDate($date, $format = 'd/m/Y H:i:s') {
    if (is_numeric($date)) {
        return date($format, $date);
    }
    return date($format, strtotime($date));
}

/**
 * Calcula el tiempo transcurrido de forma legible
 * @param string|int $datetime
 * @return string
 */
function timeAgo($datetime) {
    $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'hace ' . $diff . ' segundos';
    } elseif ($diff < 3600) {
        return 'hace ' . floor($diff / 60) . ' minutos';
    } elseif ($diff < 86400) {
        return 'hace ' . floor($diff / 3600) . ' horas';
    } elseif ($diff < 604800) {
        return 'hace ' . floor($diff / 86400) . ' días';
    } else {
        return formatDate($timestamp, 'd/m/Y');
    }
}

/**
 * Valida si una cadena es JSON válida
 * @param string $string
 * @return bool
 */
function isJson($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Convierte un array a JSON de forma segura
 * @param array $data
 * @param bool $pretty Pretty print
 * @return string
 */
function toJson($data, $pretty = false) {
    $options = JSON_UNESCAPED_UNICODE;
    if ($pretty) {
        $options |= JSON_PRETTY_PRINT;
    }
    return json_encode($data, $options);
}

/**
 * Limita una cadena a un número de caracteres
 * @param string $string
 * @param int $limit
 * @param string $end Terminación (ej: '...')
 * @return string
 */
function strLimit($string, $limit = 100, $end = '...') {
    if (mb_strlen($string) <= $limit) {
        return $string;
    }
    return mb_substr($string, 0, $limit) . $end;
}

/**
 * Genera un slug a partir de un string
 * @param string $string
 * @return string
 */
function slug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

/**
 * Valida un número de teléfono (formato básico)
 * @param string $phone
 * @return bool
 */
function isValidPhone($phone) {
    return preg_match('/^[0-9]{10,15}$/', preg_replace('/[^0-9]/', '', $phone));
}

/**
 * Formatea bytes a tamaño legible
 * @param int $bytes
 * @param int $precision
 * @return string
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * Genera una contraseña aleatoria
 * @param int $length
 * @return string
 */
function generatePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';

    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }

    return $password;
}

/**
 * Verifica si una URL es válida
 * @param string $url
 * @return bool
 */
function isValidUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Obtiene la IP del cliente
 * @return string
 */
function getClientIp() {
    $ipaddress = '';

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
}

/**
 * Crea un mensaje flash en la sesión
 * @param string $key
 * @param string $message
 * @param string $type success, error, warning, info
 */
function setFlash($key, $message, $type = 'info') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['flash'][$key] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Obtiene y elimina un mensaje flash
 * @param string $key
 * @return array|null
 */
function getFlash($key) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['flash'][$key])) {
        $flash = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $flash;
    }

    return null;
}

/**
 * Muestra un mensaje flash con HTML
 * @param string $key
 */
function displayFlash($key) {
    $flash = getFlash($key);

    if ($flash) {
        $colors = [
            'success' => '#4CAF50',
            'error' => '#f44336',
            'warning' => '#ff9800',
            'info' => '#2196F3'
        ];

        $color = $colors[$flash['type']] ?? $colors['info'];

        echo "<div style='padding: 15px; margin: 10px 0; background-color: {$color}20; border-left: 4px solid {$color}; border-radius: 4px;'>";
        echo "<strong style='color: {$color};'>" . ucfirst($flash['type']) . ":</strong> ";
        echo escape($flash['message']);
        echo "</div>";
    }
}

/**
 * Paginación simple
 * @param int $total Total de registros
 * @param int $perPage Registros por página
 * @param int $currentPage Página actual
 * @return array [offset, limit, totalPages, currentPage]
 */
function paginate($total, $perPage = 10, $currentPage = 1) {
    $totalPages = ceil($total / $perPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;

    return [
        'offset' => $offset,
        'limit' => $perPage,
        'total_pages' => $totalPages,
        'current_page' => $currentPage,
        'total_records' => $total
    ];
}
