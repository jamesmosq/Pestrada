<?php

$contraseña_correcta = "1234";
$contraseña = "2";

while ($contraseña !== $contraseña_correcta) {
    $contraseña = readline("Introduce la contraseña: ");
}

echo "¡Contraseña correcta!"; //corregir el algoritmo y mostrar cuando la contraseña sea incorrecta

