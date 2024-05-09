<?php
// Iniciar sesión
session_start();

// Asignar un valor a una variable de sesión
$_SESSION['usuario'] = 'Juan';

// Acceder al valor de la variable de sesión en otra página
echo $_SESSION['usuario']; // Output: Juan

