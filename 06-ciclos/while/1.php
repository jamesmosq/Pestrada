<?php
// NOTA: Este ejemplo usa readline() que solo funciona por consola (CLI).
// Para ejecutar: php 1.php   (desde la terminal, NO desde el navegador)

// En el navegador readline() no puede leer el teclado: el while nunca terminaria
// y bloquearia el servidor. Por eso, si no estamos en consola, avisamos y salimos.
if (PHP_SAPI !== 'cli') {
    echo "Este ejemplo se ejecuta en la consola.<br>";
    echo "Abre la terminal en esta carpeta y escribe: <code>php 1.php</code>";
    exit;
}

$contraseña_correcta = "1234";
$contraseña = "2";

while ($contraseña !== $contraseña_correcta) {
    $contraseña = readline("Introduce la contraseña: ");
}

echo "¡Contraseña correcta!"; //corregir el algoritmo y mostrar cuando la contraseña sea incorrecta

