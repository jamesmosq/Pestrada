<?php
/**
 * Página de Login Mejorada
 * Implementa autenticación segura con protección CSRF
 */

// Iniciar la sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de funciones mejorado
require_once __DIR__ . '/functions.php';

// Si ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

// Procesar el inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        $error = "Token de seguridad inválido. Por favor, intenta de nuevo.";
    } else {
        // Sanitizar entradas
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validar que no estén vacíos
        if (empty($username) || empty($password)) {
            $error = "Por favor, completa todos los campos.";
        } else {
            // Verificar credenciales
            $user = verifyCredentials($username, $password);

            if ($user) {
                // Iniciar sesión y redirigir al usuario
                loginUser($user);
                header('Location: index.php');
                exit;
            } else {
                $error = "Credenciales incorrectas. Por favor, verifica tu usuario y contraseña.";

                // Log de intento fallido (para auditoría)
                error_log("Intento de login fallido para usuario: $username desde IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
            }
        }
    }
}

// Mostrar formulario de login
displayLoginForm($error);