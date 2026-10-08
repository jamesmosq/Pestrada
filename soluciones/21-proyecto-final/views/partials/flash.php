<?php
// Las vistas solo se cargan a traves del router (index.php).
// Si alguien abre este archivo directo en el navegador, lo enviamos a la aplicacion.
if (!defined('DESDE_ROUTER')) {
    header('Location: ../../index.php');
    exit;
}
?>
<?php if ($flash): ?>
<script>
    // Mensaje guardado en la sesion por el Controller (se muestra una sola vez)
    (function () {
        const flash = <?php echo json_encode($flash); ?>;

        const alertas = {
            creado:    { icon: 'success', title: 'Registro creado'    },
            editado:   { icon: 'success', title: 'Datos actualizados' },
            eliminado: { icon: 'info',    title: 'Registro eliminado' },
            error:     { icon: 'error',   title: 'Error'              },
        };
        const alerta = alertas[flash.tipo] ?? alertas.error;

        if (flash.tipo === 'error') {
            // Los errores son modales: el usuario debe leerlos y cerrarlos
            Swal.fire({ icon: alerta.icon, title: alerta.title, text: flash.mensaje });
        } else {
            // Los exitos son toasts: aparecen en la esquina y se van solos
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: alerta.icon,
                title: flash.mensaje,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }
    })();
</script>
<?php endif; ?>
