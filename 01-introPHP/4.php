<html>
<head></head>
<body>
<h1>Concatenar</h1>
<?php
echo 'John Lennon' . ' y ' . 'Paul McCartney';
//Debes utilizar un punto entre ambos textos. Los espacios son opcionales, pero te ayudará a visualizar con más facilidad


//No es lo mismo usar comillas simples o dobles.
$texto1 = 'Atapuerca';
$texto2 = "Museo de la Evolucion";

//Pero cuando se utilizan las comillas dobles se le indica que dentro puede existir una variable.

$emisora = 'La Ser';
echo "Me gusta escuchar $emisora";
// Me gusta escuchar La Ser

//Si hubiera utilizado comillas simples lo hubiera interpretado tal cual estaba escrito.
$emisora = 'La Ser';
echo 'Me gusta escuchar $emisora';
// Me gusta escuchar $emisora


//Esta técnica es una forma sencilla de concadenar variables sin usar un punto
$mes = 'Julio';
$dia = '11';
echo "Mi cumpleaños es el $dia de $mes";
// Mi cumpleaños es el 11 de Julio



?>

</body>
</html>
