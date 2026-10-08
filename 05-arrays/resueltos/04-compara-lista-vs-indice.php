<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 04 — Buscar en una lista o tener un índice
 *  Tipo: compara soluciones
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  En la portería del centro de formación se escanea el documento de cada
 *  aprendiz que entra, y hay que mostrar su nombre y su ficha. Los aprendices
 *  están en una lista (como llegarían de la base de datos).
 *
 *  Dos soluciones correctas:
 *    A. Recorrer la lista hasta encontrar el documento.
 *    B. Construir UNA vez un array indexado por documento y luego buscar
 *       directamente por la clave.
 *  Las dos encuentran lo mismo. La diferencia está en CUÁNTO TRABAJO hacen.
 */

// Lista de prueba: 2.000 aprendices generados
$aprendices = [];
for ($i = 1; $i <= 2000; $i++) {
    $aprendices[] = [
        'documento' => (string) (1000000000 + $i),
        'nombre'    => "Aprendiz $i",
        'ficha'     => $i % 2 === 0 ? '2758634' : '2812045',
    ];
}

// ════════════════════════════════════════════════════════════════════════════
//  SOLUCIÓN A — Recorrer la lista cada vez
// ════════════════════════════════════════════════════════════════════════════
function buscarRecorriendo(array $aprendices, string $documento, int &$comparaciones): ?array
{
    foreach ($aprendices as $aprendiz) {
        $comparaciones++;
        if ($aprendiz['documento'] === $documento) {
            return $aprendiz;
        }
    }
    return null;
}

// ════════════════════════════════════════════════════════════════════════════
//  SOLUCIÓN B — Indexar una vez, buscar por clave
// ════════════════════════════════════════════════════════════════════════════
// array_column($lista, null, 'documento') arma:
//   ['1000000001' => [aprendiz completo], '1000000002' => [...], ...]
$porDocumento = array_column($aprendices, null, 'documento');

function buscarPorIndice(array $porDocumento, string $documento): ?array
{
    return $porDocumento[$documento] ?? null;   // un solo paso, sin recorrer
}

// ── Simular 500 personas entrando ─────────────────────────────────────────────
$entradas = [];
for ($i = 0; $i < 500; $i++) {
    $entradas[] = (string) (1000000000 + random_int(1, 2100));   // algunos no existen
}

$comparacionesA = 0;
$encontradosA = 0;
foreach ($entradas as $doc) {
    if (buscarRecorriendo($aprendices, $doc, $comparacionesA)) {
        $encontradosA++;
    }
}

$encontradosB = 0;
foreach ($entradas as $doc) {
    if (buscarPorIndice($porDocumento, $doc)) {
        $encontradosB++;
    }
}

echo "500 búsquedas entre 2.000 aprendices:<br>";
echo "A (recorrer): encontró $encontradosA, hizo " . number_format($comparacionesA, 0, ',', '.') . " comparaciones<br>";
echo "B (índice):   encontró $encontradosB, hizo 500 búsquedas directas (más 2.000 pasos UNA vez para armar el índice)<br>";

$ejemplo = buscarPorIndice($porDocumento, '1000000042');
echo "<br>Ejemplo: documento 1000000042 → {$ejemplo['nombre']}, ficha {$ejemplo['ficha']}<br>";

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  COMPARACIÓN
 * ════════════════════════════════════════════════════════════════════════════
 *
 *                          | A (recorrer la lista)        | B (índice por documento)
 *  ------------------------+------------------------------+------------------------------
 *  Código                  | un foreach con if            | array_column + ?? (2 líneas)
 *  Trabajo por búsqueda    | hasta 2.000 comparaciones    | 1 paso
 *                          | (en promedio, la mitad)      |
 *  Trabajo previo          | ninguno                      | armar el índice una vez
 *  Si el documento no está | recorre TODA la lista        | 1 paso
 *  Memoria                 | la lista                     | la lista + el índice
 *  Cuándo conviene         | una sola búsqueda, o buscar  | muchas búsquedas por el
 *                          | por condiciones (nota > 4)   | mismo dato exacto
 *
 *  En una app web real, la búsqueda la hace la BASE DE DATOS: un índice en la
 *  columna documento (CREATE INDEX) es exactamente la idea de la solución B,
 *  y es la razón por la que buscar por la clave primaria es tan rápido.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python esta comparación es "list contra dict": buscar en una lista con
 *  un for es lento y buscar en un dict por clave es instantáneo. En PHP los dos
 *  son "array", pero funciona igual: buscar por CLAVE es instantáneo, buscar
 *  un VALOR (con foreach, in_array o array_search) recorre todo.
 *
 *  Detalle: los documentos se guardaron como texto ('1000000042'). Como claves,
 *  PHP los convierte en enteros (ver el resuelto 02), pero $porDocumento['1000000042']
 *  y $porDocumento[1000000042] encuentran lo mismo, así que aquí no causa problemas.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Ejecuta varias veces: ¿por qué cambia el número de comparaciones de A y
 *     el de encontrados?
 *  2. ¿Qué pasaría con el índice si dos aprendices tuvieran el mismo documento?
 *  3. Si hay que buscar "todos los de la ficha 2758634", ¿sirve el índice por
 *     documento? ¿Qué índice armarías? (pista: resuelto 01 de esta carpeta)
 *  4. ¿Qué hace array_column($aprendices, 'nombre', 'documento')? Pruébalo.
 */
