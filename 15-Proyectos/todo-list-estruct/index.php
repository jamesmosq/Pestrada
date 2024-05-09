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
    case 'toggle':
        if (isset($_GET['id']) && isset($_GET['status'])) {
            toggleTodo($conn, $_GET['id'], $_GET['status']);
        }
        header("Location: index.php");
        break;
    case 'delete':
        if (isset($_GET['id'])) {
            deleteTodo($conn, $_GET['id']);
        }
        header("Location: index.php");
        break;
    default:
        $todos = getTodos($conn);
        include 'views/todo_list.php';
        break;
}