<?php
class TodoController {
    private $db;
    private $todoItem;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->todoItem = new TodoItem($db);
    }

    public function index() {
        $result = $this->todoItem->read();
        $todos = $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
        include 'views/todo_list.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->todoItem->task = $_POST['task'];
            $this->todoItem->is_completed = 0;
            if ($this->todoItem->create()) {
                header("Location: index.php");
                exit();
            }
        }
        include 'views/add_todo.php';
    }

    // Las acciones que modifican datos solo se aceptan por POST
    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
            $this->todoItem->id = (int) $_POST['id'];
            $this->todoItem->is_completed = ($_POST['status'] == '1') ? 0 : 1;
            $this->todoItem->updateStatus();
        }
        header("Location: index.php");
        exit();
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->todoItem->id = (int) $_POST['id'];
            $this->todoItem->delete();
        }
        header("Location: index.php");
        exit();
    }
}