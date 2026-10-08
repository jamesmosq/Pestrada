<?php

// Marca que la peticion entro por el router: las vistas lo exigen
define('DESDE_ROUTER', true);

// La sesion guarda el token CSRF (en Laravel esto lo hace @csrf)
session_start();

require_once __DIR__ . '/controllers/EstudianteController.php';

$action = $_GET['action'] ?? 'index';

$controller = new EstudianteController();

// Solo estas acciones son validas (lista blanca de seguridad)
$accionesPermitidas = [
    'index'      => 'index',
    'crear'      => 'crear',
    'guardar'    => 'guardar',
    'editar'     => 'editar',
    'actualizar' => 'actualizar',
    'eliminar'   => 'eliminar',
];

if (array_key_exists($action, $accionesPermitidas)) {
    $metodo = $accionesPermitidas[$action];
    $controller->$metodo();
} else {
    header('Location: index.php?action=index');
    exit;
}
