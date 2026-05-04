<?php
// ── Múltiples catch y jerarquía de excepciones ───────────────────────────────

// PHP tiene una jerarquía de excepciones:
// Throwable
//   ├── Error (errores del motor PHP)
//   │     ├── TypeError
//   │     ├── DivisionByZeroError
//   │     └── ParseError
//   └── Exception (errores de lógica de aplicación)
//         ├── InvalidArgumentException
//         ├── RuntimeException
//         ├── LengthException
//         └── OutOfRangeException ... (y muchas más)

function procesarDato(mixed $dato): string {
    if (!is_string($dato)) {
        throw new TypeError("Se esperaba un string, se recibió: " . gettype($dato));
    }
    if (empty($dato)) {
        throw new InvalidArgumentException("El dato no puede estar vacío.");
    }
    if (strlen($dato) > 20) {
        throw new LengthException("El dato no puede tener más de 20 caracteres.");
    }
    return strtoupper($dato);
}

// Varios valores de prueba para demostrar cada excepción
$pruebas = [
    "hola",           // OK
    "",               // InvalidArgumentException
    12345,            // TypeError
    str_repeat("x", 25), // LengthException
    "php es genial",  // OK
];

foreach ($pruebas as $dato) {
    try {
        $resultado = procesarDato($dato);
        echo "✓ Resultado: $resultado<br>";
    } catch (TypeError $e) {
        echo "✗ Tipo incorrecto: " . $e->getMessage() . "<br>";
    } catch (LengthException $e) {
        echo "✗ Demasiado largo: " . $e->getMessage() . "<br>";
    } catch (InvalidArgumentException $e) {
        echo "✗ Argumento inválido: " . $e->getMessage() . "<br>";
    } catch (Exception $e) {
        // catch genérico — captura cualquier Exception no capturada arriba
        echo "✗ Error inesperado: " . $e->getMessage() . "<br>";
    }
}
