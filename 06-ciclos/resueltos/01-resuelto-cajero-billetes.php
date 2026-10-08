<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 01 — ¿Qué billetes entrega el cajero?
 *  Tipo: resuelto y comentado
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  Un cajero tiene billetes de $ 100.000, $ 50.000, $ 20.000 y $ 10.000.
 *  Dado un monto, entregar la MENOR cantidad posible de billetes.
 *  El monto debe ser múltiplo de 10.000 y no superar $ 2.000.000.
 *
 *  CÓMO SE PENSÓ (antes de escribir código)
 *  1. ¿Cómo lo haría una persona? Empieza por el billete más grande: entrega
 *     todos los de 100.000 que quepan, luego los de 50.000 con lo que falta,
 *     y así hasta el más pequeño.
 *  2. Eso son DOS ciclos, uno dentro del otro:
 *       - por fuera, recorrer las denominaciones de mayor a menor (foreach:
 *         se sabe exactamente cuántas son)
 *       - por dentro, "mientras el billete quepa, entregar uno" (while: NO se
 *         sabe cuántas vueltas dará)
 *  3. Validar antes de empezar: si el monto no es múltiplo de 10.000, el ciclo
 *     terminaría con un sobrante imposible de entregar.
 */

const DENOMINACIONES = [100000, 50000, 20000, 10000];   // de mayor a menor

function retirar(int $monto): array
{
    if ($monto <= 0 || $monto > 2000000) {
        throw new InvalidArgumentException("El monto debe estar entre 10.000 y 2.000.000.");
    }
    if ($monto % 10000 !== 0) {
        throw new InvalidArgumentException("El monto debe ser múltiplo de 10.000.");
    }

    $restante = $monto;
    $entrega  = [];   // [denominación => cantidad de billetes]

    foreach (DENOMINACIONES as $billete) {
        $cantidad = 0;
        while ($restante >= $billete) {   // mientras el billete quepa...
            $restante -= $billete;        // ...lo entrego
            $cantidad++;
        }
        if ($cantidad > 0) {
            $entrega[$billete] = $cantidad;
        }
    }

    return $entrega;
}

// ── Uso ───────────────────────────────────────────────────────────────────────
function pesos(int $valor): string
{
    return '$ ' . number_format($valor, 0, ',', '.');
}

foreach ([380000, 70000, 10000, 2000000, 15000, 0] as $monto) {
    try {
        $entrega = retirar($monto);
        $partes = [];
        foreach ($entrega as $billete => $cantidad) {
            $partes[] = "$cantidad x " . pesos($billete);
        }
        $total = array_sum($entrega);
        echo pesos($monto) . " → " . implode(', ', $partes)
           . " ($total " . ($total === 1 ? 'billete' : 'billetes') . ")<br>";
    } catch (InvalidArgumentException $e) {
        echo pesos($monto) . " → " . $e->getMessage() . "<br>";
    }
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAZA — retirar(380000)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *   $billete | vueltas del while           | $restante al salir | $cantidad
 *   ---------+-----------------------------+--------------------+----------
 *   100.000  | 380k→280k→180k→80k (3)      |      80.000        |    3
 *    50.000  | 80k→30k (1)                 |      30.000        |    1
 *    20.000  | 30k→10k (1)                 |      10.000        |    1
 *    10.000  | 10k→0 (1)                   |           0        |    1
 *   resultado: [100000 => 3, 50000 => 1, 20000 => 1, 10000 => 1] → 6 billetes
 *
 *  Hazla tú para 70.000: ¿cuántas veces entra al while de 100.000?
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  - En Python, 380000 // 100000 es la división entera (3). En PHP, / SIEMPRE
 *    da decimal si no es exacta (380000 / 100000 = 3.8). La división entera es
 *    intdiv(380000, 100000), y el residuo es % igual que en Python.
 *  - Por eso este problema también se puede resolver SIN el while:
 *        $cantidad = intdiv($restante, $billete);
 *        $restante = $restante % $billete;
 *    (ver "Para modificar").
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Por qué el ciclo de afuera es foreach y el de adentro es while? ¿Se
 *     podrían cambiar?
 *  2. ¿Qué pasaría si DENOMINACIONES estuviera en orden de menor a mayor?
 *  3. Quita la validación del múltiplo de 10.000 y retira 15.000. ¿Qué
 *     devuelve? ¿Qué billete faltaría?
 *  4. ¿Cuántas vueltas en total hace el while para retirar 2.000.000?
 *
 *  PARA MODIFICAR
 *  - Reemplaza el while por intdiv() y %. ¿Cambia el resultado?
 *  - Agrega un límite de billetes disponibles por denominación (por ejemplo,
 *    solo quedan 2 de 100.000) y haz que el cajero use los siguientes.
 */
