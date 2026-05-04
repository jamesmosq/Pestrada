<?php
// Sintaxis básica del bucle for:
// for (inicialización; condición; incremento) { ... }

// Ejemplo 1: Contar del 1 al 10
for ($i = 1; $i <= 10; $i++) {
    echo "Número: $i <br>";
}
echo "<hr>";

// Ejemplo 2: Contar hacia atrás del 5 al 1
for ($i = 5; $i >= 1; $i--) {
    echo "Cuenta regresiva: $i <br>";
}
echo "<hr>";

// Ejemplo 3: Tabla de multiplicar del 3
$numero = 3;
for ($i = 1; $i <= 10; $i++) {
    echo "$numero x $i = " . ($numero * $i) . "<br>";
}
