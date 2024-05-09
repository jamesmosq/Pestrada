<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operadores de Arrays en PHP</title>
    <link rel="stylesheet" type="text/css" href="/style.css">
</head>
<body>

<h2>Operadores de Arrays en PHP</h2>

<table>
    <tr>
        <th>Operador</th>
        <th>Nombre</th>
        <th>Ejemplo</th>
        <th>Resultado</th>
        <th>Probar</th>
    </tr>
    <tr>
        <td>+</td>
        <td>Unión</td>
        <td>$x + $y</td>
        <td>Unión de $x y $y</td>
        <td><?php $x = array("a" => "rojo", "b" => "verde"); $y = array("c" => "azul", "b" => "amarillo"); $resultado = $x + $y; print_r($resultado);?></td>
    </tr>
    <tr>
        <td>==</td>
        <td>Igualdad</td>
        <td>$x == $y</td>
        <td>Devuelve true si $x y $y tienen los mismos pares clave/valor</td>
        <td><?php $x = array("a" => "rojo", "b" => "verde"); $y = array("b" => "verde", "a" => "rojo"); echo ($x == $y) ? 'true' : 'false';?></td>
    </tr>
    <tr>
        <td>===</td>
        <td>Identidad</td>
        <td>$x === $y</td>
        <td>Devuelve true si $x y $y tienen los mismos pares clave/valor en el mismo orden y del mismo tipo de dato</td>
        <td><?php $x = array("a" => "rojo", "b" => "verde"); $y = array("a" => "rojo", "b" => "verde"); echo ($x === $y) ? 'true' : 'false';?></td>
    </tr>
    <tr>
        <td>!=</td>
        <td>Diferencia</td>
        <td>$x != $y</td>
        <td>Devuelve true si $x no es igual a $y</td>
        <td><?php $x = array("a" => "rojo", "b" => "verde"); $y = array("c" => "azul", "b" => "verde"); echo ($x != $y) ? 'true' : 'false';?></td>
    </tr>
    <tr>
        <td>&lt;&gt;</td>
        <td>Diferencia</td>
        <td>$x &lt;&gt; $y</td>
        <td>Devuelve true si $x no es igual a $y</td>
        <td><?php $x = array("a" => "rojo", "b" => "verde"); $y = array("c" => "azul", "b" => "verde"); echo ($x <> $y) ? 'true' : 'false';?></td>
    </tr>
    <tr>
        <td>!==</td>
        <td>No identidad</td>
        <td>$x !== $y</td>
        <td>Devuelve true si $x no es idéntico a $y</td>
        <td><?php $x = array("a" => "rojo", "b" => "verde"); $y = array("c" => "azul", "b" => "verde"); echo ($x !== $y) ? 'true' : 'false';?></td>
    </tr>
</table>

</body>
</html>
