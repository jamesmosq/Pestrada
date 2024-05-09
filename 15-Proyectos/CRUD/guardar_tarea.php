<?php

include("db.php");
echo "probando";
if (isset($_POST['guardar_tarea'])){
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
      
    $query = "INSERT INTO tareas (titulo, descripcion) VALUES ('$titulo', '$descripcion')";
    $result = mysqli_query($conn, $query);
    if (!$result){
        die("Consulta fallida");
    }



    
    $_SESSION['message'] = 'Tarea guardada satisfactoriamente';
    $_SESSION['message_type'] = 'success';    
    header("location: index.php");
}


?>