<?php

include("db.php");

if(isset($_GET['id'])) {
    $id= $_GET['id'];
    $query = "DELETE FROM tareas WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if (!$result){
        die("Eliminación fallida");
    }

    $_SESSION['message'] = 'Tarea eliminada';
    $_SESSION['message_type'] = 'danger';   
    header("Location: index.php");
}




?>