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
    <title>Cursos — SENA</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>

<div class="container--narrow">
    <div class="card">

        <div class="page-header">
            <h2>Cursos</h2>
            <a href="index.php" class="btn btn--secondary">
                <i class="fa-solid fa-users"></i> Estudiantes
            </a>
        </div>

        <!-- Crear curso -->
        <form class="search" action="index.php?c=cursos&action=guardar" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
            <input type="text" name="nombre" class="form-control" placeholder="Nombre del nuevo curso" required>
            <button type="submit" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Agregar</button>
        </form>

        <table class="table">
            <thead>
            <tr><th>Curso</th><th>Estudiantes</th><th></th></tr>
            </thead>
            <tbody>
            <?php if (empty($cursos)): ?>
                <tr><td colspan="3" class="empty-row">No hay cursos.</td></tr>
            <?php endif; ?>
            <?php foreach ($cursos as $curso): ?>
                <tr>
                    <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                    <td><span class="badge"><?php echo (int) $curso['total_estudiantes']; ?></span></td>
                    <td>
                        <form action="index.php?c=cursos&action=eliminar" method="POST"
                              onsubmit="return confirmarEliminarCurso(event, this, <?php echo (int) $curso['total_estudiantes']; ?>)">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
                            <input type="hidden" name="id" value="<?php echo (int) $curso['id']; ?>">
                            <button type="submit" class="btn btn--danger btn--sm">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

<?php require __DIR__ . '/../partials/flash.php'; ?>

<script>
    function confirmarEliminarCurso(evento, form, total) {
        evento.preventDefault();
        Swal.fire({
            icon:              'warning',
            title:             'Eliminar curso',
            text:              total > 0
                                 ? `Tiene ${total} estudiante(s). Quedaran sin curso.`
                                 : 'El curso no tiene estudiantes.',
            showCancelButton:  true,
            confirmButtonText: 'Eliminar',
            cancelButtonText:  'Cancelar',
            confirmButtonColor:'#d9534f',
        }).then((r) => {
            if (r.isConfirmed) {
                form.submit();   // submit() no vuelve a disparar onsubmit
            }
        });
        return false;
    }
</script>

</body>
</html>
