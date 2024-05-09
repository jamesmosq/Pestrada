<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operadores de Incremento/Decremento</title>
    <link rel="stylesheet" type="text/css" href="/style.css">
</head>
<body>

<h2>Operadores de Incremento/Decremento</h2>

<table>
    <tr>
        <th>Operador</th>
        <th>Mismo que...</th>
        <th>Descripción</th>
        <th>Probar</th>
    </tr>
    <tr>
        <td>++$x</td>
        <td>Pre-incremento</td>
        <td>Incrementa $x en uno, luego devuelve $x</td>
        <td><?php $x=5; echo ++$x;?></td>
    </tr>
    <tr>
        <td>$x++</td>
        <td>Post-incremento</td>
        <td>Devuelve $x, luego incrementa $x en uno</td>
        <td><?php $x=5; echo $x++;?></td>
    </tr>
    <tr>
        <td>--$x</td>
        <td>Pre-decremento</td>
        <td>Decrementa $x en uno, luego devuelve $x</td>
        <td><?php $x=5; echo --$x;?></td>
    </tr>
    <tr>
        <td>$x--</td>
        <td>Post-decremento</td>
        <td>Devuelve $x, luego decrementa $x en uno</td>
        <td><?php $x=5; echo $x--;?></td>
    </tr>
</table>

</body>
</html>
