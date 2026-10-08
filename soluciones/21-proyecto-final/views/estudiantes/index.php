<?php
// Las vistas solo se cargan a traves del router (index.php).
// Si alguien abre este archivo directo en el navegador, lo enviamos a la aplicacion.
if (!defined('DESDE_ROUTER')) {
    header('Location: ../../index.php');
    exit;
}

// Construye una URL del listado conservando busqueda, orden y pagina actuales
$url = fn(array $cambios = []) => 'index.php?' . http_build_query(array_merge(
    ['c' => 'estudiantes', 'action' => 'index', 'q' => $buscar, 'orden' => $orden, 'dir' => $dir, 'pagina' => $pagina],
    $cambios
));

// Encabezado que ordena al hacer clic (y alterna asc/desc)
$th = function (string $columna, string $titulo) use ($url, $orden, $dir) {
    $nuevaDir = ($orden === $columna && $dir === 'asc') ? 'desc' : 'asc';
    $flecha   = $orden === $columna ? ($dir === 'asc' ? ' ▲' : ' ▼') : '';
    return '<a class="th-link" href="' . htmlspecialchars($url(['orden' => $columna, 'dir' => $nuevaDir, 'pagina' => 1])) . '">'
         . $titulo . $flecha . '</a>';
};
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
            <div>
                <h2>Gestion de Estudiantes</h2>
                <small><?php echo $total; ?> estudiante<?php echo $total === 1 ? '' : 's'; ?>
                    <?php echo $buscar !== '' ? 'encontrados' : 'registrados'; ?></small>
            </div>
            <div class="table-actions">
                <a href="index.php?c=cursos" class="btn btn--secondary">
                    <i class="fa-solid fa-book"></i> Cursos
                </a>
                <a href="index.php?c=estudiantes&action=crear" class="btn btn--primary">
                    <i class="fa-solid fa-user-plus"></i> Nuevo estudiante
                </a>
            </div>
        </div>

        <!-- Buscador: GET, para que la busqueda quede en la URL -->
        <form class="search" method="GET" action="index.php">
            <input type="hidden" name="c" value="estudiantes">
            <input type="text" name="q" class="form-control"
                   placeholder="Buscar por nombre o email..."
                   value="<?php echo htmlspecialchars($buscar); ?>">
            <button type="submit" class="btn btn--primary"><i class="fa-solid fa-magnifying-glass"></i></button>
            <?php if ($buscar !== ''): ?>
                <a href="index.php" class="btn btn--secondary">Limpiar</a>
            <?php endif; ?>
        </form>

        <table class="table">
            <thead>
            <tr>
                <th><?php echo $th('id', '#'); ?></th>
                <th><?php echo $th('nombre', 'Nombre'); ?></th>
                <th><?php echo $th('email', 'Email'); ?></th>
                <th><?php echo $th('ficha', 'Ficha'); ?></th>
                <th><?php echo $th('curso', 'Curso'); ?></th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($estudiantes)): ?>
                <tr>
                    <td colspan="6" class="empty-row">
                        <i class="fa-solid fa-circle-info"></i>
                        <?php echo $buscar !== ''
                            ? 'Ningun estudiante coincide con "' . htmlspecialchars($buscar) . '".'
                            : 'No hay estudiantes registrados.'; ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($estudiantes as $est): ?>
                    <tr>
                        <td><?php echo $est['id']; ?></td>
                        <td>
                            <a href="index.php?c=estudiantes&action=ver&id=<?php echo (int) $est['id']; ?>">
                                <?php echo htmlspecialchars($est['nombre']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($est['email']); ?></td>
                        <td><span class="badge"><?php echo htmlspecialchars($est['ficha'] ?? ''); ?></span></td>
                        <td><?php echo htmlspecialchars($est['curso'] ?? '—'); ?></td>
                        <td>
                            <div class="table-actions">
                                <a href="index.php?c=estudiantes&action=editar&id=<?php echo (int) $est['id']; ?>"
                                   class="btn btn--warning btn--sm">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
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

        <?php if ($paginas > 1): ?>
            <nav class="pagination">
                <?php if ($pagina > 1): ?>
                    <a href="<?php echo htmlspecialchars($url(['pagina' => $pagina - 1])); ?>">&laquo; Anterior</a>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $paginas; $p++): ?>
                    <a href="<?php echo htmlspecialchars($url(['pagina' => $p])); ?>"
                       class="<?php echo $p === $pagina ? 'active' : ''; ?>"><?php echo $p; ?></a>
                <?php endfor; ?>

                <?php if ($pagina < $paginas): ?>
                    <a href="<?php echo htmlspecialchars($url(['pagina' => $pagina + 1])); ?>">Siguiente &raquo;</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

    </div>
</div>

<!-- Formulario oculto: SweetAlert2 lo envia por POST al confirmar -->
<form id="formEliminar" action="index.php?c=estudiantes&action=eliminar" method="POST" hidden>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
    <input type="hidden" name="id" id="eliminarId">
</form>

<?php require __DIR__ . '/../partials/flash.php'; ?>

<script>
    function escaparHTML(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }

    // Para eliminar hay que escribir el nombre exacto del estudiante
    function confirmarEliminar(boton) {
        const id     = boton.dataset.id;
        const nombre = boton.dataset.nombre;

        Swal.fire({
            icon:              'warning',
            title:             'Eliminar estudiante',
            html:              `Esta accion no se puede deshacer.<br>
                                Escribe <strong>${escaparHTML(nombre)}</strong> para confirmar:`,
            input:             'text',
            inputPlaceholder:  nombre,
            showCancelButton:  true,
            confirmButtonText: 'Eliminar',
            cancelButtonText:  'Cancelar',
            confirmButtonColor:'#d9534f',
            inputValidator: (valor) => {
                if (valor.trim() !== nombre) {
                    return 'El nombre no coincide';
                }
            },
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
