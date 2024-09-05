<?php
function getTodos($conn) {
    try {
        $query = "SELECT * FROM todos";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error al leer las tareas: " . $e->getMessage();
        return [];
    }
}

function addTodo($conn, $task) {
    $query = "INSERT INTO todos (task, is_completed) VALUES (:task, :is_completed)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":task", $task);
    $is_completed = false;
    $stmt->bindParam(":is_completed", $is_completed);
    return $stmt->execute();
}

function toggleTodo($conn, $id, $status) {
    $query = "UPDATE todos SET is_completed = :is_completed WHERE id = :id";
    $stmt = $conn->prepare($query);
    $new_status = $status == '1' ? 0 : 1;
    $stmt->bindParam(":is_completed", $new_status);
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}

function deleteTodo($conn, $id) {
    $query = "DELETE FROM todos WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}