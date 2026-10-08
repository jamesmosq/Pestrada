<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 02 — Lo que la sesión guarda (y lo que no)
 *  Tipo: predice la salida
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. NO lo ejecutes todavía.
 *  2. Escribe tu predicción de cada caso.
 *  3. Ejecútalo y compara. Lee la explicación de los que fallaste.
 *
 *  Nota: los resultados se guardan en $salida y se imprimen todos al FINAL.
 *  ¿Por qué? Porque session_start() debe ejecutarse antes de imprimir nada
 *  (ver la "Trampa de PHP" del resuelto 01).
 */

$salida = [];
$ver = fn($valor) => var_export($valor, true);

// ── CASO 1 ────────────────────────────────────────────────────────────────────
// Todavía NO se ha llamado a session_start()
$salida[] = "Caso 1: isset(\$_SESSION) = " . $ver(isset($_SESSION))
          . " | estado = " . (session_status() === PHP_SESSION_ACTIVE ? 'activa' : 'no activa');
// Tu predicción: ____________

session_start();
$_SESSION = [];   // empezamos limpio en cada ejecución (no es parte de los casos)

// ── CASO 2 ────────────────────────────────────────────────────────────────────
$salida[] = "Caso 2: isset(\$_SESSION) = " . $ver(isset($_SESSION))
          . " | cantidad de datos = " . count($_SESSION);
// Tu predicción: ____________

// ── CASO 3 ────────────────────────────────────────────────────────────────────
$_SESSION['carrito'][] = 'Teclado';
$_SESSION['carrito'][] = 'Mouse';
$salida[] = "Caso 3: carrito = " . $ver($_SESSION['carrito']);
// Tu predicción (¿da error porque 'carrito' no existía?): ____________

// ── CASO 4 ────────────────────────────────────────────────────────────────────
// Mensaje "flash": se lee una vez y se borra
$_SESSION['flash'] = 'Guardado con éxito';

$primera = $_SESSION['flash'] ?? 'nada';
unset($_SESSION['flash']);
$segunda = $_SESSION['flash'] ?? 'nada';

$salida[] = "Caso 4: primera lectura = $primera | segunda lectura = $segunda";
// Tu predicción: ____________

// ── CASO 5 ────────────────────────────────────────────────────────────────────
$_SESSION['usuario'] = 'ana';
$idAntes = session_id();
session_regenerate_id(true);
$idDespues = session_id();

$salida[] = "Caso 5: ¿cambió el id? " . $ver($idAntes !== $idDespues)
          . " | usuario = " . $ver($_SESSION['usuario'] ?? null);
// Tu predicción: ____________

// ── CASO 6 ────────────────────────────────────────────────────────────────────
session_destroy();
$salida[] = "Caso 6: después de session_destroy(), usuario = " . $ver($_SESSION['usuario'] ?? null);
// Tu predicción: ____________

// ── CASO 7 ────────────────────────────────────────────────────────────────────
$_SESSION = [];
$salida[] = "Caso 7: después de \$_SESSION = [], usuario = " . $ver($_SESSION['usuario'] ?? null);
// Tu predicción: ____________


foreach ($salida as $linea) {
    echo htmlspecialchars($linea) . "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESPUESTAS Y EXPLICACIÓN (lee esto DESPUÉS de ejecutar)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CASO 1 -> isset($_SESSION) = false | estado = no activa
 *    $_SESSION NO existe hasta que se llama a session_start(). Si escribes en
 *    $_SESSION sin haberla iniciado, PHP crea un array normal que se pierde
 *    al terminar la petición: los datos "no se guardan" y no hay ningún aviso.
 *
 *  CASO 2 -> isset($_SESSION) = true | cantidad de datos = 0
 *    Después de session_start() ya existe (vacía en este caso).
 *
 *  CASO 3 -> carrito = array (0 => 'Teclado', 1 => 'Mouse')
 *    No da error: al escribir $_SESSION['carrito'][] PHP crea el array
 *    automáticamente. En una sesión se puede guardar cualquier array.
 *
 *  CASO 4 -> primera lectura = Guardado con éxito | segunda lectura = nada
 *    Así funcionan los mensajes "flash": se muestran una vez y desaparecen.
 *    Es lo que hacen 21-sweetAlert2 (soluciones) y Laravel con ->with().
 *
 *  CASO 5 -> ¿cambió el id? true | usuario = 'ana'
 *    session_regenerate_id() cambia el IDENTIFICADOR de la sesión (el valor
 *    de la cookie) pero conserva los DATOS. Se usa justo al iniciar sesión:
 *    si alguien conocía el id anterior, ya no le sirve.
 *
 *  CASO 6 -> después de session_destroy(), usuario = 'ana'
 *    ¡Sorpresa! session_destroy() borra la sesión GUARDADA en el servidor, pero
 *    el array $_SESSION de ESTA petición sigue teniendo los datos hasta que
 *    termine el script. Por eso un logout correcto hace las dos cosas.
 *
 *  CASO 7 -> después de $_SESSION = [], usuario = NULL
 *    Vaciar el array sí borra los datos en esta petición. Compáralo con
 *    logoutUser() en 12-sesion/functions.php: hace $_SESSION = [], borra la
 *    cookie y llama a session_destroy().
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Flask, session["usuario"] = "ana" funciona sin preparar nada: el
 *  framework abre la sesión por ti. En PHP puro tienes que llamar a
 *  session_start() en CADA archivo que use la sesión (login.php, index.php,
 *  logout.php...). Si lo olvidas en uno, en ese archivo $_SESSION está vacía
 *  aunque el usuario haya iniciado sesión.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Recarga la página varias veces. ¿Cambian los resultados? ¿Por qué?
 *     (pista: mira la línea que dice "empezamos limpio")
 *  2. Abre F12 > Aplicación (Application) > Cookies. ¿Cómo se llama la cookie
 *     de la sesión? ¿Qué valor tiene? ¿Ves ahí el nombre "ana"?
 *  3. Si la sesión guarda los datos en el servidor y el navegador solo tiene
 *     un identificador, ¿qué pasa si alguien roba ese identificador?
 */
