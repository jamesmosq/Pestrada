<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 02 — ¿Verdadero o falso?
 *  Tipo: predice la salida
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  Este es el archivo más importante del módulo para quien viene de Python:
 *  PHP compara y decide "verdadero o falso" de una forma distinta.
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. NO lo ejecutes todavía.
 *  2. Para cada caso escribe true o false (o lo que imprima).
 *  3. Ejecútalo, compara y lee la explicación de los que fallaste.
 */

function mostrar(string $caso, string $expresion, $resultado): void
{
    echo "<strong>$caso</strong> &nbsp; <code>" . htmlspecialchars($expresion) . "</code> &nbsp; → "
       . var_export($resultado, true) . "<br>";
}

echo "<h3>Parte A: == contra ===</h3>";
mostrar('A1', '5 == "5"',     5 == "5");
mostrar('A2', '5 === "5"',    5 === "5");
mostrar('A3', '"1" == "01"',  "1" == "01");
mostrar('A4', '"10" == "1e1"', "10" == "1e1");
mostrar('A5', '0 == "hola"',  0 == "hola");
mostrar('A6', 'null == false', null == false);
mostrar('A7', '[] == false',  [] == false);
// Tus predicciones: A1 ___ A2 ___ A3 ___ A4 ___ A5 ___ A6 ___ A7 ___

echo "<h3>Parte B: ¿qué cuenta como falso en un if?</h3>";
foreach (['0', '0.0', '', ' ', 'false', [], [0], null, 0.0] as $i => $valor) {
    mostrar('B' . ($i + 1), 'if (' . (is_array($valor) ? json_encode($valor) : var_export($valor, true)) . ')', (bool) $valor);
}
// Tus predicciones: B1 ___ B2 ___ B3 ___ B4 ___ B5 ___ B6 ___ B7 ___ B8 ___ B9 ___

echo "<h3>Parte C: switch y match</h3>";

$opcion = "1";   // llega de un formulario: siempre es texto

switch ($opcion) {
    case 1:
        $resultadoSwitch = 'entró al case 1';
        break;
    default:
        $resultadoSwitch = 'entró al default';
}
mostrar('C1', 'switch ("1") { case 1: ... }', $resultadoSwitch);

try {
    $resultadoMatch = match ($opcion) {
        1       => 'entró al caso 1',
        default => 'entró al default',
    };
} catch (Throwable $e) {
    $resultadoMatch = get_class($e);
}
mostrar('C2', 'match ("1") { 1 => ... }', $resultadoMatch);
// Tus predicciones: C1 ___ C2 ___

echo "<h3>Parte D: el operador ?? y el ternario</h3>";
$datos = ['nombre' => '', 'edad' => 0];
mostrar('D1', '$datos["nombre"] ?? "Anónimo"', $datos['nombre'] ?? 'Anónimo');
mostrar('D2', '$datos["nombre"] ?: "Anónimo"', $datos['nombre'] ?: 'Anónimo');
mostrar('D3', '$datos["ciudad"] ?? "Sin ciudad"', $datos['ciudad'] ?? 'Sin ciudad');
mostrar('D4', '$datos["edad"] ?: "no indicó"', $datos['edad'] ?: 'no indicó');
// Tus predicciones: D1 ___ D2 ___ D3 ___ D4 ___


/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESPUESTAS Y EXPLICACIÓN (lee esto DESPUÉS de ejecutar)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  PARTE A — == convierte los tipos antes de comparar; === no convierte nada
 *    A1 true   El texto "5" se convierte a número 5.
 *    A2 false  Número contra texto: tipos distintos.
 *    A3 true   Los dos textos PARECEN números, así que PHP los compara como números (1 == 1).
 *    A4 true   "1e1" es notación científica: 1 x 10 = 10. Sorprendente, pero así es.
 *    A5 false  En PHP 8 un texto que no es número no es igual a 0. (En PHP 7 daba true.)
 *    A6 true   null y false se consideran "vacíos" para ==.
 *    A7 true   Un array vacío también.
 *    Conclusión: usa === siempre que puedas. Con == hay demasiadas sorpresas.
 *
 *  PARTE B — valores que en un if cuentan como FALSO
 *    B1 '0'      false  ← el texto "0" es FALSO en PHP (en Python, "0" es verdadero)
 *    B2 '0.0'    true   ← pero "0.0" es verdadero: solo "0" exacto es falso
 *    B3 ''       false
 *    B4 ' '      true   ← un espacio ya no es texto vacío
 *    B5 'false'  true   ← el TEXTO "false" no es el valor false
 *    B6 []       false
 *    B7 [0]      true   ← el array tiene un elemento (aunque sea 0)
 *    B8 null     false
 *    B9 0.0      false
 *
 *  PARTE C — switch compara con ==, match compara con ===
 *    C1 'entró al case 1'   switch usa == : "1" == 1 es true.
 *    C2 'entró al default'  match usa ===: "1" === 1 es false.
 *    Con datos de formularios (siempre texto) esto importa: convierte primero,
 *    match ((int) $opcion) { 1 => ... }
 *
 *  PARTE D — ?? pregunta "¿existe y no es null?"; ?: pregunta "¿es verdadero?"
 *    D1 ''            La clave existe (aunque esté vacía): ?? la devuelve tal cual.
 *    D2 'Anónimo'     '' es falso, así que ?: usa el valor de la derecha.
 *    D3 'Sin ciudad'  La clave no existe: ?? usa el valor de la derecha (sin warning).
 *    D4 'no indicó'   ¡Cuidado! La edad 0 es falsa para ?:, aunque sea un dato real.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, == NUNCA convierte tipos: 5 == "5" es False y "1" == "01" es
 *  False. El === de PHP se parece mucho más al == de Python.
 *  Y en Python solo el texto VACÍO es falso; en PHP también lo es "0".
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Un formulario envía cantidad = "0". ¿Qué hace if ($_POST['cantidad']) ?
 *     ¿Cómo escribirías bien esa condición?
 *  2. ¿Cuándo usarías ?? y cuándo ?: ? Da un ejemplo de cada uno.
 *  3. ¿Por qué A3 y A4 pueden ser un problema de seguridad al comparar
 *     contraseñas o códigos con ==? (investiga: "PHP magic hashes")
 */
