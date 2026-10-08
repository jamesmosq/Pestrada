<?php
include("db.php");

if (isset($_POST['guardar_tarea'])) {
    $titulo      = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conn->prepare("INSERT INTO tareas (titulo, descripcion) VALUES (:titulo, :descripcion)");
    $stmt->execute([':titulo' => $titulo, ':descripcion' => $descripcion]);

    $_SESSION['message']      = 'Tarea guardada satisfactoriamente';
    $_SESSION['message_type'] = 'success';
}

// Siempre redirigir y terminar el script con exit
header("Location: index.php");
exit;
