<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
<h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
<p>Esta es una página protegida.</p>
<a href="logout.php">Cerrar Sesión</a>
</body>
</html>
