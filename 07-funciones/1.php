<?php
function imprimirNumeros($limite) {
    for ($i = 1; $i <= $limite; $i++) {
        echo $i . " ";
    }
}

// Prueba de la función
$limite = 10;
echo "Lista de números del 1 al $limite: ";
imprimirNumeros($limite);
?>
