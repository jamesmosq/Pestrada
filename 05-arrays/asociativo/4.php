<?php
//Añadir Nuevos Elementos al Array
$persona = array(
    "nombre" => "Juan",
    "edad" => 30,
    "ciudad" => "Madrid"
);

// Añadir un nuevo elemento al array
$persona["pais"] = "España";
$persona["Direccion"] = "Calle 34#233";
print_r($persona);
// Salida: Array ( [nombre] => Juan [edad] => 31 [ciudad] => Madrid [pais] => España )

?>