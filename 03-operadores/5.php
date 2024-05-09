<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operadores Lógicos</title>
    <link rel="stylesheet" type="text/css" href="/style.css">
</head>
<body>

<h2>Operadores Lógicos</h2>

<table>
    <tr>
        <th>Operador</th>
        <th>Nombre</th>
        <th>Ejemplo</th>
        <th>Resultado</th>
        <th>Probar</th>
    </tr>
    <tr>
        <td>and</td>
        <td>And</td>
        <td>$x and $y</td>
        <td>Verdadero si tanto $x como $y son verdaderos</td>
        <td><?php $x = true; $y = true; echo ($x and $y) ? 'true' : 'false';?></td>
    </tr>
    <tr>
        <td>or</td>
        <td>Or</td>
        <td>$x or $y</td>
        <td>Verdadero si $x o $y es verdadero</td>
        <td><?php $x = true; $y = false; echo ($x or $y) ? 'true' : 'false';?></td>
    </tr>
    <tr>
        <td>xor</td>
        <td>Xor</td>
        <td>$x xor $y</td>
        <td>Verdadero si $x o $y es verdadero, pero no ambos</td>
        <td><?php $x = true; $y = false; echo ($x xor $y) ? 'true' : 'false';?></td>
    </tr>
    <tr>
        <td>&&</td>
        <td>And</td>
        <td>$x && $y</td>
        <td>Verdadero si tanto $x como $y son verdaderos</td>
        <td><?php $x = true; $y = true; echo ($x && $y) ? 'true' : 'false';?></td>
    </tr>
    <tr>
        <td>||</td>
        <td>Or</td>
        <td>$x || $y</td>
        <td>Verdadero si $x o $y es verdadero</td>
        <td><?php $x = true; $y = false; echo ($x || $y) ? 'true' : 'false';?></td>
    </tr>
    <tr>
        <td>!</td>
        <td>Not</td>
        <td>!$x</td>
        <td>Verdadero si $x no es verdadero</td>
        <td><?php $x = false; echo (!$x) ? 'true' : 'false';?></td>
    </tr>
</table>

</body>
</html>
