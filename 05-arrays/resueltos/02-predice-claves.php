<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 02 — Las claves que cambian solas
 *  Tipo: predice la salida
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En PHP, un mismo tipo (array) hace de lista y de diccionario. Eso trae
 *  comportamientos que en Python no existen. Este archivo los muestra.
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. NO lo ejecutes todavía.
 *  2. Escribe tu predicción de cada caso.
 *  3. Ejecútalo, compara y lee la explicación de los que fallaste.
 */

function mostrar(string $caso, $valor): void
{
    echo "<strong>$caso:</strong> " . htmlspecialchars(json_encode($valor, JSON_UNESCAPED_UNICODE)) . "<br>";
}

// ── CASO 1 ────────────────────────────────────────────────────────────────────
$a = [];
$a["1"] = 'texto uno';
$a[1]   = 'número uno';
mostrar('Caso 1 — cuántos elementos y cuáles', $a);
// Tu predicción: ____________

// ── CASO 2 ────────────────────────────────────────────────────────────────────
$fichas = ['2758634' => 'ADSO', '0123' => 'Redes'];
mostrar('Caso 2 — tipos de las claves', array_map('gettype', array_keys($fichas)));
// Tu predicción (¿string o integer?): ____________

// ── CASO 3 ────────────────────────────────────────────────────────────────────
$colores = ['rojo', 'verde', 'azul'];
unset($colores[1]);
$colores[] = 'negro';
mostrar('Caso 3 — claves después de unset y []', array_keys($colores));
// Tu predicción: ____________

// ── CASO 4 ────────────────────────────────────────────────────────────────────
$lista = [5 => 'a'];
$lista[] = 'b';
$lista[] = 'c';
mostrar('Caso 4 — claves', array_keys($lista));
// Tu predicción: ____________

// ── CASO 5 ────────────────────────────────────────────────────────────────────
$x = ['a' => 1, 'b' => 2];
$y = ['b' => 99, 'c' => 3];
mostrar('Caso 5a — $x + $y', $x + $y);
mostrar('Caso 5b — array_merge($x, $y)', array_merge($x, $y));
// Tu predicción: ____________

// ── CASO 6 ────────────────────────────────────────────────────────────────────
$ids1 = [10 => 'Ana', 20 => 'Luis'];
$ids2 = [30 => 'Marta'];
mostrar('Caso 6 — array_merge con claves numéricas', array_merge($ids1, $ids2));
// Tu predicción: ____________

// ── CASO 7 ────────────────────────────────────────────────────────────────────
$notas = ['Ana' => 4.2, 'Luis' => 3.1, 'Marta' => 4.7];
$copia = $notas;
sort($copia);
mostrar('Caso 7a — sort()', $copia);
$copia = $notas;
asort($copia);
mostrar('Caso 7b — asort()', $copia);
// Tu predicción: ____________

// ── CASO 8 ────────────────────────────────────────────────────────────────────
$codigos = ['10', '20', '30'];
mostrar('Caso 8a — in_array("1e1", $codigos)', in_array('1e1', $codigos));
mostrar('Caso 8b — in_array("1e1", $codigos, true)', in_array('1e1', $codigos, true));
// Tu predicción: ____________

// ── CASO 9 ────────────────────────────────────────────────────────────────────
$letras = ['a', 'b', 'c'];
$pos = array_search('a', $letras);
mostrar('Caso 9 — array_search("a") y el if', [$pos, $pos ? 'lo encontró' : 'NO lo encontró']);
// Tu predicción: ____________


/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESPUESTAS Y EXPLICACIÓN (lee esto DESPUÉS de ejecutar)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CASO 1 -> {"1":"número uno"}  (UN solo elemento)
 *    La clave "1" (texto que parece número entero) se convierte en el número 1.
 *    Las dos líneas escriben en la MISMA clave: la segunda pisa a la primera.
 *
 *  CASO 2 -> ["integer","string"]
 *    '2758634' se convirtió en número; '0123' NO, porque un entero no empieza
 *    con 0. Por eso, si guardas cédulas o fichas como claves, a veces son número
 *    y a veces texto. Compara con == o conviértelas a (string) si hace falta.
 *
 *  CASO 3 -> [0,2,3]
 *    unset() borra el elemento pero NO reorganiza las claves (queda el hueco
 *    del 1), y [] agrega en "la clave más alta + 1". Para reordenar:
 *    array_values().
 *
 *  CASO 4 -> [5,6,7]
 *    [] siempre continúa desde la clave numérica más alta.
 *
 *  CASO 5 -> 5a: {"a":1,"b":2,"c":3}   5b: {"a":1,"b":99,"c":3}
 *    +  (unión): si la clave ya existe, se queda la de la IZQUIERDA.
 *    array_merge: si la clave de texto se repite, gana la de la DERECHA.
 *
 *  CASO 6 -> ["Ana","Luis","Marta"]  (las claves 10, 20, 30 se perdieron)
 *    array_merge RENUMERA las claves numéricas desde 0. Si esas claves eran ids,
 *    los perdiste. Para conservarlas: $ids1 + $ids2.
 *
 *  CASO 7 -> 7a: [3.1,4.2,4.7]   7b: {"Luis":3.1,"Ana":4.2,"Marta":4.7}
 *    sort() ordena los valores y BORRA las claves (quedan 0, 1, 2): perdiste
 *    los nombres. asort() ordena conservando la relación clave => valor.
 *    (ksort ordena por la clave; arsort y krsort, al revés.)
 *
 *  CASO 8 -> 8a: true   8b: false
 *    in_array compara con == por defecto, y "1e1" == "10" es true (ver
 *    04-condicionales/resueltos/02). Con el tercer parámetro true compara con ===.
 *
 *  CASO 9 -> [0,"NO lo encontró"]
 *    Sí lo encontró, en la posición 0... y 0 es falso en el if. Compara siempre
 *    con === false:  if ($pos !== false) { ... }
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, una lista y un diccionario son tipos distintos y las claves de
 *  un diccionario nunca cambian de tipo: d["1"] y d[1] son dos claves
 *  diferentes. En PHP, "1" y 1 son la MISMA clave, y borrar de una "lista" deja
 *  huecos. Si necesitas una lista limpia (0, 1, 2...), usa array_values().
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. En el resuelto 01 de esta carpeta, las fichas son claves. ¿Son números o
 *     texto? ¿Importa para el reporte?
 *  2. Tienes los estudiantes de dos fichas en arrays indexados por cédula.
 *     ¿Usas + o array_merge para juntarlos? ¿Por qué?
 *  3. Después de borrar con unset() en una lista, json_encode() la convierte en
 *     un objeto {"0":...,"2":...} en lugar de una lista [...]. Pruébalo. ¿Por qué
 *     importa eso si la envías a JavaScript?
 */
