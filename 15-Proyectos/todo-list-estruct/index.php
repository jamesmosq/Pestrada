<?php
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (addTodo($conn, $_POST['task'])) {
                header("Location: index.php");
                exit();
            }
        }
        include 'views/add_todo.php';
        break;
    // Las acciones que MODIFICAN datos solo se aceptan por POST
    case 'toggle':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
            toggleTodo($conn, (int) $_POST['id'], $_POST['status']);
        }
        header("Location: index.php");
        exit();
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            deleteTodo($conn, (int) $_POST['id']);
        }
        header("Location: index.php");
        exit();
    default:
        $todos = getTodos($conn);
        include 'views/todo_list.php';
        break;
}