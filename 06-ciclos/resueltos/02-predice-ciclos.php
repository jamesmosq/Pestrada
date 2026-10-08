<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 02 — ¿Cuántas vueltas da?
 *  Tipo: predice la salida
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. NO lo ejecutes todavía.
 *  2. Escribe tu predicción de cada caso.
 *  3. Ejecútalo, compara y lee la explicación de los que fallaste.
 */

function mostrar(string $caso, $valor): void
{
    echo "<strong>$caso:</strong> " . htmlspecialchars(is_string($valor) ? $valor : json_encode($valor)) . "<br>";
}

// ── CASO 1 ────────────────────────────────────────────────────────────────────
mostrar('Caso 1a — range(1, 5)', range(1, 5));
mostrar('Caso 1b — range(0, 10, 3)', range(0, 10, 3));
// Tu predicción: ____________

// ── CASO 2 ────────────────────────────────────────────────────────────────────
$vueltas = 0;
for ($i = 0; $i <= 3; $i++) {
    $vueltas++;
}
mostrar('Caso 2 — vueltas y valor final de $i', "vueltas = $vueltas, \$i = $i");
// Tu predicción: ____________

// ── CASO 3 ────────────────────────────────────────────────────────────────────
$n = 10;
while ($n < 5) {
    $n++;
}
$m = 10;
do {
    $m++;
} while ($m < 5);
mostrar('Caso 3 — while y do-while', "n = $n, m = $m");
// Tu predicción: ____________

// ── CASO 4 ────────────────────────────────────────────────────────────────────
$resultado = [];
for ($i = 1; $i <= 10; $i++) {
    if ($i % 2 === 0) {
        continue;
    }
    if ($i > 7) {
        break;
    }
    $resultado[] = $i;
}
mostrar('Caso 4 — continue y break', $resultado);
// Tu predicción: ____________

// ── CASO 5 ────────────────────────────────────────────────────────────────────
$encontrado = '';
foreach (['A', 'B', 'C'] as $fila) {
    foreach ([1, 2, 3] as $columna) {
        if ($fila === 'B' && $columna === 2) {
            $encontrado = "$fila$columna";
            break 2;
        }
    }
}
mostrar('Caso 5 — break 2', "encontrado = $encontrado, fila al salir = $fila, columna = $columna");
// Tu predicción: ____________

// ── CASO 6 ────────────────────────────────────────────────────────────────────
$vueltas = 0;
for ($x = 0.0; $x < 1.0; $x += 0.1) {
    $vueltas++;
}
mostrar('Caso 6 — sumar 0.1 hasta llegar a 1', "vueltas = $vueltas");
// Tu predicción (¿10?): ____________

// ── CASO 7 ────────────────────────────────────────────────────────────────────
$numeros = [1, 2, 3];
$vueltas = 0;
foreach ($numeros as $v) {
    $numeros[] = $v * 10;   // agregar elementos MIENTRAS se recorre
    $vueltas++;
}
mostrar('Caso 7 — agregar mientras se recorre', "vueltas = $vueltas, numeros = " . json_encode($numeros));
// Tu predicción: ____________

// ── CASO 8 ────────────────────────────────────────────────────────────────────
$precios = [100, 200, 300];
foreach ($precios as &$p) {
    $p = $p * 2;
}
foreach ($precios as $p) {
    // solo recorrer, no hace nada
}
mostrar('Caso 8 — dos foreach con la misma variable', $precios);
// Tu predicción: ____________


/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESPUESTAS Y EXPLICACIÓN (lee esto DESPUÉS de ejecutar)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CASO 1 -> 1a: [1,2,3,4,5]   1b: [0,3,6,9]
 *    range() de PHP INCLUYE el final (range(1, 5) llega hasta 5). En 1b el
 *    paso es 3: 0, 3, 6, 9 (12 ya se pasa de 10).
 *
 *  CASO 2 -> vueltas = 4, $i = 4
 *    Con <= 3 cuenta 0, 1, 2, 3: cuatro vueltas. Y $i sigue existiendo
 *    después del for, con el valor que hizo fallar la condición (4).
 *
 *  CASO 3 -> n = 10, m = 11
 *    while revisa la condición ANTES: como 10 < 5 es falso, no entra nunca.
 *    do-while revisa DESPUÉS: siempre entra al menos una vez.
 *
 *  CASO 4 -> [1,3,5,7]
 *    continue salta los pares (pasa a la siguiente vuelta); break se ejecuta
 *    con i = 9 (el primer impar mayor que 7) y termina el ciclo.
 *
 *  CASO 5 -> encontrado = B2, fila al salir = B, columna = 2
 *    break 2 sale de LOS DOS ciclos a la vez. Con break (o break 1) solo saldría
 *    del de adentro y seguiría con la fila C.
 *
 *  CASO 6 -> vueltas = 11
 *    0.1 no se puede representar exacto en binario. Sumar 0.1 diez veces da
 *    0.9999999999999999, que todavía es < 1.0, así que da una vuelta más.
 *    Lección: no controles un ciclo con decimales; usa enteros (de 0 a 10)
 *    y divide adentro.
 *
 *  CASO 7 -> vueltas = 3, numeros = [1,2,3,10,20,30]
 *    foreach recorre una COPIA del array tal como estaba al empezar. Los
 *    elementos agregados durante el ciclo no se recorren.
 *
 *  CASO 8 -> [200,400,400]   (¡el último valor se dañó!)
 *    Después del primer foreach, $p sigue siendo una REFERENCIA al último
 *    elemento. El segundo foreach asigna cada valor a $p... es decir, escribe
 *    en el último elemento. Cuando llega al final, copia el penúltimo (400).
 *    Solución: después de un foreach con &, escribe unset($p);
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  - range(1, 5) en Python es [1, 2, 3, 4] (NO incluye el 5). En PHP SÍ lo
 *    incluye: [1, 2, 3, 4, 5]. Al traducir un ciclo de Python, revisa el final.
 *  - En Python, agregar a una lista mientras la recorres con for puede crear un
 *    ciclo infinito. En PHP el foreach recorre una copia: no pasa (caso 7).
 *  - Python no tiene break 2: para salir de dos ciclos se usa una bandera o una
 *    función con return.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Reescribe el caso 6 con un contador entero de 0 a 9 y $x = $i / 10.
 *     ¿Cuántas vueltas da ahora?
 *  2. Agrega unset($p); entre los dos foreach del caso 8 y vuelve a ejecutar.
 *  3. ¿En qué situación real usarías do-while? (pista: un menú que se muestra
 *     al menos una vez)
 */
