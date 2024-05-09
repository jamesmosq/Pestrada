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

</body>
</html>