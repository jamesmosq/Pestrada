<?php
//Eliminación de Elementos del Array
$persona = array(
    "nombre" => "Juan",
    "edad" => 30,
    "ciudad" => "Madrid"
);

// Eliminar un elemento del array
unset($persona["ciudad"]);
print_r($persona);
// Salida: Array ( [nombre] => Juan [edad] => 30 )


?>