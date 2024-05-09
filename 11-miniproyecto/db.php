<?php
$host = 'localhost';
$dbname = 'login_db';
$username = 'root';  // Cambia esto según tu configuración
$password = '';      // Cambia esto según tu configuración

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    die();
}
?>
