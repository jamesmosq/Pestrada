<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 03 — El carrito que cobra mal
 *  Tipo: encuentra el error
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  SITUACIÓN
 *  Una tienda calcula el valor de una compra con cuatro funciones. Las reglas son:
 *    - subtotal = suma de (precio x cantidad) de cada producto
 *    - los cupones que CONTIENEN la palabra "VIP" dan 20 % de descuento
 *    - el envío es gratis DESDE $100.000 (100.000 incluido); si no, cuesta $12.000
 *
 *  El código no muestra ningún error de PHP... pero las pruebas de abajo fallan.
 *  Hay CUATRO errores, uno en cada función.
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. Ejecútalo y mira qué pruebas dicen FALLA.
 *  2. Por cada prueba que falla, encuentra la línea culpable y explica POR QUÉ falla.
 *  3. Corrígela aquí mismo y vuelve a ejecutar hasta que todo diga OK.
 *  4. Solo al final, compara con la versión corregida al pie del archivo.
 *
 *  Pistas (léelas solo si llevas 15 minutos sin avanzar):
 *    - Pista 1: en un ciclo, ¿se está SUMANDO o REEMPLAZANDO?
 *    - Pista 2: ¿qué devuelve una función que no tiene return?
 *    - Pista 3: ¿qué devuelve strpos() si el texto está en la posición 0?
 *    - Pista 4: "desde 100.000" ¿incluye el 100.000?
 */

function subtotal($carrito)
{
    $total = 0;
    foreach ($carrito as $item) {
        $total = $item['precio'] * $item['cantidad'];
    }
    return $total;
}

function descontar($total, $porcentaje)
{
    $total - $total * $porcentaje / 100;
}

function aplicarCupon($total, $cupon)
{
    if (strpos($cupon, 'VIP')) {
        $total = descontar($total, 20);
    }
    return $total;
}

function costoEnvio($total)
{
    return $total > 100000 ? 0 : 12000;
}

// ── Pruebas automáticas: NO las modifiques, corrige las funciones ─────────────
$carrito = [
    ['producto' => 'Teclado', 'precio' => 45000, 'cantidad' => 2],
    ['producto' => 'Mouse',   'precio' => 10000, 'cantidad' => 1],
];

$pruebas = [
    ['subtotal del carrito',                subtotal($carrito),                  100000],
    ['envío con compra de 100.000',         costoEnvio(100000),                  0],
    ['envío con compra de 99.999',          costoEnvio(99999),                   12000],
    ['cupón "VIP-AMIGO" sobre 100.000',     aplicarCupon(100000, 'VIP-AMIGO'),   80000],
    ['cupón "CLIENTE-VIP" sobre 100.000',   aplicarCupon(100000, 'CLIENTE-VIP'), 80000],
    ['cupón "NAVIDAD" sobre 100.000',       aplicarCupon(100000, 'NAVIDAD'),     100000],
];

foreach ($pruebas as [$descripcion, $obtenido, $esperado]) {
    $ok = $obtenido == $esperado;
    echo ($ok ? "OK    " : "FALLA ") . " $descripcion: esperado $esperado, obtenido "
       . var_export($obtenido, true) . "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  - Python tiene "VIP" in cupon, que devuelve True o False. PHP tiene
 *    strpos(), que devuelve la POSICIÓN (0, 1, 2...) o false. Y en un if,
 *    la posición 0 se trata como falso. Usa str_contains($cupon, 'VIP') (PHP 8)
 *    o compara strpos(...) !== false.
 *  - Igual que en Python (que devuelve None), una función de PHP sin return
 *    devuelve null. PHP no avisa: el null sigue viajando por el programa.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Ninguno de los 4 errores hizo que PHP mostrara un mensaje. ¿Por qué esos
 *     errores son más peligrosos que un "Fatal error"?
 *  2. ¿Cómo te ayudaron las pruebas automáticas a encontrarlos?
 *  3. Si las funciones tuvieran tipos (function descontar(float $total, ...): float),
 *     ¿cuál de los 4 errores habría detectado PHP solo? Pruébalo.
 *
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
 *  function subtotal(array $carrito): float
 *  {
 *      $total = 0;
 *      foreach ($carrito as $item) {
 *          $total += $item['precio'] * $item['cantidad'];     // ERROR 1: era "=", debe acumular con "+="
 *      }
 *      return $total;
 *  }
 *
 *  function descontar(float $total, float $porcentaje): float
 *  {
 *      return $total - $total * $porcentaje / 100;            // ERROR 2: faltaba el return
 *  }
 *
 *  function aplicarCupon(float $total, string $cupon): float
 *  {
 *      if (str_contains($cupon, 'VIP')) {                     // ERROR 3: strpos() da 0 si empieza por VIP
 *          $total = descontar($total, 20);
 *      }
 *      return $total;
 *  }
 *
 *  function costoEnvio(float $total): int
 *  {
 *      return $total >= 100000 ? 0 : 12000;                   // ERROR 4: "desde" incluye el 100.000: >=
 *  }
 *
 *  Respuesta a la pregunta 3: con ": float" en descontar(), PHP lanza un
 *  TypeError porque la función no devuelve nada. Los tipos convierten errores
 *  silenciosos en errores visibles.
 */
