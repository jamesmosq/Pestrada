<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Constantes</title>
</head>
<!--En ocasiones es necesario indicar una variable va a ser inmutable.
Ello ocurre porque hay cosas que nunca cambian ni quieres que cambien:
número PI, velocidad de la luz, fuerza de la gravedad, días de la semana…-->
<!--Para crearla usaremos la funcion define().-->

<!--Para crearla usaremos la funcion define().
por sugerencia del lenguaje de programacion se recomienda que las constantes se escriban en MAYUSCULA SOSTENIDA
https://www.php.net/manual/es/language.constants.syntax.php
-->
<body>
<?php
define('GRAVEDAD', 9.8);

//En cambio, para utilizarla solo usaremos su nombre sin necesidad de usar el prefijo $.
print GRAVEDAD."<br>";

//Otra sintaxis que nos ofrece PHP es usando la palabra const, como en JavaScript.
define('PI', 3.14);
const PESO = 40;
$result = PESO * 3;
print "Procesando: ".$result."<br>";

print PESO;
?>
</body>
</html>