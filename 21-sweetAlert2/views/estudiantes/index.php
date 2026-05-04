<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Estudiantes — SENA</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>

<div class="container">
    <div class="card">

        <div class="page-header">
            <h2>Gestion de Estudiantes</h2>
            <a href="index.php?action=crear" class="btn btn--primary">
                <i class="fa-solid fa-user-plus"></i> Nuevo estudiante
            </a>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Ficha</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($estudiantes)): ?>
                <tr>
                    <td colspan="5" class="empty-row">
                        <i class="fa-solid fa-circle-info"></i>
                        No hay estudiantes registrados.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($estudiantes as $est): ?>
                    <tr>
                        <td><?php echo $est['id']; ?></td>
                        <td><?php echo htmlspecialchars($est['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($est['email']); ?></td>
                        <td>
                            <span class="badge">
                                <?php echo htmlspecialchars($est['ficha']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="index.php?action=editar&id=<?php echo $est['id']; ?>"
                                   class="btn btn--warning btn--sm">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <button class="btn btn--danger btn--sm"
                                        onclick="confirmarEliminar(
                                        <?php echo $est['id']; ?>,
                                                '<?php echo addslashes($est['nombre']); ?>'
                                                )">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<script>
    // ── Mensajes flash desde el Controller ──────────────────────────────────
    const status  = "<?php echo $status; ?>";
    const mensaje = <?php echo json_encode($mensaje); ?>;

    const alertas = {
        creado:    { icon: 'success', title: 'Estudiante creado'  },
        editado:   { icon: 'success', title: 'Datos actualizados' },
        eliminado: { icon: 'info',    title: 'Registro eliminado' },
        error:     { icon: 'error',   title: 'Error'              },
    };

    if (status && alertas[status]) {
        Swal.fire({
            icon:              alertas[status].icon,
            title:             alertas[status].title,
            text:              mensaje,
            timer:             status === 'error' ? undefined : 2500,
            timerProgressBar:  status !== 'error',
            showConfirmButton: status === 'error',
        });
    }

    // ── Confirmacion antes de eliminar ───────────────────────────────────────
    function confirmarEliminar(id, nombre) {
        Swal.fire({
            icon:              'warning',
            title:             'Eliminar estudiante',
            html:              `Seguro que deseas eliminar a <strong>${nombre}</strong>?
                               <br><small>Esta accion no se puede deshacer.</small>`,
            showCancelButton:  true,
            confirmButtonText: 'Si, eliminar',
            cancelButtonText:  'Cancelar',
            confirmButtonColor:'#d9534f',
            cancelButtonColor: '#6c757d',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `index.php?action=eliminar&id=${id}`;
            }
        });
    }
</script>

</body>
</html>