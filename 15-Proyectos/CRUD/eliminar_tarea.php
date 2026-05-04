<?php
include("db.php");

if (isset($_GET['id'])) {
    $id   = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM tareas WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $_SESSION['message']      = 'Tarea eliminada';
    $_SESSION['message_type'] = 'danger';
    header("Location: index.php");
    exit;
}
