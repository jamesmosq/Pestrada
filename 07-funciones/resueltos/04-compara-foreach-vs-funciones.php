<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 04 — Dos formas de resolver lo mismo
 *  Tipo: compara dos soluciones
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  De una lista de aprendices con su nota final, obtener:
 *    a) los nombres de los que aprobaron (nota >= 3.0), de mayor a menor nota
 *    b) el promedio de los aprobados
 *
 *  Abajo hay DOS soluciones correctas que imprimen exactamente lo mismo.
 *  La pregunta no es "cuál funciona" (las dos funcionan), sino
 *  "cuál se entiende mejor, cuál es más fácil de cambiar y cuándo usar cada una".
 */

$aprendices = [
    ['nombre' => 'Ana',   'nota' => 4.1],
    ['nombre' => 'Luis',  'nota' => 2.5],
    ['nombre' => 'Marta', 'nota' => 3.8],
    ['nombre' => 'Pedro', 'nota' => 2.9],
    ['nombre' => 'Sofía', 'nota' => 4.6],
];

// ════════════════════════════════════════════════════════════════════════════
//  SOLUCIÓN A — con foreach (paso a paso)
// ════════════════════════════════════════════════════════════════════════════
function aprobadosConForeach(array $aprendices): array
{
    // 1. Filtrar: recorrer y quedarse con los que cumplen
    $aprobados = [];
    foreach ($aprendices as $a) {
        if ($a['nota'] >= 3.0) {
            $aprobados[] = $a;
        }
    }

    // 2. Ordenar de mayor a menor (<=> compara: devuelve -1, 0 o 1)
    usort($aprobados, function ($x, $y) {
        return $y['nota'] <=> $x['nota'];
    });

    // 3. Sacar nombres y sumar notas en un mismo recorrido
    $nombres = [];
    $suma = 0;
    foreach ($aprobados as $a) {
        $nombres[] = $a['nombre'];
        $suma += $a['nota'];
    }

    // 4. Promedio (cuidado con dividir entre cero si nadie aprobó)
    $promedio = count($aprobados) > 0 ? $suma / count($aprobados) : 0;

    return ['nombres' => $nombres, 'promedio' => round($promedio, 2)];
}

// ════════════════════════════════════════════════════════════════════════════
//  SOLUCIÓN B — con funciones de arrays (cada paso es una función)
// ════════════════════════════════════════════════════════════════════════════
function aprobadosConFunciones(array $aprendices): array
{
    $aprobados = array_filter($aprendices, fn($a) => $a['nota'] >= 3.0);

    usort($aprobados, fn($x, $y) => $y['nota'] <=> $x['nota']);

    $notas = array_column($aprobados, 'nota');

    return [
        'nombres'  => array_column($aprobados, 'nombre'),
        'promedio' => $notas ? round(array_sum($notas) / count($notas), 2) : 0,
    ];
}

// ── Las dos dan lo mismo ──────────────────────────────────────────────────────
foreach (['A (foreach)' => aprobadosConForeach($aprendices),
          'B (funciones)' => aprobadosConFunciones($aprendices)] as $solucion => $r) {
    echo "Solución $solucion: " . implode(', ', $r['nombres'])
       . " — promedio {$r['promedio']}<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  COMPARACIÓN
 * ════════════════════════════════════════════════════════════════════════════
 *
 *                         | A (foreach)                 | B (funciones de arrays)
 *  -----------------------+-----------------------------+-----------------------------
 *  Líneas                 | ~20                         | ~8
 *  Se lee como...         | "cómo" se hace, paso a paso | "qué" se quiere: filtrar,
 *                         |                             | ordenar, extraer
 *  Fácil de depurar       | Sí: se puede poner un echo  | Menos: cada paso es una
 *                         | dentro del ciclo            | sola expresión
 *  Hay que conocer        | foreach, if, usort          | array_filter, array_column,
 *                         |                             | usort, fn
 *  Recorridos del array   | 2 (y se puede hacer en 1)   | varios (uno por función)
 *
 *  ¿Cuál usar?
 *  - Cuando cada paso es simple (filtrar, transformar, sumar), B es más clara:
 *    cada línea dice qué hace.
 *  - Cuando dentro del ciclo hay varias decisiones (if, else, contadores,
 *    break), A es más clara y más fácil de depurar.
 *  - Con listas de cientos o miles de elementos la diferencia de velocidad no
 *    importa. Elige por legibilidad.
 *
 *  Dato útil: Laravel trabaja casi todo con el estilo B. Sus "Collections" se
 *  escriben así:  $aprendices->filter(...)->sortByDesc('nota')->pluck('nombre')
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, [a for a in lista if a["nota"] >= 3] crea una lista NUEVA con
 *  índices 0, 1, 2... En PHP, array_filter CONSERVA LAS CLAVES originales.
 *  Mira lo que pasa abajo: las claves quedan 0, 2, 4 (con "huecos" donde
 *  estaban Luis y Pedro), así que $filtrados[1] no existe.
 *  (En la solución B no se nota porque usort() reenumera las claves.)
 *  Si necesitas las claves desde 0, usa array_values().
 */

echo "<hr>";

$filtrados = array_filter($aprendices, fn($a) => $a['nota'] >= 3.0);
echo "Claves después de array_filter: " . implode(', ', array_keys($filtrados)) . "<br>";
echo "¿Existe \$filtrados[1]? " . (isset($filtrados[1]) ? 'sí' : 'no (Luis fue filtrado)') . "<br>";

$reindexados = array_values($filtrados);
echo "Claves después de array_values: " . implode(', ', array_keys($reindexados)) . "<br>";

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Qué pasa en cada solución si NINGÚN aprendiz aprobó? Pruébalo dejando
 *     todas las notas por debajo de 3.0.
 *  2. La solución A recorre los aprobados dos veces. ¿Cómo la dejarías con un
 *     solo foreach? ¿Vale la pena?
 *  3. Agrega "la nota más alta" a cada solución. ¿En cuál fue más fácil?
 *  4. Explica con tus palabras qué hace el operador <=> (se llama "nave espacial").
 */
