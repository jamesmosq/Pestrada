<?php
/**
 * Conexión a base de datos para CRUD de Tareas - PDO
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config.php';

$conn = getDBConnection(DB_NAME_TAREAS);
