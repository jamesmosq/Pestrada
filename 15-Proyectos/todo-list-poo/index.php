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
    case 'toggle':
        $controller->toggle($_GET['id']);
        break;
    case 'delete':
        $controller->delete($_GET['id']);
        break;
    default:
        $controller->index();
        break;
}