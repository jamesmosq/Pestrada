<?php

// Marca que la peticion entro por el router: las vistas lo exigen
define('DESDE_ROUTER', true);

// La sesion guarda el token CSRF y los mensajes flash
session_start();

require_once __DIR__ . '/controllers/EstudianteController.php';
require_once __DIR__ . '/controllers/CursoController.php';

// Lista blanca: controlador => acciones permitidas
// (En Laravel esto es el archivo routes/web.php)
$rutas = [
    'estudiantes' => [EstudianteController::class,
                      ['index', 'ver', 'crear', 'guardar', 'editar', 'actualizar', 'eliminar']],
    'cursos'      => [CursoController::class,
                      ['index', 'guardar', 'eliminar']],
];

$c      = $_GET['c']      ?? 'estudiantes';
$action = $_GET['action'] ?? 'index';

if (isset($rutas[$c]) && in_array($action, $rutas[$c][1], true)) {
    [$clase] = $rutas[$c];
    try {
        $controller = new $clase();
        $controller->$action();
    } catch (PDOException $e) {
        // 42S02 = tabla no existe, 42S22 = columna no existe: falta ejecutar migracion.sql
        if (in_array($e->getCode(), ['42S02', '42S22'], true)) {
            http_response_code(500);
            exit('<h2>Falta preparar la base de datos</h2>
                  <p>Ejecuta el archivo <code>migracion.sql</code> de esta carpeta en phpMyAdmin
                  (pestana SQL) y recarga la pagina.</p>
                  <p><small>Detalle: ' . htmlspecialchars($e->getMessage()) . '</small></p>');
        }
        throw $e;
    }
} else {
    header('Location: index.php');
    exit;
}
