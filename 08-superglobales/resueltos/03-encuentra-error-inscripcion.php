<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 03 — La inscripción que acepta lo que no debe
 *  Tipo: encuentra el error
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  SITUACIÓN
 *  Un formulario de inscripción a un paseo envía por POST estos campos:
 *      correo   (texto)        adultos (número)       ninos (número, puede ser 0)
 *      acepto   (casilla "Acepto el reglamento"; si no se marca, NO se envía)
 *
 *  La función procesarInscripcion() recibe el array del formulario (lo mismo
 *  que $_POST) y debe:
 *    - exigir un correo válido
 *    - aceptar 0 niños (es un valor válido, no un campo vacío)
 *    - rechazar la inscripción si NO se marcó la casilla
 *    - calcular el total de personas = adultos + niños
 *
 *  Recibir el array como parámetro (en vez de leer $_POST adentro) permite
 *  probar la función con datos inventados: eso hacen las pruebas de abajo.
 *
 *  Hay CUATRO errores. Ejecuta, mira las pruebas que fallan, corrige aquí
 *  mismo y compara al final con la versión corregida.
 *
 *  Pistas (solo si llevas 15 minutos sin avanzar):
 *    - Pista 1: ¿el nombre de la clave coincide EXACTAMENTE con el del formulario?
 *    - Pista 2: ¿qué responde empty() para el texto '0'?
 *    - Pista 3: si la casilla no llega, ¿qué valor toma $acepto?
 *    - Pista 4: ¿con qué operador se SUMA en PHP y con cuál se UNE texto?
 */

function procesarInscripcion(array $post): array
{
    $errores = [];

    $correo  = trim($post['Correo'] ?? '');
    $adultos = $post['adultos'] ?? '';
    $ninos   = $post['ninos'] ?? '';
    $acepto  = $post['acepto'] ?? true;

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Correo no válido.';
    }
    if (empty($adultos) || !ctype_digit($adultos)) {
        $errores[] = 'Indica cuántos adultos van.';
    }
    if (empty($ninos) || !ctype_digit($ninos)) {
        $errores[] = 'Indica cuántos niños van (puede ser 0).';
    }
    if (!$acepto) {
        $errores[] = 'Debes aceptar el reglamento.';
    }

    if ($errores) {
        return ['ok' => false, 'errores' => $errores];
    }

    return ['ok' => true, 'personas' => $adultos . $ninos];
}

// ── Pruebas automáticas: NO las modifiques, corrige la función ────────────────
$pruebas = [
    'inscripción correcta (2 adultos, 1 niño)' => [
        ['correo' => 'ana@x.co', 'adultos' => '2', 'ninos' => '1', 'acepto' => 'si'],
        ['ok' => true, 'personas' => 3],
    ],
    'inscripción correcta sin niños (0)' => [
        ['correo' => 'luis@x.co', 'adultos' => '3', 'ninos' => '0', 'acepto' => 'si'],
        ['ok' => true, 'personas' => 3],
    ],
    'sin marcar la casilla (no se envía)' => [
        ['correo' => 'ana@x.co', 'adultos' => '2', 'ninos' => '1'],
        ['ok' => false, 'errores' => ['Debes aceptar el reglamento.']],
    ],
    'correo inválido' => [
        ['correo' => 'ana@', 'adultos' => '2', 'ninos' => '1', 'acepto' => 'si'],
        ['ok' => false, 'errores' => ['Correo no válido.']],
    ],
];

foreach ($pruebas as $descripcion => [$datos, $esperado]) {
    $obtenido = procesarInscripcion($datos);

    $ok = $obtenido === $esperado;   // mismo resultado, mismos errores, mismos tipos

    echo ($ok ? "OK    " : "FALLA ") . " $descripcion<br>";
    if (!$ok) {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;esperado: " . htmlspecialchars(json_encode($esperado, JSON_UNESCAPED_UNICODE))
           . "<br>&nbsp;&nbsp;&nbsp;&nbsp;obtenido: " . htmlspecialchars(json_encode($obtenido, JSON_UNESCAPED_UNICODE)) . "<br>";
    }
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  - En Python el + suma números y también une textos ("2" + "1" = "21").
 *    En PHP son operadores DISTINTOS: + siempre suma y . siempre une.
 *    "2" . "1" da "21"; "2" + "1" da 3. Y el resultado de . es texto.
 *  - En Python, if not "0" es False (el texto "0" no está vacío). En PHP,
 *    empty("0") es true. Es uno de los comportamientos más sorprendentes de PHP.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Cuál de los cuatro errores sería más grave en producción? ¿Por qué?
 *  2. ¿Qué ventaja tiene que la función reciba $post como parámetro en lugar
 *     de leer $_POST directamente? (Pista: ¿cómo la probarías si no?)
 *  3. Escribe la línea que usaría la página real:  $resultado = ...
 *
 *
 *
 *
 *
 *
 *
 *
 *  (sigue bajando solo cuando termines)
 *
 *
 *
 *
 *
 *
 *
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  VERSIÓN CORREGIDA
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  function procesarInscripcion(array $post): array
 *  {
 *      $errores = [];
 *
 *      $correo  = trim($post['correo'] ?? '');       // ERROR 1: la clave es 'correo', en minúscula
 *      $adultos = $post['adultos'] ?? '';
 *      $ninos   = $post['ninos'] ?? '';
 *      $acepto  = isset($post['acepto']);            // ERROR 3: si no llega, NO aceptó (era ?? true)
 *
 *      if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
 *          $errores[] = 'Correo no válido.';
 *      }
 *      if (empty($adultos) || !ctype_digit($adultos)) {    // aquí empty() sí sirve: 0 adultos no es válido
 *          $errores[] = 'Indica cuántos adultos van.';
 *      }
 *      if ($ninos === '' || !ctype_digit($ninos)) {        // ERROR 2: empty('0') es true; 0 niños es válido
 *          $errores[] = 'Indica cuántos niños van (puede ser 0).';
 *      }
 *      if (!$acepto) {
 *          $errores[] = 'Debes aceptar el reglamento.';
 *      }
 *
 *      if ($errores) {
 *          return ['ok' => false, 'errores' => $errores];
 *      }
 *
 *      return ['ok' => true, 'personas' => (int) $adultos + (int) $ninos];
 *                                                    // ERROR 4: el punto une texto ("21"); se suma con +
 *  }
 *
 *  Respuesta a la pregunta 3:  $resultado = procesarInscripcion($_POST);
 */
