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
                                <!-- Los datos viajan en atributos data-* escapados,
                                     no incrustados dentro del JavaScript -->
                                <button type="button"
                                        class="btn btn--danger btn--sm"
                                        data-id="<?php echo (int) $est['id']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($est['nombre']); ?>"
                                        onclick="confirmarEliminar(this)">
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

<!-- Formulario oculto: SweetAlert2 lo envia por POST al confirmar -->
<form id="formEliminar" action="index.php?action=eliminar" method="POST" hidden>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
    <input type="hidden" name="id" id="eliminarId">
</form>

<script>
    // ── Mensajes flash desde el Controller ──────────────────────────────────
    // json_encode() convierte el valor PHP en un string JavaScript seguro.
    // NUNCA imprimir una variable PHP "a pelo" entre comillas dentro de un script.
    const status  = <?php echo json_encode($status); ?>;
    const mensaje = <?php echo json_encode($mensaje); ?>;

    // Evita que un nombre como <img onerror=...> se ejecute dentro de la alerta
    function escaparHTML(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }

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
    function confirmarEliminar(boton) {
        const id     = boton.dataset.id;
        const nombre = boton.dataset.nombre;

        Swal.fire({
            icon:              'warning',
            title:             'Eliminar estudiante',
            html:              `Seguro que deseas eliminar a <strong>${escaparHTML(nombre)}</strong>?
                               <br><small>Esta accion no se puede deshacer.</small>`,
            showCancelButton:  true,
            confirmButtonText: 'Si, eliminar',
            cancelButtonText:  'Cancelar',
            confirmButtonColor:'#d9534f',
            cancelButtonColor: '#6c757d',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('eliminarId').value = id;
                document.getElementById('formEliminar').submit();
            }
        });
    }
</script>

</body>
</html>