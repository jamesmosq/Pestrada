<?php
include("db.php");

// Solo se acepta POST: eliminar con un enlace (GET) es peligroso,
// porque cualquier link o el propio navegador podria borrar datos.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id   = (int) $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM tareas WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $_SESSION['message']      = 'Tarea eliminada';
    $_SESSION['message_type'] = 'danger';
}

header("Location: index.php");
exit;
