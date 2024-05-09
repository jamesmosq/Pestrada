<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visibilidad (Ámbito de variables)</title>
</head>
<body>
<!--Una variable por defecto tiene un alcance local. No puede usarse en otro script (o página).
Si quieres que sea accesible por cualquier documento, debes usar la palabra reservaba global.-->
<?php
$localizacion = 'Valencia'; // Local
$propietario = "Cirque du Soleil";
global $propietario; // Global
//en los archivos a.php y b.php podemos ver el uso de las variables globales
?>

</body>
</html>
