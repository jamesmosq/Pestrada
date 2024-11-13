<?php
// Iniciar la sesión
session_start();

// Incluir el archivo de funciones
require_once 'C:\xampp\htdocs\Pestrada\12-sesion\functions.php';

// Procesar el inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (verifyCredentials($username, $password)) {
        // Iniciar sesión y redirigir al usuario
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    } else {
        echo "Credenciales incorrectas.";
    }
}