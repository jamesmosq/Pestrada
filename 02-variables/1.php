<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Variables</title>
</head>
<body>
<!--Para declarar una variable debe tener la siguiente estructura.-->
<?php
$edad = 31;
//$edad es el nombre de la variable. Puede empezar por cualquier carácter salvo un número u operadores aritmeticos
//para evitar errores y confusiones el lenguaje de programacion recomienda usar la sintaxis que se declara al inicio
//esto de acuerdo a las buenas practicas.

$piso = 31; // Válido
//$3escalera = 2; // Inválido

//El valor pueden ser varios tipos de variables.
$nombre = 'Manolo'; // Texto. Puede ser con comillas simples o dobles (String)
$edad = 31; // Enteros (Integer)
$altura = 1.72; // Decimales, usando el punto en lugar de la coma (Float)
$mayorEdad = True; // Verdad o mentira (Boolean)

?>
</body>
</html>