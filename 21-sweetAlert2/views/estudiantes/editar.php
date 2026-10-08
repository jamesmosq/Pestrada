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
    <title>Editar Estudiante — SENA</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>

<div class="container--narrow">
    <div class="card">

        <div class="page-header">
            <h2 class="header--warning">Editar estudiante</h2>
        </div>

        <form id="formEditar" action="index.php?action=actualizar" method="POST">

            <!-- Token CSRF: prueba que el formulario salio de nuestra pagina -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">

            <!-- Sin este campo el Controller no sabria que registro actualizar -->
            <input type="hidden"
                   name="id"
                   value="<?php echo $estudiante['id']; ?>">

            <div class="form-group">
                <label>
                    Nombre completo <span class="required">*</span>
                </label>
                <input type="text"
                       name="nombre"
                       class="form-control form-control--warning"
                       value="<?php echo htmlspecialchars($estudiante['nombre']); ?>"
                       required>
            </div>

            <div class="form-group">
                <label>
                    Email institucional <span class="required">*</span>
                </label>
                <input type="email"
                       name="email"
                       class="form-control form-control--warning"
                       value="<?php echo htmlspecialchars($estudiante['email']); ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Numero de ficha</label>
                <input type="text"
                       name="ficha"
                       class="form-control form-control--warning"
                       value="<?php echo htmlspecialchars($estudiante['ficha']); ?>">
            </div>

            <div class="form-actions">
                <button type="button"
                        class="btn btn--warning"
                        onclick="confirmarEdicion()">
                    <i class="fa-solid fa-floppy-disk"></i> Actualizar
                </button>
                <a href="index.php?action=index" class="btn btn--secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    const status  = <?php echo json_encode($status); ?>;
    const mensaje = <?php echo json_encode($mensaje); ?>;

    function escaparHTML(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }

    if (status === 'error') {
        Swal.fire({
            icon:  'error',
            title: 'Error al actualizar',
            text:  mensaje,
        });
    }

    function confirmarEdicion() {
        const nombre = document.querySelector('[name=nombre]').value.trim();

        if (!nombre) {
            Swal.fire({
                icon:  'warning',
                title: 'Campo requerido',
                text:  'El nombre no puede estar vacio.',
            });
            return;
        }

        Swal.fire({
            icon:              'question',
            title:             'Guardar cambios',
            html:              `Se actualizaran los datos de <strong>${escaparHTML(nombre)}</strong>`,
            showCancelButton:  true,
            confirmButtonText: 'Si, actualizar',
            cancelButtonText:  'Cancelar',
            confirmButtonColor:'#e6940a',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formEditar').submit();
            }
        });
    }
</script>

</body>
</html>