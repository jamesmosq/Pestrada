<?php
// ── try / catch / finally ────────────────────────────────────────────────────

// SIN manejo de errores: un error detiene TODO el script.
// CON try/catch podemos capturarlo y seguir ejecutando.

// Ejemplo 1: capturar una excepción manual
function dividir(float $a, float $b): float {
    if ($b === 0.0) {
        throw new InvalidArgumentException("No se puede dividir entre cero.");
    }
    return $a / $b;
}

try {
    echo dividir(10, 2) . "<br>"; // 5
    echo dividir(8, 0) . "<br>";  // lanza excepción — la línea siguiente no se ejecuta
    echo "Esta línea NO se imprime.<br>";
} catch (InvalidArgumentException $e) {
    echo "Error capturado: " . $e->getMessage() . "<br>";
}

echo "El script CONTINÚA después del try/catch.<br>";

echo "<hr>";

// Ejemplo 2: finally — se ejecuta SIEMPRE (con o sin error)
function conectarBD(bool $forzarError = false): string {
    if ($forzarError) {
        throw new RuntimeException("No se pudo conectar a la base de datos.");
    }
    return "Conexión exitosa";
}

foreach ([false, true] as $error) {
    try {
        $resultado = conectarBD($error);
        echo $resultado . "<br>";
    } catch (RuntimeException $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    } finally {
        echo "→ Bloque finally ejecutado (cierre de recursos aquí)<br>";
    }
}
