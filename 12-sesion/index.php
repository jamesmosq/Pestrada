<?php
/**
 * Página Principal / Dashboard
 * Requiere autenticación
 */

// Iniciar la sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de funciones
require_once __DIR__ . '/functions.php';

// Validar sesión (verificar timeout y seguridad)
if (!validateSession()) {
    header('Location: login.php');
    exit;
}

// Requiere que el usuario esté autenticado
requireAuth();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pestrada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .user-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .user-info p {
            margin: 5px 0;
        }
        .logout-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
        .session-info {
            font-size: 0.9em;
            color: #666;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido al Dashboard</h1>

        <div class="user-info">
            <h2>Información del Usuario</h2>
            <p><strong>Usuario:</strong> <?php echo escape($_SESSION['username']); ?></p>
            <p><strong>Nombre:</strong> <?php echo escape($_SESSION['nombre_completo'] ?? 'No especificado'); ?></p>
            <p><strong>Email:</strong> <?php echo escape($_SESSION['email'] ?? 'No especificado'); ?></p>
        </div>

        <p>Has iniciado sesión exitosamente. Desde aquí puedes acceder a todas las funcionalidades de la aplicación.</p>

        <a href="logout.php" class="logout-btn">Cerrar Sesión</a>

        <div class="session-info">
            <p><strong>Información de Sesión:</strong></p>
            <p>Hora de inicio: <?php echo date('d/m/Y H:i:s', $_SESSION['login_time'] ?? time()); ?></p>
            <p>IP: <?php echo escape($_SESSION['ip_address'] ?? 'Desconocida'); ?></p>
        </div>
    </div>
</body>
</html>