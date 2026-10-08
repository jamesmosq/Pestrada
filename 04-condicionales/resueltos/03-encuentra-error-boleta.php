<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 03 — La boleta de cine mal cobrada
 *  Tipo: encuentra el error
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  SITUACIÓN
 *  Un cine calcula el precio de la boleta así:
 *    1. Edad válida: de 0 a 120 años. Si no, "Edad no válida".
 *    2. Precio base por edad: menores de 12 → 9.000 | de 12 a 59 → 14.000 |
 *       60 o más → 8.000
 *    3. Descuento por día: miércoles 50 %, jueves 20 %, los demás días nada.
 *    4. Recargo por sala (se suma DESPUÉS del descuento):
 *       2D → 0 | 3D → 4.000 | IMAX → 7.000
 *
 *  El código tiene CUATRO errores, uno en cada función. Ejecuta, mira las
 *  pruebas que fallan, corrige aquí mismo y compara al final.
 *
 *  Pistas (solo si llevas 15 minutos sin avanzar):
 *    - Pista 1: ¿una edad puede ser menor que 0 Y mayor que 120 al mismo tiempo?
 *    - Pista 2: si alguien tiene 70 años, ¿en cuál condición entra primero?
 *    - Pista 3: en un switch, ¿qué pasa si a un case le falta el break?
 *    - Pista 4: ¿qué hace match cuando ningún caso coincide?
 */

function edadValida(int $edad): bool
{
    if ($edad < 0 && $edad > 120) {
        return false;
    }
    return true;
}

function precioBase(int $edad): int
{
    if ($edad < 12) {
        $base = 9000;
    } elseif ($edad >= 12) {
        $base = 14000;
    } elseif ($edad >= 60) {
        $base = 8000;
    }
    return $base;
}

function descuentoDia(string $dia): float
{
    $descuento = 0;
    switch ($dia) {
        case 'miercoles':
            $descuento = 0.50;
        case 'jueves':
            $descuento = 0.20;
            break;
    }
    return $descuento;
}

function recargoSala(string $sala): int
{
    return match ($sala) {
        '2D' => 0,
        '3D' => 4000,
    };
}

function precioBoleta(int $edad, string $dia, string $sala): int|string
{
    if (!edadValida($edad)) {
        return 'Edad no válida';
    }
    $precio = precioBase($edad) * (1 - descuentoDia($dia));
    return (int) round($precio) + recargoSala($sala);
}

// ── Pruebas automáticas: NO las modifiques, corrige las funciones ─────────────
$pruebas = [
    ['adulto, lunes, 2D',           30,  'lunes',     '2D',   14000],
    ['adulto mayor, lunes, 2D',     70,  'lunes',     '2D',   8000],
    ['niño, miércoles, 2D',         8,   'miercoles', '2D',   4500],
    ['adulto, jueves, 3D',          30,  'jueves',    '3D',   15200],
    ['adulto, lunes, IMAX',         30,  'lunes',     'IMAX', 21000],
    ['edad 150',                    150, 'lunes',     '2D',   'Edad no válida'],
];

foreach ($pruebas as [$descripcion, $edad, $dia, $sala, $esperado]) {
    try {
        $obtenido = precioBoleta($edad, $dia, $sala);
    } catch (Throwable $e) {
        $obtenido = 'ERROR: ' . get_class($e);
    }
    $ok = $obtenido === $esperado;
    echo ($ok ? "OK    " : "FALLA ") . " $descripcion: esperado " . var_export($esperado, true)
       . ", obtenido " . var_export($obtenido, true) . "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  Python no tenía switch (y su match/case no "se cae" al siguiente caso).
 *  En PHP, si un case no termina con break, la ejecución SIGUE en el case de
 *  abajo aunque no coincida (se llama "fall-through"). Es una de las fuentes
 *  de error más comunes. El match de PHP 8 no tiene ese problema: cada caso
 *  es independiente y no lleva break.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Cuál de los 4 errores es el único que PHP te avisa? ¿Por qué los otros
 *     tres no producen ningún mensaje?
 *  2. ¿El fall-through del switch puede ser útil a propósito? Busca un ejemplo
 *     (pista: sábado y domingo con el mismo precio).
 *  3. Reescribe descuentoDia() con match. ¿Cuál versión se lee mejor?
 *  4. En precioBase(), después de corregir el orden, ¿hace falta el último elseif
 *     o basta con un else? ¿Qué ventaja tiene el else?
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
 *  function edadValida(int $edad): bool
 *  {
 *      if ($edad < 0 || $edad > 120) {        // ERROR 1: con && nunca se cumple; debe ser ||
 *          return false;
 *      }
 *      return true;
 *  }
 *
 *  function precioBase(int $edad): int
 *  {
 *      if ($edad < 12) {
 *          $base = 9000;
 *      } elseif ($edad >= 60) {               // ERROR 2: el caso más específico va PRIMERO;
 *          $base = 8000;                      // antes, ">= 12" atrapaba también a los de 70
 *      } else {
 *          $base = 14000;
 *      }
 *      return $base;
 *  }
 *
 *  function descuentoDia(string $dia): float
 *  {
 *      $descuento = 0;
 *      switch ($dia) {
 *          case 'miercoles':
 *              $descuento = 0.50;
 *              break;                         // ERROR 3: faltaba: seguía al case 'jueves'
 *          case 'jueves':
 *              $descuento = 0.20;
 *              break;
 *      }
 *      return $descuento;
 *  }
 *
 *  function recargoSala(string $sala): int
 *  {
 *      return match ($sala) {
 *          '2D'    => 0,
 *          '3D'    => 4000,
 *          'IMAX'  => 7000,                   // ERROR 4: faltaba el caso; match sin caso
 *      };                                     // ni default lanza UnhandledMatchError
 *  }
 *
 *  Respuesta a la pregunta 1: solo el error 4, porque match lanza una excepción
 *  si ningún caso coincide. Los otros tres son código válido que hace algo
 *  distinto de lo que se quería: PHP no puede saber cuál era tu intención.
 */
