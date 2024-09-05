<?php

session_start();

$conn = mysqli_connect(
    'localhost',
    'root',   // USUARIO
    '',        //CONTRASEÑA
    'tareas_crud' // BASE DE DATOS
);

?>