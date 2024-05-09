<?php
// Creación de un array asociativo
$persona = array(
    "nombre" => "Juan",
    "edad" => 30,
    "ciudad" => "Madrid"
);

// Otra forma de crear un array asociativo
$producto = [
    "nombre" => "Silla",
    "precio" => 1200,
    "marca" => "Dell"
];

echo "La ".$producto["nombre"] . " es tiene un precio de: ".$producto['precio'];
echo "<br>";


$servicio = array("Energia"=>"EPM","Internet"=>"Tigo","VOD"=>"Netflix");
echo $servicio["Internet"]



//print_r($servicio);


//print_r($persona)."<br>";

//print_r($producto)."<br>";

?>

