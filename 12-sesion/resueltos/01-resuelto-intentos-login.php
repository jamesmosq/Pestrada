<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 01 — Login con límite de intentos
 *  Tipo: resuelto y comentado
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  Un login que, después de 3 intentos fallidos, se bloquea durante 60 segundos.
 *  Si el usuario entra bien, ve una página de bienvenida con botón para salir.
 *  (Para no depender de la base de datos, los usuarios están en un array.
 *   Usuarios de prueba: admin / admin123   y   ana / clave123)
 *
 *  CÓMO SE PENSÓ (antes de escribir código)
 *  1. ¿Qué hay que RECORDAR entre una petición y otra?
 *       - si el usuario ya entró y quién es       -> $_SESSION['usuario']
 *       - cuántos intentos fallidos lleva          -> $_SESSION['intentos']
 *       - hasta cuándo está bloqueado              -> $_SESSION['bloqueado_hasta']
 *     Todo eso va en la sesión, porque las variables normales se borran al
 *     terminar cada petición.
 *  2. El archivo atiende 3 situaciones, en este orden:
 *       a) quiere salir (POST con accion=salir)
 *       b) intenta entrar (POST con usuario y clave)
 *       c) solo está mirando la página (GET)
 *  3. Seguridad:
 *       - las contraseñas se guardan como hash y se comparan con password_verify()
 *       - el mensaje de error NO dice si falló el usuario o la clave
 *         (si dijera "el usuario no existe", un atacante sabría qué usuarios hay)
 *       - al entrar se cambia el id de sesión: session_regenerate_id(true)
 */

session_start();   // SIEMPRE antes de cualquier echo o HTML

const MAX_INTENTOS = 3;
const SEGUNDOS_BLOQUEO = 60;

// En un sistema real esto viene de la base de datos (tabla usuarios)
$usuarios = [
    'admin' => ['nombre' => 'Administrador', 'hash' => '$2y$10$TNSJ0cdliQafrmAkDpV4/u02oya.0d7KXC6EJCsgNqLytzxxjEPke'],
    'ana'   => ['nombre' => 'Ana Gómez',     'hash' => '$2y$10$e1fySYgETXk.k/PT6bpSs.IPtEy/qqrg4z4PgYHW8vsO.hC2NGtqW'],
];

$mensaje = '';

// ── a) Salir ──────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'salir') {
    $_SESSION = [];          // vaciar los datos
    session_destroy();       // borrar la sesión en el servidor
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// ── b) Intentar entrar ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'entrar') {

    $bloqueadoHasta = $_SESSION['bloqueado_hasta'] ?? 0;

    if (time() < $bloqueadoHasta) {
        // Bloqueado: ni siquiera se revisa la contraseña
        $mensaje = 'Demasiados intentos. Espera ' . ($bloqueadoHasta - time()) . ' segundos.';
    } else {
        $usuario = trim($_POST['usuario'] ?? '');
        $clave   = $_POST['clave'] ?? '';
        $datos   = $usuarios[$usuario] ?? null;

        if ($datos && password_verify($clave, $datos['hash'])) {
            session_regenerate_id(true);           // id nuevo al cambiar de "anónimo" a "autenticado"
            $_SESSION['usuario'] = $datos['nombre'];
            unset($_SESSION['intentos'], $_SESSION['bloqueado_hasta']);

            header('Location: ' . $_SERVER['PHP_SELF']);   // PRG: F5 no reenvía la clave
            exit;
        }

        // Falló: contar el intento
        $_SESSION['intentos'] = ($_SESSION['intentos'] ?? 0) + 1;
        $restantes = MAX_INTENTOS - $_SESSION['intentos'];

        if ($restantes <= 0) {
            $_SESSION['bloqueado_hasta'] = time() + SEGUNDOS_BLOQUEO;
            $_SESSION['intentos'] = 0;             // se reinicia el conteo para después del bloqueo
            $mensaje = 'Demasiados intentos. Espera ' . SEGUNDOS_BLOQUEO . ' segundos.';
        } else {
            $mensaje = "Usuario o clave incorrectos. Te quedan $restantes intento(s).";
        }
    }
}

// ── c) Mostrar la página ──────────────────────────────────────────────────────
$logueado = isset($_SESSION['usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login con límite de intentos</title>
    <style>
        body  { font-family: Arial, sans-serif; max-width: 380px; margin: 40px auto; }
        input { width: 100%; padding: 6px; margin-bottom: 10px; box-sizing: border-box; }
        .error { color: #c0392b; }
    </style>
</head>
<body>
<?php if ($logueado): ?>

    <h2>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></h2>
    <form method="POST">
        <input type="hidden" name="accion" value="salir">
        <button type="submit">Salir</button>
    </form>

<?php else: ?>

    <h2>Iniciar sesión</h2>
    <?php if ($mensaje): ?>
        <p class="error"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="accion" value="entrar">
        <input type="text" name="usuario" placeholder="Usuario">
        <input type="password" name="clave" placeholder="Clave">
        <button type="submit">Entrar</button>
    </form>

<?php endif; ?>
</body>
</html>
<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAZA — lo que guarda la sesión en cada paso
 * ════════════════════════════════════════════════════════════════════════════
 *
 *   paso                         | intentos | bloqueado_hasta | usuario       | mensaje
 *   -----------------------------+----------+-----------------+---------------+--------------------------------
 *   entra por primera vez (GET)  |    -     |       -         |      -        | (ninguno)
 *   admin / mala                 |    1     |       -         |      -        | Te quedan 2 intento(s)
 *   admin / otra                 |    2     |       -         |      -        | Te quedan 1 intento(s)
 *   admin / nada                 |    0     |  ahora + 60     |      -        | Demasiados intentos...
 *   admin / admin123 (en <60 s)  |    0     |  ahora + 60     |      -        | Espera N segundos (¡aunque la clave es correcta!)
 *   admin / admin123 (tras 60 s) |    -     |       -         | Administrador | (redirige a la bienvenida)
 *   Salir                        |   (la sesión se destruye: todo vacío)
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  session_start() tiene que ir ANTES de imprimir cualquier cosa, incluso un
 *  espacio o una línea en blanco antes de "<?php". La sesión viaja en una
 *  cookie, y las cookies son encabezados: se envían antes que el HTML. Si ya
 *  salió HTML, PHP dice "headers already sent" y la sesión no funciona.
 *  En Python (Flask, Django) el framework arma la respuesta completa antes de
 *  enviarla y por eso nunca ves este error; en PHP puro el orden importa.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Abre una ventana de incógnito durante el bloqueo. ¿Sigues bloqueado?
 *     ¿Qué te dice eso sobre dónde vive el contador? ¿Es una buena protección
 *     contra un atacante? ¿Dónde lo guardarías para que sí lo fuera?
 *  2. ¿Por qué el mensaje dice "Usuario o clave incorrectos" y no
 *     "El usuario no existe"?
 *  3. ¿Qué pasaría si en lugar de password_verify() se comparara
 *     $clave === $datos['hash']?
 *  4. ¿Para qué sirve session_regenerate_id(true) justo después de entrar?
 *
 *  PARA MODIFICAR
 *  - Muestra en la bienvenida a qué hora inició sesión.
 *  - Haz que la sesión se cierre sola después de 5 minutos sin actividad.
 */
