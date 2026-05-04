<?php
// ── Valores por defecto y type hints ─────────────────────────────────────────

// Parámetros con valor por defecto — deben ir SIEMPRE al final
function saludar(string $nombre, string $saludo = "Hola"): string {
    return "$saludo, $nombre!";
}

echo saludar("Ana")               . "<br>"; // Hola, Ana!
echo saludar("Ana", "Buenos días"). "<br>"; // Buenos días, Ana!
echo saludar("Ana", "Buenas noches"). "<br>";

echo "<hr>";

// Type hints — indicar el tipo esperado en parámetros y retorno
function sumar(int $a, int $b): int {
    return $a + $b;
}

function promedio(float $a, float $b): float {
    return ($a + $b) / 2;
}

function esMayor(int $edad): bool {
    return $edad >= 18;
}

echo sumar(5, 3)        . "<br>"; // 8
echo promedio(7.5, 8.5) . "<br>"; // 8
echo esMayor(20) ? "Mayor<br>" : "Menor<br>";

echo "<hr>";

// Tipo nullable — ? antes del tipo permite NULL como valor válido
function buscarUsuario(int $id): ?string {
    $usuarios = [1 => "Ana", 2 => "Luis", 3 => "Pedro"];
    return $usuarios[$id] ?? null; // retorna null si no existe
}

echo buscarUsuario(2)  ?? "Usuario no encontrado"; echo "<br>";
echo buscarUsuario(99) ?? "Usuario no encontrado"; echo "<br>";

echo "<hr>";

// Número variable de argumentos (variadic) — recibe "todos los que vengan"
function sumarTodos(int ...$numeros): int {
    return array_sum($numeros);
}

echo sumarTodos(1, 2, 3)          . "<br>"; // 6
echo sumarTodos(10, 20, 30, 40)   . "<br>"; // 100
echo sumarTodos(5)                . "<br>"; // 5
