<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 04 — ¿Cuántos meses para llegar a la meta?
 *  Tipo: compara soluciones
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  Un aprendiz ahorra $ 150.000 cada mes en una cuenta que paga 1 % mensual
 *  sobre lo acumulado. ¿Cuántos meses necesita para reunir $ 2.000.000 para
 *  un computador?
 *
 *  La diferencia con los ciclos de los otros resueltos: aquí NO se sabe de
 *  antemano cuántas vueltas habrá. Eso es justamente lo que se resuelve.
 *  Abajo hay TRES soluciones que dan el mismo resultado.
 */

const AHORRO_MENSUAL = 150000;
const INTERES        = 0.01;
const META           = 2000000;

// ════════════════════════════════════════════════════════════════════════════
//  SOLUCIÓN A — while: "mientras no llegue a la meta, sigue"
// ════════════════════════════════════════════════════════════════════════════
function mesesConWhile(): array
{
    $saldo = 0;
    $meses = 0;
    while ($saldo < META) {
        $saldo = $saldo * (1 + INTERES) + AHORRO_MENSUAL;
        $meses++;
    }
    return [$meses, $saldo];
}

// ════════════════════════════════════════════════════════════════════════════
//  SOLUCIÓN B — for con break: "cuenta meses y para cuando llegue"
// ════════════════════════════════════════════════════════════════════════════
function mesesConFor(): array
{
    $saldo = 0;
    for ($mes = 1; $mes <= 600; $mes++) {   // 600 meses (50 años) como límite de seguridad
        $saldo = $saldo * (1 + INTERES) + AHORRO_MENSUAL;
        if ($saldo >= META) {
            return [$mes, $saldo];
        }
    }
    return [null, $saldo];                  // no llegó ni en 50 años
}

// ════════════════════════════════════════════════════════════════════════════
//  SOLUCIÓN C — do-while: "ahorra un mes y luego pregunta"
// ════════════════════════════════════════════════════════════════════════════
function mesesConDoWhile(): array
{
    $saldo = 0;
    $meses = 0;
    do {
        $saldo = $saldo * (1 + INTERES) + AHORRO_MENSUAL;
        $meses++;
    } while ($saldo < META);
    return [$meses, $saldo];
}

foreach (['A (while)' => mesesConWhile(), 'B (for + break)' => mesesConFor(),
          'C (do-while)' => mesesConDoWhile()] as $solucion => [$meses, $saldo]) {
    echo "$solucion: $meses meses, saldo final $ " . number_format($saldo, 0, ',', '.') . "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  COMPARACIÓN
 * ════════════════════════════════════════════════════════════════════════════
 *
 *                         | A while             | B for + break         | C do-while
 *  -----------------------+---------------------+-----------------------+---------------------
 *  Pregunta la condición  | ANTES de cada vuelta| dentro, con un if     | DESPUÉS de cada vuelta
 *  Mínimo de vueltas      | 0                   | 1                     | 1
 *  Se lee como...         | "mientras falte"    | "mes a mes, hasta..." | "haz, y repite si falta"
 *  Riesgo                 | ciclo infinito si   | ninguno: tiene un     | ciclo infinito si
 *                         | nunca llega         | límite (600)          | nunca llega
 *  Contador de meses      | aparte ($meses)     | es la variable del for| aparte ($meses)
 *
 *  ¿Cuál usar?
 *  - Se sabe cuántas vueltas (los 12 meses del año, los elementos de un
 *    array) → for o foreach.
 *  - No se sabe cuántas, depende de una condición → while. Es la más clara
 *    para este problema.
 *  - Tiene que ejecutarse al menos una vez (mostrar un menú, pedir un dato
 *    hasta que sea válido) → do-while.
 *  - Si la condición podría no cumplirse NUNCA (por ejemplo, si el ahorro
 *    mensual fuera 0), B es la más segura: el límite evita que la página se
 *    quede pensando para siempre.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  Python no tiene do-while (se imita con "while True:" y un break al final).
 *  En PHP existe, pero fíjate en el punto y coma: } while ($condicion);
 *  Y un ciclo infinito en PHP web no se queda "colgado" para siempre: PHP lo
 *  corta cuando se cumple max_execution_time (30 segundos por defecto; en WAMP
 *  viene en 120) con un Fatal error. En la consola, en cambio, no hay límite.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Cambia AHORRO_MENSUAL a 0. ¿Qué pasa con cada solución? (No la ejecutes
 *     en el navegador sin pensarlo: ¿cuál se quedaría girando?)
 *  2. Haz la traza de los 3 primeros meses en tu cuaderno: saldo después de cada mes.
 *  3. ¿Cuántos meses tardaría SIN intereses? ¿Cuánto aportan los intereses?
 *  4. Si la meta fuera 0, ¿qué devuelve cada solución? ¿Cuál tiene razón?
 *
 *  PARA MODIFICAR
 *  - Muestra una tabla mes a mes con el saldo (¿qué ciclo usarías?).
 *  - Agrega un aumento del ahorro de $ 10.000 cada 6 meses.
 */
