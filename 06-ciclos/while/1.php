<?php
// NOTA: Este ejemplo usa readline() que solo funciona por consola (CLI).
// Para ejecutar: php 1.php   (desde la terminal, NO desde el navegador)

$contraseña_correcta = "1234";
$contraseña = "2";

while ($contraseña !== $contraseña_correcta) {
    $contraseña = readline("Introduce la contraseña: ");
}

echo "¡Contraseña correcta!"; //corregir el algoritmo y mostrar cuando la contraseña sea incorrecta

