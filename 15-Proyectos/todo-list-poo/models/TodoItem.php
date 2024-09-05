<?php
class TodoItem {
    private $conn;
    private $table_name = "todos";

    public $id;
    public $task;
    public $is_completed;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (task, is_completed) VALUES (:task, :is_completed)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":task", $this->task);
        $stmt->bindParam(":is_completed", $this->is_completed);
        return $stmt->execute();
    }

    public function read() {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error al leer las tareas: " . $e->getMessage();
            return false;
        }
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET task = :task, is_completed = :is_completed WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":task", $this->task);
        $stmt->bindParam(":is_completed", $this->is_completed);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}