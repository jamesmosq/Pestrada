<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Tareas</h1>
        <a href="index.php?action=add" class="add-task-link">Agregar Tarea</a>
        <?php if(!empty($todos)): ?>
            <ul>
            <?php foreach ($todos as $todo): ?>
                <li>
                    <span class="task-text"><?php echo htmlspecialchars($todo['task']); ?></span>
                    <div class="task-actions">
                        <a href="index.php?action=toggle&id=<?php echo $todo['id']; ?>&status=<?php echo $todo['is_completed']; ?>">
                            <?php echo $todo['is_completed'] ? 'Desmarcar' : 'Marcar'; ?>
                        </a>
                        <a href="index.php?action=delete&id=<?php echo $todo['id']; ?>">Eliminar</a>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No hay tareas pendientes.</p>
        <?php endif; ?>
    </div>
</body>
</html>