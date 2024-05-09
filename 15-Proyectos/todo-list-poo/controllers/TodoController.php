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

    public function toggle($id) {
        $this->todoItem->id = $id;
        $this->todoItem->is_completed = ($_GET['status'] == '1') ? 0 : 1;
        if ($this->todoItem->update()) {
            header("Location: index.php");
            exit();
        }
    }

    public function delete($id) {
        $this->todoItem->id = $id;
        if ($this->todoItem->delete()) {
            header("Location: index.php");
            exit();
        }
    }
}