<?php
// ── Funciones anónimas y arrow functions ─────────────────────────────────────

// Función anónima (closure) — función sin nombre, guardada en variable
$cuadrado = function(int $n): int {
    return $n * $n;
};

echo $cuadrado(4) . "<br>"; // 16
echo $cuadrado(7) . "<br>"; // 49

echo "<hr>";

// Usar funciones anónimas como argumento — muy común con arrays
$numeros = [3, 1, 4, 1, 5, 9, 2, 6];

// array_map — aplica una función a cada elemento
$cuadrados = array_map(function($n) { return $n * $n; }, $numeros);
echo implode(", ", $cuadrados) . "<br>"; // 9, 1, 16, 1, 25, 81, 4, 36

// array_filter — filtra elementos que pasen la condición
$pares = array_filter($numeros, function($n) { return $n % 2 === 0; });
echo implode(", ", $pares) . "<br>"; // 4, 2, 6

// usort — ordena con función personalizada
$palabras = ["banana", "manzana", "uva", "pera"];
usort($palabras, function($a, $b) { return strlen($a) - strlen($b); }); // por longitud
echo implode(", ", $palabras) . "<br>"; // uva, pera, banana, manzana

echo "<hr>";

// Arrow function (fn) — PHP 7.4+ — sintaxis corta para closures de una línea
$doble = fn($n) => $n * 2;
echo $doble(5) . "<br>"; // 10

// La ventaja: accede automáticamente a variables del contexto externo
$factor = 3;
$multiplicar = fn($n) => $n * $factor; // usa $factor sin necesidad de 'use'
echo $multiplicar(7) . "<br>"; // 21

// Las funciones anónimas normales necesitan 'use' para acceder al contexto:
$multiplicarClosure = function($n) use ($factor) { return $n * $factor; };
echo $multiplicarClosure(7) . "<br>"; // 21

echo "<hr>";

// Ejemplo práctico: pipeline de transformaciones
$precios = [100, 250, 75, 300, 50];

$resultado = array_filter(
    array_map(fn($p) => $p * 1.19, $precios), // aplicar IVA del 19%
    fn($p) => $p > 100                         // solo los que superan $100
);

echo "Precios con IVA mayores a $100:<br>";
foreach ($resultado as $precio) {
    echo "$ " . number_format($precio, 2) . "<br>";
}
