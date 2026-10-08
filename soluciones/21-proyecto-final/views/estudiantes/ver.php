<?php
// Las vistas solo se cargan a traves del router (index.php).
// Si alguien abre este archivo directo en el navegador, lo enviamos a la aplicacion.
if (!defined('DESDE_ROUTER')) {
    header('Location: ../../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($estudiante['nombre']); ?> — SENA</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>

<div class="container--narrow">
    <div class="card">

        <div class="page-header">
            <h2><i class="fa-solid fa-id-card"></i> <?php echo htmlspecialchars($estudiante['nombre']); ?></h2>
        </div>

        <dl class="detalle">
            <dt>Email</dt>
            <dd><?php echo htmlspecialchars($estudiante['email']); ?></dd>

            <dt>Ficha</dt>
            <dd><?php echo htmlspecialchars($estudiante['ficha'] ?: '—'); ?></dd>

            <dt>Celular</dt>
            <dd><?php echo htmlspecialchars($estudiante['telefono'] ?? '—'); ?></dd>

            <dt>Curso</dt>
            <dd><?php echo htmlspecialchars($estudiante['curso'] ?? 'Sin curso'); ?></dd>

            <dt>Registrado</dt>
            <dd><?php echo date('d/m/Y H:i', strtotime($estudiante['created_at'])); ?></dd>
        </dl>

        <div class="form-actions">
            <a href="index.php?c=estudiantes&action=editar&id=<?php echo (int) $estudiante['id']; ?>"
               class="btn btn--warning">
                <i class="fa-solid fa-pen-to-square"></i> Editar
            </a>
            <a href="index.php" class="btn btn--secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

    </div>
</div>

</body>
</html>
