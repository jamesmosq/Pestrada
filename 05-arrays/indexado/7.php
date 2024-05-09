<?php
//Funciones Útiles para Arrays
//array_push: Añade uno o más elementos al final de un array.

$frutas = array("Manzana", "Banana", "Cereza");
array_push($frutas, "Naranja", "Pera");
print_r($frutas);
// Salida: Array ( [0] => Manzana [1] => Banana [2] => Cereza [3] => Naranja [4] => Pera )


//array_pop: Elimina y devuelve el último elemento del array.

$frutas = array("Manzana", "Banana", "Cereza");
$ultimaFruta = array_pop($frutas);
echo $ultimaFruta; // Salida: Cereza
print_r($frutas);
// Salida: Array ( [0] => Manzana [1] => Banana )

//array_shift: Elimina y devuelve el primer elemento del array.

$frutas = array("Manzana", "Banana", "Cereza");
$primeraFruta = array_shift($frutas);
echo $primeraFruta; // Salida: Manzana
print_r($frutas);
// Salida: Array ( [0] => Banana [1] => Cereza )

//array_unshift: Añade uno o más elementos al inicio del array.

$frutas = array("Manzana", "Banana", "Cereza");
array_unshift($frutas, "Kiwi", "Fresa");
print_r($frutas);
// Salida: Array ( [0] => Kiwi [1] => Fresa [2] => Manzana [3] => Banana [4] => Cereza )


