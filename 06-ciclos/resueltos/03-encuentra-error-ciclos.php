<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 03 — Cuatro ciclos que casi funcionan
 *  Tipo: encuentra el error
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  SITUACIÓN
 *  Cuatro funciones, cada una con UN error en su ciclo:
 *    - promedio():       promedio de una lista de notas
 *    - numerosHasta():   lista [1, 2, ..., n]. Un compañero la "tradujo" de
 *                        Python, donde se escribe list(range(1, n + 1))
 *    - tablas():         las tablas de multiplicar del 1 al n, cada una del
 *                        x1 al x10 (n tablas x 10 líneas)
 *    - primerNegativo(): el PRIMER número negativo de la lista (o null)
 *
 *  Pistas (solo si llevas 15 minutos sin avanzar):
 *    - Pista 1: ¿dónde se pone en 0 el acumulador?
 *    - Pista 2: ¿range() de PHP incluye el último número?
 *    - Pista 3: ¿cuántas variables de control tienen los dos for anidados?
 *    - Pista 4: cuando encuentra el primero, ¿el ciclo se detiene?
 */

function promedio(array $notas): float
{
    foreach ($notas as $nota) {
        $suma = 0;
        $suma += $nota;
    }
    return $suma / count($notas);
}

function numerosHasta(int $n): array
{
    return range(1, $n + 1);
}

function tablas(int $hasta): array
{
    $lineas = [];
    for ($i = 1; $i <= $hasta; $i++) {
        for ($i = 1; $i <= 10; $i++) {
            $lineas[] = "$i x $i = " . ($i * $i);
        }
    }
    return $lineas;
}

function primerNegativo(array $numeros): ?int
{
    $encontrado = null;
    foreach ($numeros as $numero) {
        if ($numero < 0) {
            $encontrado = $numero;
        }
    }
    return $encontrado;
}

// ── Pruebas automáticas: NO las modifiques, corrige las funciones ─────────────
$pruebas = [
    ['promedio de [4, 3, 5]',                  fn() => promedio([4, 3, 5]),                 4.0],
    ['números hasta 5',                        fn() => numerosHasta(5),                     [1, 2, 3, 4, 5]],
    ['tablas del 1 al 3: cantidad de líneas',  fn() => count(tablas(3)),                    30],
    ['tablas del 1 al 3: última línea',        fn() => tablas(3)[29] ?? 'no existe',        '3 x 10 = 30'],
    ['primer negativo de [5, -2, 8, -7]',      fn() => primerNegativo([5, -2, 8, -7]),     -2],
    ['primer negativo de [1, 2, 3]',           fn() => primerNegativo([1, 2, 3]),          null],
];

foreach ($pruebas as [$descripcion, $prueba, $esperado]) {
    $obtenido = $prueba();
    $ok = $obtenido === $esperado;
    echo ($ok ? "OK    " : "FALLA ") . " $descripcion: esperado " . htmlspecialchars(json_encode($esperado))
       . ", obtenido " . htmlspecialchars(json_encode($obtenido)) . "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  Python:  range(1, n + 1)  ->  1, 2, ..., n       (el final NO se incluye)
 *  PHP:     range(1, $n)     ->  1, 2, ..., n       (el final SÍ se incluye)
 *  Al traducir de Python a PHP, el "+ 1" sobra. Y al revés: un for de PHP con
 *  $i <= $n se traduce en Python a range(1, n + 1).
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. En promedio(), ¿por qué no apareció un "Undefined variable $suma"?
 *     ¿Qué pasaría con promedio([]) en la versión corregida? ¿Cómo lo evitas?
 *  2. En tablas(), ¿por qué el for de afuera da UNA sola vuelta? Sigue el valor
 *     de $i en tu cuaderno.
 *  3. primerNegativo() se puede corregir de dos formas. ¿Cuáles? ¿Cuál es mejor?
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
 *  function promedio(array $notas): float
 *  {
 *      $suma = 0;                             // ERROR 1: se reiniciaba en cada vuelta;
 *      foreach ($notas as $nota) {            // el acumulador va ANTES del ciclo
 *          $suma += $nota;
 *      }
 *      return $suma / count($notas);
 *  }
 *
 *  function numerosHasta(int $n): array
 *  {
 *      return range(1, $n);                   // ERROR 2: en PHP range() ya incluye el final
 *  }
 *
 *  function tablas(int $hasta): array
 *  {
 *      $lineas = [];
 *      for ($tabla = 1; $tabla <= $hasta; $tabla++) {
 *          for ($i = 1; $i <= 10; $i++) {    // ERROR 3: los dos ciclos usaban $i; el de
 *              $lineas[] = "$tabla x $i = " . ($tabla * $i);   // adentro lo dejaba en 11
 *          }
 *      }
 *      return $lineas;
 *  }
 *
 *  function primerNegativo(array $numeros): ?int
 *  {
 *      foreach ($numeros as $numero) {
 *          if ($numero < 0) {
 *              return $numero;                // ERROR 4: seguía buscando y se quedaba con
 *          }                                  // el ÚLTIMO; return (o break) lo detiene
 *      }
 *      return null;
 *  }
 *
 *  Respuesta a la pregunta 1: en la última vuelta $suma sí existe (vale 0 + la
 *  última nota), así que no hay warning: el error es silencioso. Con un array
 *  vacío, count() es 0 y la división lanza DivisionByZeroError; hay que revisar
 *  if (count($notas) === 0) antes.
 */
