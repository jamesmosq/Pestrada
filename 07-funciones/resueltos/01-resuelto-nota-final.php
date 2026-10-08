<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 01 — Nota final ponderada
 *  Tipo: resuelto y comentado
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  Un aprendiz tiene tres notas (de 0.0 a 5.0) y cada una pesa distinto:
 *  taller 30 %, quiz 30 %, proyecto 40 %. Calcular la nota final y decir si
 *  aprobó (nota >= 3.0). Si los datos no son válidos, avisar en lugar de
 *  calcular algo sin sentido.
 *
 *  CÓMO SE PENSÓ (antes de escribir código)
 *  1. ¿Qué entra?  -> un array de notas y un array de pesos.
 *  2. ¿Qué sale?   -> un número (la nota) y un texto (aprobó / reprobó).
 *     Son dos responsabilidades distintas -> DOS funciones pequeñas,
 *     no una grande que haga todo.
 *  3. ¿Qué puede salir mal?
 *     - que lleguen más notas que pesos (o al revés)
 *     - que una nota esté fuera de 0..5
 *     - que los pesos no sumen 1 (100 %)
 *  4. Fórmula: final = nota1*peso1 + nota2*peso2 + nota3*peso3
 *     -> un ciclo que acumula, sirve para 3 notas o para 10.
 */

// ── Función 1: calcula ────────────────────────────────────────────────────────
// Tipos declarados (array, float): si alguien manda un texto, PHP avisa de
// inmediato en vez de dar un resultado raro más adelante.
function calcularNotaFinal(array $notas, array $pesos): float
{
    // Validar ANTES de calcular: "fallar rápido".
    if (count($notas) !== count($pesos)) {
        throw new InvalidArgumentException("Hay " . count($notas) . " notas y " . count($pesos) . " pesos.");
    }

    // Los decimales no son exactos en la computadora: 0.3 + 0.3 + 0.4 puede dar
    // 0.99999999. Por eso NO se compara con === 1.0, sino "casi igual a 1".
    if (abs(array_sum($pesos) - 1) > 0.001) {
        throw new InvalidArgumentException("Los pesos deben sumar 100 %.");
    }

    $final = 0;
    foreach ($notas as $i => $nota) {
        if ($nota < 0 || $nota > 5) {
            throw new InvalidArgumentException("La nota $nota está fuera del rango 0 a 5.");
        }
        $final += $nota * $pesos[$i];   // acumulador
    }

    return round($final, 1);            // una sola cifra decimal, como en el boletín
}

// ── Función 2: interpreta ─────────────────────────────────────────────────────
// Separada de la anterior: si mañana el mínimo para aprobar cambia a 3.5,
// solo se toca esta función.
function estado(float $nota, float $minimo = 3.0): string
{
    return $nota >= $minimo ? "APROBÓ" : "NO APROBÓ";
}

// ── Uso ───────────────────────────────────────────────────────────────────────
$pesos = [0.3, 0.3, 0.4];

$aprendices = [
    'Ana'   => [4.0, 3.5, 4.5],
    'Luis'  => [2.0, 3.0, 2.5],
    'Marta' => [3.0, 3.0],            // le falta una nota
    'Pedro' => [5.0, 4.0, 7.0],       // nota fuera de rango
];

foreach ($aprendices as $nombre => $notas) {
    try {
        $final = calcularNotaFinal($notas, $pesos);
        echo "$nombre: $final — " . estado($final) . "<br>";
    } catch (InvalidArgumentException $e) {
        echo "$nombre: no se pudo calcular. " . $e->getMessage() . "<br>";
    }
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAZA — calcularNotaFinal([4.0, 3.5, 4.5], [0.3, 0.3, 0.4])
 * ════════════════════════════════════════════════════════════════════════════
 *
 *   vuelta | $i | $nota | $pesos[$i] | $nota * peso | $final después
 *   -------+----+-------+------------+--------------+---------------
 *   inicio |    |       |            |              | 0
 *      1   | 0  |  4.0  |    0.3     |     1.20     | 1.20
 *      2   | 1  |  3.5  |    0.3     |     1.05     | 2.25
 *      3   | 2  |  4.5  |    0.4     |     1.80     | 4.05
 *   return round(4.05, 1) -> 4.1
 *
 *  Hazla tú para Luis antes de ejecutar: ¿te da 2.5?
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, si una función recibe una LISTA y la modifica, la lista original
 *  cambia (se comparte). En PHP los ARRAYS se pasan COPIADOS: la función trabaja
 *  sobre su propia copia y el original queda intacto.
 *
 *  Míralo funcionando abajo: subirNotas() suma 0.5 a cada nota, pero $notasAna
 *  no cambia. Para modificar el original hay que pedirlo con & (por referencia)
 *  o, mejor, DEVOLVER el array nuevo con return.
 */

echo "<hr>";

function subirNotas(array $notas): array
{
    foreach ($notas as $i => $nota) {
        $notas[$i] = min(5, $nota + 0.5);
    }
    return $notas;
}

$notasAna = [4.0, 3.5, 4.5];
subirNotas($notasAna);                       // se perdió el resultado
echo "Sin usar el return: " . implode(', ', $notasAna) . "<br>";

$notasAna = subirNotas($notasAna);           // así sí
echo "Usando el return:   " . implode(', ', $notasAna) . "<br>";

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR (preguntas de sustentación)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Por qué hay dos funciones y no una sola que imprima "Ana: 4.1 — APROBÓ"?
 *  2. ¿Qué pasaría si se compara array_sum($pesos) === 1.0? Pruébalo con
 *     var_dump(0.1 + 0.2 === 0.3);
 *  3. ¿Por qué el try/catch está DENTRO del foreach y no afuera?
 *  4. ¿Qué ventaja tiene lanzar una excepción en vez de devolver -1 cuando
 *     los datos están mal?
 *
 *  PARA MODIFICAR
 *  - Agrega una cuarta nota "autoevaluación" con peso 10 % (ajusta los demás pesos).
 *  - Haz que estado() devuelva también "EN RIESGO" si la nota está entre 3.0 y 3.4.
 */
