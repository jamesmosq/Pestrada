<?php
/**
 * Página de Logout
 * Cierra la sesión del usuario de forma segura
 */

// Iniciar la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir funciones
require_once __DIR__ . '/functions.php';

// Cerrar sesión usando la función mejorada
logoutUser();

// Redirigir al login con mensaje
header('Location: login.php?mensaje=sesion_cerrada');
exit;
?>