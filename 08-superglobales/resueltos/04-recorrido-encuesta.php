<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 04 — Una encuesta, tres peticiones
 *  Tipo: sigue el recorrido
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  Este archivo es una encuesta: "¿Qué lenguaje te gusta más?". Parece una sola
 *  página, pero al votar el navegador hace TRES peticiones distintas a este
 *  mismo archivo. El objetivo es que sigas el recorrido de cada una.
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. Ábrelo en el navegador y abre las herramientas de desarrollo (F12),
 *     pestaña "Red" (Network). Marca la opción "Conservar registro" (Preserve log).
 *  2. Vota. Mira en la pestaña Red las peticiones que aparecieron y fíjate en
 *     la columna Estado (200, 302) y en el Método (GET, POST).
 *  3. Debajo de la página hay un panel gris que muestra qué llegó al servidor
 *     en la ÚLTIMA petición.
 *  4. Llena la tabla de la sección RECORRIDO (al final) y compárala con la respuesta.
 *  5. Después de votar, presiona F5. ¿Te pregunta si quieres reenviar el formulario?
 */

session_start();   // la sesión guarda los votos mientras el navegador esté abierto

$opciones = ['php' => 'PHP', 'python' => 'Python', 'js' => 'JavaScript'];
$error = '';

// ── PETICIÓN 2: llega el voto por POST ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voto = $_POST['lenguaje'] ?? '';

    if (array_key_exists($voto, $opciones)) {
        $_SESSION['votos'][$voto] = ($_SESSION['votos'][$voto] ?? 0) + 1;

        // NO se muestra nada aquí: se le dice al navegador "ve a esta otra URL".
        // header() envía el código 302 y la dirección nueva.
        header('Location: ' . $_SERVER['PHP_SELF'] . '?gracias=' . $voto);
        exit;   // obligatorio: después de redirigir, no se ejecuta nada más
    }

    // Voto inválido: aquí NO se redirige, se muestra el error en esta misma respuesta
    $error = 'Elige una de las opciones.';
}

// ── PETICIONES 1 y 3: se muestra la página (GET) ──────────────────────────────
$votos   = $_SESSION['votos'] ?? [];
$gracias = $_GET['gracias'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encuesta</title>
    <style>
        body   { font-family: Arial, sans-serif; max-width: 520px; margin: 30px auto; }
        .ok    { color: #27ae60; }
        .error { color: #c0392b; }
        .panel { background: #f0f2f5; padding: 12px; margin-top: 30px; font-family: monospace; font-size: 13px; }
    </style>
</head>
<body>
    <h2>¿Qué lenguaje te gusta más?</h2>

    <?php if (isset($opciones[$gracias])): ?>
        <p class="ok">Gracias por votar por <?= $opciones[$gracias] ?>.</p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <?php foreach ($opciones as $clave => $nombre): ?>
            <label><input type="radio" name="lenguaje" value="<?= $clave ?>"> <?= $nombre ?></label><br>
        <?php endforeach; ?>
        <br><button type="submit">Votar</button>
    </form>

    <h3>Resultados</h3>
    <?php foreach ($opciones as $clave => $nombre): ?>
        <?= $nombre ?>: <?= $votos[$clave] ?? 0 ?> voto(s)<br>
    <?php endforeach; ?>

    <!-- Panel de diagnóstico: qué recibió el servidor en ESTA petición -->
    <div class="panel">
        <strong>Lo que recibió el servidor en esta petición</strong><br>
        Método: <?= $_SERVER['REQUEST_METHOD'] ?><br>
        URL: <?= htmlspecialchars($_SERVER['REQUEST_URI']) ?><br>
        $_GET: <?= htmlspecialchars(json_encode($_GET)) ?><br>
        $_POST: <?= htmlspecialchars(json_encode($_POST)) ?><br>
        $_SESSION['votos']: <?= htmlspecialchars(json_encode($votos)) ?>
    </div>
</body>
</html>
<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RECORRIDO — llénalo tú y luego compara
 * ════════════════════════════════════════════════════════════════════════════
 *
 *   petición | ¿quién la hace?            | método | URL                    | ¿qué hace el servidor?           | respuesta
 *   ---------+----------------------------+--------+------------------------+----------------------------------+----------
 *      1     | ?                          |   ?    | ?                      | ?                                |    ?
 *      2     | ?                          |   ?    | ?                      | ?                                |    ?
 *      3     | ?                          |   ?    | ?                      | ?                                |    ?
 *
 *
 *
 *
 *
 *
 *  (sigue bajando cuando la hayas llenado)
 *
 *
 *
 *
 *
 *
 *  RESPUESTA
 *
 *   petición | ¿quién la hace?            | método | URL                         | ¿qué hace el servidor?              | respuesta
 *   ---------+----------------------------+--------+-----------------------------+-------------------------------------+----------
 *      1     | el usuario escribe la URL  |  GET   | 04-recorrido-encuesta.php   | muestra formulario y resultados     | 200 + HTML
 *      2     | el usuario presiona Votar  |  POST  | 04-recorrido-encuesta.php   | valida, suma el voto en la sesión   | 302 (sin HTML)
 *            |                            |        | (el voto va en el cuerpo)   | y responde "ve a ...?gracias=php"   |
 *      3     | el NAVEGADOR, solo,        |  GET   | ...encuesta.php?gracias=php | muestra "Gracias" y los resultados  | 200 + HTML
 *            | al recibir el 302          |        |                             |                                     |
 *
 *  Por eso en el panel nunca ves $_POST con datos después de votar: la página
 *  que estás viendo es la de la petición 3 (GET). El POST ya pasó.
 *
 *  Y por eso F5 no pregunta nada: F5 repite la ÚLTIMA petición, que fue el GET.
 *  A esto se le llama patrón PRG: Post / Redirect / Get.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En un programa de Python las variables viven mientras el programa corre.
 *  En PHP web, el script se ejecuta DESDE CERO en cada petición y al terminar
 *  se olvida todo: $votos, $error, todo. Lo único que sobrevive entre una
 *  petición y otra es lo que guardes fuera del script: la sesión, una base de
 *  datos o un archivo. Por eso los votos van en $_SESSION.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Quita las líneas header(...) y exit; vota y presiona F5 varias veces.
 *     ¿Qué pasa con los resultados? ¿Por qué?
 *  2. Envía el formulario sin elegir ninguna opción. ¿Cuántas peticiones hubo
 *     esta vez? ¿Por qué en este caso NO se redirige?
 *  3. Abre la encuesta en una ventana de incógnito y vota. ¿Ves los votos de la
 *     otra ventana? ¿Por qué? (pista: ¿de quién es cada sesión?)
 *  4. Escribe a mano en la URL ?gracias=<b>hola</b>. ¿Se ve en negrita? ¿Por qué no?
 */
