<?php
require_once 'config/Database.php';
require_once 'models/TodoItem.php';
require_once 'controllers/TodoController.php';

$controller = new TodoController();

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'add':
        $controller->add();
        break;
    // toggle y delete leen sus datos de $_POST dentro del controlador
    case 'toggle':
        $controller->toggle();
        break;
    case 'delete':
        $controller->delete();
        break;
    default:
        $controller->index();
        break;
}