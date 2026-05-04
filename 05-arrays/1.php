<?php
// En PHP existen tres tipos principales de arrays

// 1. Array Indexado - índices numéricos automáticos
$colores = ["Rojo", "Verde", "Azul"];
echo $colores[0] . "<br>"; // Rojo
echo $colores[1] . "<br>"; // Verde
echo "<hr>";

// 2. Array Asociativo - índices personalizados (clave => valor)
$persona = [
    "nombre" => "Ana",
    "edad"   => 25,
    "ciudad" => "Bogotá"
];
echo $persona["nombre"] . "<br>"; // Ana
echo $persona["ciudad"] . "<br>"; // Bogotá
echo "<hr>";

// 3. Array Multidimensional - arrays dentro de arrays
$estudiantes = [
    ["nombre" => "Carlos", "nota" => 85],
    ["nombre" => "María",  "nota" => 92],
    ["nombre" => "Juan",   "nota" => 78],
];
foreach ($estudiantes as $est) {
    echo $est["nombre"] . " - Nota: " . $est["nota"] . "<br>";
}
