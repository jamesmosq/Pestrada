<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 01 — Reporte de notas por ficha
 *  Tipo: resuelto y comentado
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  Se tiene una lista de aprendices con su ficha y su nota final. Hacer un
 *  reporte que, POR CADA FICHA, muestre: cuántos aprendices tiene, el promedio
 *  de notas y quién tiene la mejor nota.
 *
 *  CÓMO SE PENSÓ (antes de escribir código)
 *  1. La lista llega "plana": un aprendiz detrás de otro, con las fichas
 *     mezcladas. Para sacar datos POR FICHA, primero hay que AGRUPAR:
 *        [ '2758634' => [aprendiz, aprendiz, ...],
 *          '2812045' => [aprendiz, ...] ]
 *     Es un array asociativo (clave = ficha) cuyos valores son arrays (lista).
 *  2. Para agrupar basta un foreach con  $porFicha[$ficha][] = $aprendiz;
 *     PHP crea la lista de esa ficha la primera vez que aparece.
 *  3. Con los grupos armados, cada estadística se calcula sobre un grupo:
 *        cantidad -> count()
 *        promedio -> array_sum() / count()
 *        mejor    -> recorrer y quedarse con el de nota más alta
 */

$aprendices = [
    ['nombre' => 'Ana',    'ficha' => '2758634', 'nota' => 4.2],
    ['nombre' => 'Luis',   'ficha' => '2812045', 'nota' => 3.1],
    ['nombre' => 'Marta',  'ficha' => '2758634', 'nota' => 4.7],
    ['nombre' => 'Pedro',  'ficha' => '2812045', 'nota' => 2.8],
    ['nombre' => 'Sofía',  'ficha' => '2812045', 'nota' => 4.0],
    ['nombre' => 'Andrés', 'ficha' => '2758634', 'nota' => 3.6],
];

// ── 1. Agrupar ────────────────────────────────────────────────────────────────
$porFicha = [];
foreach ($aprendices as $aprendiz) {
    $ficha = $aprendiz['ficha'];
    $porFicha[$ficha][] = $aprendiz;   // si la ficha no existía, PHP crea la lista
}

// ── 2. Calcular por grupo ─────────────────────────────────────────────────────
function estadisticas(array $grupo): array
{
    $notas = array_column($grupo, 'nota');   // [4.2, 4.7, 3.6]

    $mejor = $grupo[0];
    foreach ($grupo as $aprendiz) {
        if ($aprendiz['nota'] > $mejor['nota']) {
            $mejor = $aprendiz;
        }
    }

    return [
        'cantidad' => count($grupo),
        'promedio' => round(array_sum($notas) / count($notas), 2),
        'mejor'    => $mejor['nombre'] . ' (' . $mejor['nota'] . ')',
    ];
}

// ── 3. Mostrar ────────────────────────────────────────────────────────────────
ksort($porFicha);   // ordenar por número de ficha (la CLAVE)

echo "<table border='1' cellpadding='6'>";
echo "<tr><th>Ficha</th><th>Aprendices</th><th>Promedio</th><th>Mejor nota</th></tr>";
foreach ($porFicha as $ficha => $grupo) {
    $e = estadisticas($grupo);
    echo "<tr><td>$ficha</td><td>{$e['cantidad']}</td><td>{$e['promedio']}</td><td>{$e['mejor']}</td></tr>";
}
echo "</table>";

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAZA — cómo va quedando $porFicha en cada vuelta del foreach
 * ════════════════════════════════════════════════════════════════════════════
 *
 *   vuelta | aprendiz | $porFicha después
 *   -------+----------+---------------------------------------------------------
 *     1    | Ana      | ['2758634' => [Ana]]
 *     2    | Luis     | ['2758634' => [Ana],              '2812045' => [Luis]]
 *     3    | Marta    | ['2758634' => [Ana, Marta],       '2812045' => [Luis]]
 *     4    | Pedro    | ['2758634' => [Ana, Marta],       '2812045' => [Luis, Pedro]]
 *     5    | Sofía    | ['2758634' => [Ana, Marta],       '2812045' => [Luis, Pedro, Sofía]]
 *     6    | Andrés   | ['2758634' => [Ana, Marta, Andrés], '2812045' => [Luis, Pedro, Sofía]]
 *
 *  Resultado esperado:
 *    2758634 | 3 | 4.17 | Marta (4.7)
 *    2812045 | 3 | 3.3  | Sofía (4)
 *
 *  Para ver la estructura real, agrega al final:  echo '<pre>'; print_r($porFicha);
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, por_ficha[ficha].append(a) da KeyError si la ficha todavía no
 *  existe (hay que usar setdefault o defaultdict). En PHP, $porFicha[$ficha][] = $a
 *  crea la lista automáticamente. Cómodo... pero también significa que un error
 *  de escritura en la clave crea un grupo nuevo sin avisar.
 *
 *  Y otra: en PHP la lista y el diccionario son el MISMO tipo (array). Un array
 *  puede tener claves 0, 1, 2 (como lista) o claves de texto (como diccionario).
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Qué pasaría si un aprendiz tuviera la ficha escrita '2758634 ' (con un
 *     espacio al final)? ¿Cómo lo evitarías?
 *  2. ¿Por qué estadisticas() empieza con $mejor = $grupo[0] y no con $mejor = null?
 *  3. ¿Qué hace ksort() y en qué se diferencia de sort()? ¿Qué pasaría si usas
 *     sort($porFicha)?
 *  4. Reemplaza el foreach de "mejor" por usort() + quedarte con el primero.
 *     ¿Cuál prefieres?
 *
 *  PARA MODIFICAR
 *  - Agrega una columna "Aprobados" (nota >= 3.0) por ficha.
 *  - Muestra debajo de la tabla el mejor aprendiz de TODAS las fichas.
 */
