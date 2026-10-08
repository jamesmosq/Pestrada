<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 03 — La bodega desordenada
 *  Tipo: encuentra el error
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  SITUACIÓN
 *  Cuatro funciones para manejar los productos de una bodega:
 *    - posicionDe():     posición de un producto en la lista (o -1 si no está)
 *    - ordenarPorPrecio(): ['producto' => precio] ordenado de menor a mayor
 *                          precio, SIN perder el nombre de cada producto
 *    - conIva():          los mismos precios con 19 % de IVA
 *    - unirBodegas():     junta dos bodegas indexadas por CÓDIGO de producto,
 *                          conservando los códigos
 *
 *  Hay CUATRO errores, uno por función, y todos son trampas del resuelto 02.
 *
 *  Pistas (solo si llevas 15 minutos sin avanzar):
 *    - Pista 1: ¿qué devuelve array_search() si el producto está en la posición 0?
 *    - Pista 2: ¿qué le pasa a las claves con sort()?
 *    - Pista 3: dentro de un foreach, ¿cambiar $precio cambia el array?
 *    - Pista 4: ¿qué hace array_merge() con claves numéricas?
 */

function posicionDe(array $lista, string $producto): int
{
    $pos = array_search($producto, $lista);
    if ($pos) {
        return $pos;
    }
    return -1;
}

function ordenarPorPrecio(array $precios): array
{
    sort($precios);
    return $precios;
}

function conIva(array $precios): array
{
    foreach ($precios as $precio) {
        $precio = round($precio * 1.19);
    }
    return $precios;
}

function unirBodegas(array $bodegaA, array $bodegaB): array
{
    return array_merge($bodegaA, $bodegaB);
}

// ── Pruebas automáticas: NO las modifiques, corrige las funciones ─────────────
$lista   = ['Teclado', 'Mouse', 'Monitor'];
$precios = ['Teclado' => 45000, 'Mouse' => 18000, 'Monitor' => 650000];

$pruebas = [
    ['posición de "Mouse"',               posicionDe($lista, 'Mouse'),    1],
    ['posición de "Teclado" (la primera)', posicionDe($lista, 'Teclado'), 0],
    ['posición de "Cable" (no está)',      posicionDe($lista, 'Cable'),   -1],
    ['ordenar por precio conserva nombres', ordenarPorPrecio($precios),
        ['Mouse' => 18000, 'Teclado' => 45000, 'Monitor' => 650000]],
    ['precios con IVA',                    conIva($precios),
        ['Teclado' => 53550.0, 'Mouse' => 21420.0, 'Monitor' => 773500.0]],
    ['unir bodegas conserva los códigos',  unirBodegas([101 => 'Teclado', 102 => 'Mouse'], [205 => 'Monitor']),
        [101 => 'Teclado', 102 => 'Mouse', 205 => 'Monitor']],
];

foreach ($pruebas as [$descripcion, $obtenido, $esperado]) {
    $ok = $obtenido === $esperado;
    echo ($ok ? "OK    " : "FALLA ") . " $descripcion";
    if (!$ok) {
        echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;esperado: " . htmlspecialchars(json_encode($esperado))
           . "<br>&nbsp;&nbsp;&nbsp;&nbsp;obtenido: " . htmlspecialchars(json_encode($obtenido));
    }
    echo "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, lista.index("Teclado") devuelve 0 y si no lo encuentra lanza
 *  ValueError: no hay forma de confundir "posición 0" con "no está". En PHP,
 *  array_search() devuelve 0 o false, y en un if los dos son "falsos".
 *  Lo mismo pasa con strpos() (07-funciones/resueltos/03).
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. La prueba "posición de Mouse" pasaba aunque la función tenía un error.
 *     ¿Por qué? ¿Qué te dice eso sobre elegir buenos casos de prueba?
 *  2. Escribe conIva() de dos formas correctas: con foreach usando la clave, y
 *     con array_map(). ¿Cuál prefieres?
 *  3. ¿Para qué otra situación real sirve el operador + entre arrays?
 *     (pista: valores por defecto de una configuración)
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
 *  function posicionDe(array $lista, string $producto): int
 *  {
 *      $pos = array_search($producto, $lista);
 *      if ($pos !== false) {                  // ERROR 1: la posición 0 es "falsa" en un if
 *          return $pos;
 *      }
 *      return -1;
 *  }
 *
 *  function ordenarPorPrecio(array $precios): array
 *  {
 *      asort($precios);                       // ERROR 2: sort() borra las claves (los nombres)
 *      return $precios;
 *  }
 *
 *  function conIva(array $precios): array
 *  {
 *      foreach ($precios as $producto => $precio) {
 *          $precios[$producto] = round($precio * 1.19);
 *      }                                      // ERROR 3: $precio es una COPIA; hay que
 *      return $precios;                       // escribir en $precios[$clave]
 *  }
 *
 *  function unirBodegas(array $bodegaA, array $bodegaB): array
 *  {
 *      return $bodegaA + $bodegaB;            // ERROR 4: array_merge renumera las claves
 *  }                                          // numéricas (101, 102, 205 -> 0, 1, 2)
 *
 *  Respuesta a la pregunta 1: Mouse está en la posición 1, y 1 es "verdadero",
 *  así que el if funcionaba. El error solo aparece con el elemento de la posición 0.
 *  Los mejores casos de prueba son los BORDES: el primero, el último, el que no está.
 *
 *  Respuesta a la pregunta 2 (con array_map):
 *      return array_map(fn($p) => round($p * 1.19), $precios);
 */
