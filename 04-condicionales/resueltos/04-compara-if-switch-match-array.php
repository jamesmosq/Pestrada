<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 04 — Cuatro formas de decidir
 *  Tipo: compara soluciones
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  Una biblioteca atiende con este horario:
 *    lunes a viernes → 7:00 a 19:00 | sábado y domingo → 8:00 a 13:00
 *  Cualquier otro texto → "Día no válido".
 *
 *  Abajo está resuelto de CUATRO formas. Las cuatro dan el mismo resultado.
 *  Al final hay un segundo problema (rangos de notas) para ver cuándo cada
 *  forma deja de servir.
 */

// ── A. if / elseif ────────────────────────────────────────────────────────────
function horarioIf(string $dia): string
{
    $dia = strtolower($dia);
    if ($dia === 'lunes' || $dia === 'martes' || $dia === 'miercoles'
        || $dia === 'jueves' || $dia === 'viernes') {
        return '7:00 a 19:00';
    } elseif ($dia === 'sabado' || $dia === 'domingo') {
        return '8:00 a 13:00';
    } else {
        return 'Día no válido';
    }
}

// ── B. switch (aprovecha el fall-through: varios case juntos) ────────────────
function horarioSwitch(string $dia): string
{
    switch (strtolower($dia)) {
        case 'lunes':
        case 'martes':
        case 'miercoles':
        case 'jueves':
        case 'viernes':
            return '7:00 a 19:00';   // return sale de la función: no hace falta break
        case 'sabado':
        case 'domingo':
            return '8:00 a 13:00';
        default:
            return 'Día no válido';
    }
}

// ── C. match (PHP 8): varios valores separados por coma ──────────────────────
function horarioMatch(string $dia): string
{
    return match (strtolower($dia)) {
        'lunes', 'martes', 'miercoles', 'jueves', 'viernes' => '7:00 a 19:00',
        'sabado', 'domingo'                                 => '8:00 a 13:00',
        default                                             => 'Día no válido',
    };
}

// ── D. array como "tabla de búsqueda" ────────────────────────────────────────
function horarioArray(string $dia): string
{
    $semana = '7:00 a 19:00';
    $finde  = '8:00 a 13:00';
    $horarios = [
        'lunes' => $semana, 'martes' => $semana, 'miercoles' => $semana,
        'jueves' => $semana, 'viernes' => $semana,
        'sabado' => $finde, 'domingo' => $finde,
    ];
    return $horarios[strtolower($dia)] ?? 'Día no válido';
}

// ── Las cuatro dan lo mismo ───────────────────────────────────────────────────
foreach (['Lunes', 'sabado', 'DOMINGO', 'feriado'] as $dia) {
    $resultados = [horarioIf($dia), horarioSwitch($dia), horarioMatch($dia), horarioArray($dia)];
    $iguales = count(array_unique($resultados)) === 1 ? 'las 4 coinciden' : 'NO coinciden';
    echo "$dia → {$resultados[0]} ($iguales)<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  COMPARACIÓN
 * ════════════════════════════════════════════════════════════════════════════
 *
 *                     | A if/elseif     | B switch          | C match         | D array
 *  -------------------+-----------------+-------------------+-----------------+-----------------
 *  Compara con        | lo que escribas | == (convierte)    | === (estricto)  | clave exacta
 *  Varios valores     | || repetidos    | case apilados     | separados por , | una fila por valor
 *  Devuelve un valor  | con return      | con return        | sí, directo     | sí, directo
 *  Si no coincide     | else            | default           | default (o      | ?? valor
 *                     |                 |                   | lanza un error) |
 *  Sirve para rangos  | SÍ              | incómodo          | con match(true) | no
 *  (nota >= 4.0...)   |                 |                   |                 |
 *  Los datos pueden   | no              | no                | no              | SÍ (de la BD,
 *  venir de afuera    |                 |                   |                 | de un archivo)
 *
 *  ¿Cuál usar?
 *  - Valor exacto → resultado (día, estrato, código): C (match) o D (array).
 *    D es mejor cuando la lista puede crecer o venir de la base de datos.
 *  - Rangos o condiciones combinadas (edad >= 18 && tiene cédula): A (if/elseif).
 *  - B (switch) aparece mucho en código antiguo; en código nuevo, match es más
 *    corto y más seguro (compara con === y no necesita break).
 */

echo "<hr>";

// ── El segundo problema: rangos ───────────────────────────────────────────────
// Desempeño según la nota (0.0 a 5.0). Aquí el array y el switch no sirven bien:
// no se puede tener una clave por cada nota posible (3.0, 3.01, 3.02...).

function desempenoIf(float $nota): string
{
    if ($nota >= 4.5) {
        return 'Superior';
    } elseif ($nota >= 4.0) {
        return 'Alto';
    } elseif ($nota >= 3.0) {
        return 'Básico';
    }
    return 'Bajo';
}

// match(true): compara true === cada condición, y gana la primera verdadera
function desempenoMatch(float $nota): string
{
    return match (true) {
        $nota >= 4.5 => 'Superior',
        $nota >= 4.0 => 'Alto',
        $nota >= 3.0 => 'Básico',
        default      => 'Bajo',
    };
}

foreach ([4.8, 4.0, 3.2, 2.9] as $nota) {
    echo "Nota $nota → " . desempenoIf($nota) . " / " . desempenoMatch($nota) . "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python lo común para "valor → resultado" es un diccionario:
 *  horarios.get(dia, "Día no válido"). En PHP la versión D es exactamente eso:
 *  $horarios[$dia] ?? 'Día no válido'. Pero OJO con las claves numéricas en
 *  PHP: un array con claves "1", "2"... las convierte en números 1, 2 (lo verás
 *  en 05-arrays/resueltos/02).
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Llama a las cuatro funciones con "miércoles" (con tilde). ¿Qué devuelven?
 *     ¿Cómo lo arreglarías en un solo lugar para las cuatro?
 *  2. Si mañana los viernes cierran a las 17:00, ¿cuántas líneas cambias en
 *     cada versión?
 *  3. En desempenoIf(), ¿qué pasa si inviertes el orden de las condiciones
 *     (primero >= 3.0)? ¿Pasa lo mismo en desempenoMatch()?
 *  4. ¿Por qué en el switch de la versión B no hace falta escribir break?
 */
