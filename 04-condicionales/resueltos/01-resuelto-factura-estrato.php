<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 01 — Factura de energía por estrato
 *  Tipo: resuelto y comentado
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO (reglas simplificadas para el ejercicio)
 *  El kWh cuesta $ 850. Según el estrato de la vivienda:
 *    - Estratos 1, 2 y 3 reciben SUBSIDIO (50 %, 40 % y 15 %), pero solo sobre
 *      los primeros 130 kWh (el "consumo de subsistencia"). Lo que pase de 130
 *      se paga completo.
 *    - Estrato 4 paga la tarifa completa.
 *    - Estratos 5 y 6 pagan una CONTRIBUCIÓN del 20 % sobre todo el consumo.
 *  Calcular el valor a pagar. Si el estrato o el consumo no son válidos, avisar.
 *
 *  CÓMO SE PENSÓ (antes de escribir código)
 *  1. Primero, ¿los datos son válidos? Estrato entre 1 y 6, consumo >= 0.
 *     Validar ANTES de calcular evita resultados absurdos.
 *  2. Hay tres casos con reglas distintas (subsidio / nada / contribución):
 *     un if / elseif / else los separa con claridad.
 *  3. Dentro del subsidio, el porcentaje depende del estrato exacto: eso es
 *     "un valor por cada caso", y para eso match es más claro que muchos if.
 *  4. Lo difícil: el subsidio NO es sobre todo el consumo. Hay que partir el
 *     consumo en dos: lo que cabe en 130 kWh y lo que sobra.
 *       subsistencia = min(consumo, 130)
 *       excedente    = consumo - subsistencia
 */

const TARIFA_KWH   = 850;
const SUBSISTENCIA = 130;

function liquidar(int $estrato, float $kwh): array
{
    // ── 1. Validar ───────────────────────────────────────────────────────────
    if ($estrato < 1 || $estrato > 6) {
        throw new InvalidArgumentException("El estrato $estrato no existe (debe ser de 1 a 6).");
    }
    if ($kwh < 0) {
        throw new InvalidArgumentException("El consumo no puede ser negativo.");
    }

    $base   = $kwh * TARIFA_KWH;   // lo que costaría sin subsidio ni contribución
    $ajuste = 0;                   // negativo = subsidio, positivo = contribución

    // ── 2. Un caso por cada tipo de regla ────────────────────────────────────
    if ($estrato <= 3) {
        $porcentaje = match ($estrato) {
            1 => 0.50,
            2 => 0.40,
            3 => 0.15,
        };
        $subsistencia = min($kwh, SUBSISTENCIA);
        $ajuste = -($subsistencia * TARIFA_KWH * $porcentaje);
    } elseif ($estrato >= 5) {
        $ajuste = $base * 0.20;
    }
    // estrato 4: no hace falta un else, el ajuste ya es 0

    return [
        'base'   => $base,
        'ajuste' => $ajuste,
        'total'  => $base + $ajuste,
    ];
}

// ── Uso ───────────────────────────────────────────────────────────────────────
function pesos(float $valor): string
{
    return '$ ' . number_format($valor, 0, ',', '.');
}

$casos = [
    [2, 200],
    [1, 100],
    [4, 150],
    [5, 100],
    [7, 100],
    [3, -5],
];

foreach ($casos as [$estrato, $kwh]) {
    try {
        $f = liquidar($estrato, $kwh);
        $tipo = $f['ajuste'] < 0 ? 'subsidio' : ($f['ajuste'] > 0 ? 'contribución' : 'sin ajuste');
        echo "Estrato $estrato, $kwh kWh: base " . pesos($f['base']) . ", $tipo " . pesos(abs($f['ajuste']))
           . " → <strong>paga " . pesos($f['total']) . "</strong><br>";
    } catch (InvalidArgumentException $e) {
        echo "Estrato $estrato, $kwh kWh: " . $e->getMessage() . "<br>";
    }
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAZA — liquidar(2, 200)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *   paso                                   | valor
 *   ---------------------------------------+-------------------------------
 *   validación                             | estrato 2 y 200 kWh: válidos
 *   base = 200 * 850                       | 170.000
 *   ¿estrato <= 3?                         | sí -> rama del subsidio
 *   porcentaje = match(2)                  | 0.40
 *   subsistencia = min(200, 130)           | 130
 *   ajuste = -(130 * 850 * 0.40)           | -44.200
 *   total = 170.000 + (-44.200)            | 125.800
 *
 *  Hazla tú para (5, 100): ¿te da 102.000?
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  - En Python se escribe elif; en PHP es elseif (todo junto). "else if"
 *    separado también funciona, pero el estándar es elseif.
 *  - En Python, 1 <= estrato <= 6 es válido. En PHP NO se pueden encadenar
 *    comparaciones: se escribe $estrato >= 1 && $estrato <= 6.
 *  - match de PHP (8.0) NO es el match/case de Python (3.10). El de PHP solo
 *    compara un valor con === y devuelve un resultado; si ningún caso coincide
 *    lanza UnhandledMatchError. Aquí no pasa porque ya se validó antes.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Por qué se valida al principio y no dentro de cada rama?
 *  2. Si se quita la validación, ¿qué pasaría con liquidar(0, 100)? ¿Y con
 *     liquidar(9, 100)? Pruébalo.
 *  3. ¿Por qué el estrato 4 no necesita un else?
 *  4. ¿Qué cambiarías si mañana el subsidio del estrato 3 pasa a 20 %? ¿Cuántas
 *     líneas tocas?
 *
 *  PARA MODIFICAR
 *  - Agrega un cargo fijo de $ 9.500 que pagan todos los estratos.
 *  - Haz que el consumo de subsistencia sea 173 kWh para municipios de clima
 *    cálido (agrega un parámetro bool $climaCalido).
 */
