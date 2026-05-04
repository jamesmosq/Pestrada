<?php
/**
 * Archivo de conexión a base de datos para Miniproyecto Login
 * Mejorado para usar configuración centralizada y mejores prácticas
 */

// Cargar configuración centralizada
require_once __DIR__ . '/../config.php';

try {
    // Crear conexión PDO usando la función helper del config
    $conn = getDBConnection(DB_NAME_LOGIN);

} catch(PDOException $e) {
    // Log del error (no mostrar detalles al usuario en producción)
    error_log("Error de conexión PDO: " . $e->getMessage());
    die("Error al conectar con la base de datos. Por favor, intenta más tarde.");
}
?>
