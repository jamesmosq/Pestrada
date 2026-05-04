<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Estudiante — SENA</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>

<div class="container--narrow">
    <div class="card">

        <div class="page-header">
            <h2>Nuevo estudiante</h2>
        </div>

        <form id="formCrear" action="index.php?action=guardar" method="POST">

            <div class="form-group">
                <label>
                    Nombre completo <span class="required">*</span>
                </label>
                <input type="text"
                       name="nombre"
                       class="form-control"
                       placeholder="Ej: Carlos Perez"
                       required>
            </div>

            <div class="form-group">
                <label>
                    Email institucional <span class="required">*</span>
                </label>
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="Ej: carlos@sena.edu.co"
                       required>
            </div>

            <div class="form-group">
                <label>Numero de ficha</label>
                <input type="text"
                       name="ficha"
                       class="form-control"
                       placeholder="Ej: 2758634">
            </div>

            <div class="form-actions">
                <button type="button"
                        class="btn btn--primary"
                        onclick="confirmarGuardar()">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar
                </button>
                <a href="index.php?action=index" class="btn btn--secondary">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    // Error devuelto por el Controller (validacion del servidor)
    const status  = "<?php echo $status; ?>";
    const mensaje = <?php echo json_encode($mensaje); ?>;

    if (status === 'error') {
        Swal.fire({
            icon:  'error',
            title: 'No se pudo guardar',
            text:  mensaje,
        });
    }

    // Validacion del cliente antes de enviar
    function confirmarGuardar() {
        const nombre = document.querySelector('[name=nombre]').value.trim();
        const email  = document.querySelector('[name=email]').value.trim();

        if (!nombre || !email) {
            Swal.fire({
                icon:  'warning',
                title: 'Campos requeridos',
                text:  'Completa nombre y email antes de continuar.',
            });
            return;
        }

        Swal.fire({
            icon:              'question',
            title:             'Confirmar registro',
            html:              `Se registrara a: <strong>${nombre}</strong>`,
            showCancelButton:  true,
            confirmButtonText: 'Si, guardar',
            cancelButtonText:  'Revisar datos',
            confirmButtonColor:'#39a900',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formCrear').submit();
            }
        });
    }
</script>

</body>
</html>
