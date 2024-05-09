<?php
// Array de productos, cada producto es un array asociativo con 'nombre' y 'precio'
$productos = [
    [
        "nombre" => "Laptop",
        "precio" => 1200
    ],
    [
        "nombre" => "Teléfono",
        "precio" => 800
    ],
    [
        "nombre" => "Tablet",
        "precio" => 500
    ],
    [
        "nombre" => "Monitor",
        "precio" => 300
    ]
];

// Iterar sobre el array de productos
echo "Listado de productos:\n";
foreach ($productos as $producto) {
    echo "Nombre: " . $producto['nombre'] . ", Precio: $" . $producto['precio'] . "<br>";
}
?>
