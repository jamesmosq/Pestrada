<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Condicionales</title>
</head>
<body>
    <h1>condicionales</h1>
<p>En PHP, los condicionales se utilizan para tomar decisiones basadas en condiciones específicas. Los condicionales más comunes en PHP son if, else, elseif y switch.</p>
    <h2>1. if</h2>
    <p>El condicional if se utiliza para ejecutar un bloque de código si una condición es verdadera.</p>
    <?php
    $edad = 18;

    if ($edad >= 18) {
        echo "Eres mayor de edad.";
    }
    ?>

    <h2>2. else</h2>
    <p>El condicional else se utiliza para ejecutar un bloque de código si la condición en el if es falsa.</p>
    <?php
    $edad = 15;

    if ($edad >= 18) {
        echo "Eres mayor de edad.";
    } else {
        echo "Eres menor de edad.";
    }
    ?>

<h2>3. elseif</h2>
<p>El condicional elseif se utiliza para ejecutar un bloque de código si la condición en el if anterior es falsa, pero otra condición es verdadera.</p>
    <?php
    $hora = 20;

    if ($hora < 12) {
        echo "Buenos días.";
    } elseif ($hora < 18) {
        echo "Buenas tardes.";
    } else {
        echo "Buenas noches.";
    }
    ?>

<h2>4. switch</h2>
<p>El condicional switch se utiliza para seleccionar uno de varios bloques de código para ejecutar.</p>
    <?php
    $día = "Lunes";

    switch ($día) {
        case "Lunes":
            echo "Hoy es Lunes.";
            break;
        case "Martes":
            echo "Hoy es Martes.";
            break;
        case "Miércoles":
            echo "Hoy es Miércoles.";
            break;
        case "Jueves":
            echo "Hoy es Jueves.";
            break;
        case "Viernes":
            echo "Hoy es Viernes.";
            break;
        default:
            echo "Es fin de semana.";
    }
    ?>

</body>
</html>