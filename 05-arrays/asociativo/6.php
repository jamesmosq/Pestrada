<?php
//Uso de Funciones Útiles para Arrays Asociativos
//array_keys: Devuelve todas las claves de un array.
$persona = array(
    "nombre" => "Juan",
    "edad" => 30,
    "ciudad" => "Madrid"
);

$claves = array_keys($persona);
print_r($claves);
// Salida: Array ( [0] => nombre [1] => edad [2] => ciudad )


//array_values: Devuelve todos los valores de un array.
$persona = array(
    "nombre" => "Juan",
    "edad" => 30,
    "ciudad" => "Madrid"
);

$valores = array_values($persona);
print_r($valores);
// Salida: Array ( [0] => Juan [1] => 30 [2] => Madrid )


//array_key_exists: Comprueba si una clave existe en un array.
$persona = array(
    "nombre" => "Juan",
    "edad" => 30,
    "ciudad" => "Madrid"
);

if (array_key_exists("edad", $persona)) {
    echo "La clave 'edad' existe en el array.";
} else {
    echo "La clave 'edad' no existe en el array.";
}
// Salida: La clave 'edad' existe en el array.





?>