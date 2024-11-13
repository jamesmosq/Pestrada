<?php
// Iniciar la sesión
session_start();

// Incluir el archivo de funciones
require_once 'C:\xampp\htdocs\Pestrada\12-sesion\functions.php';

// Comprobar si el usuario ha iniciado sesión
if (isLoggedIn()) {
    echo "Bienvenido, " . $_SESSION['username'] . "!";
    echo "<a href='logout.php'>Cerrar sesión</a>";
} else {
    // Mostrar el formulario de inicio de sesión
    displayLoginForm();
}
?>