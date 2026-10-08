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
                        <!-- Marcar y eliminar modifican datos: se envian por POST, no con enlaces -->
                        <form action="index.php?action=toggle" method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo (int) $todo['id']; ?>">
                            <input type="hidden" name="status" value="<?php echo (int) $todo['is_completed']; ?>">
                            <button type="submit" class="link-button">
                                <?php echo $todo['is_completed'] ? 'Desmarcar' : 'Marcar'; ?>
                            </button>
                        </form>
                        <form action="index.php?action=delete" method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?php echo (int) $todo['id']; ?>">
                            <button type="submit" class="link-button">Eliminar</button>
                        </form>
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