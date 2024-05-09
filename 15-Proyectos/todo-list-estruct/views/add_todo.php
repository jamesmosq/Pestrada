<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Tarea</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Tarea</h1>
        <form action="index.php?action=add" method="post">
            <input type="text" name="task" required placeholder="Ingresa una nueva tarea">
            <input type="submit" value="Agregar">
        </form>
        <a href="index.php">Volver a la lista</a>
    </div>
</body>
</html>