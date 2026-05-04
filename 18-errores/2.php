<?php
// ── Excepciones personalizadas ───────────────────────────────────────────────

// Extender Exception nos permite crear errores con semántica propia

class EdadInvalidaException extends Exception {
    public function __construct(int $edad) {
        parent::__construct("La edad '$edad' no es válida. Debe estar entre 0 y 120.");
        // parent::__construct recibe: mensaje, código, excepción anterior
    }
}

class SaldoInsuficienteException extends Exception {
    public function __construct(float $saldo, float $monto) {
        parent::__construct(
            "Saldo insuficiente. Disponible: $$saldo — Solicitado: $$monto"
        );
    }
}

// ── Funciones que lanzan las excepciones ────────────────────────────────────

function registrarEdad(int $edad): string {
    if ($edad < 0 || $edad > 120) {
        throw new EdadInvalidaException($edad);
    }
    return "Edad registrada: $edad";
}

function retirar(float $saldo, float $monto): float {
    if ($monto > $saldo) {
        throw new SaldoInsuficienteException($saldo, $monto);
    }
    return $saldo - $monto;
}

// ── Uso ──────────────────────────────────────────────────────────────────────

// Caso exitoso
try {
    echo registrarEdad(25) . "<br>";
} catch (EdadInvalidaException $e) {
    echo $e->getMessage() . "<br>";
}

// Caso con error
try {
    echo registrarEdad(200) . "<br>";
} catch (EdadInvalidaException $e) {
    echo "Excepción: " . $e->getMessage() . "<br>";
}

echo "<hr>";

try {
    $nuevo = retirar(500, 200);
    echo "Nuevo saldo: $$nuevo<br>";

    $nuevo = retirar($nuevo, 400); // esto falla
    echo "Nuevo saldo: $$nuevo<br>";
} catch (SaldoInsuficienteException $e) {
    echo "Excepción: " . $e->getMessage() . "<br>";
}
