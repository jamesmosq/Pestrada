<?php
//Recorrer un Array con un Bucle foreach
$persona = array(
    "nombre" => "Juan",
    "edad" => 30,
    "ciudad" => "Madrid"
);

foreach ($persona as $clave => $valor) {
    echo "$clave: $valor\n";
    echo "$clave: $valor\n";
}
// Salida:
// nombre: Juan
// edad: 30
// ciudad: Madrid


?>