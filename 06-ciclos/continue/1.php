<?php
$i = 1;

while ($i <= 10) {
    $i++;
    if ($i <= 5) {
        continue; // Si el número es menor o igual a 5, omite el resto de la iteración
    }
    echo $i . "\n"; // Solo se ejecuta para números mayores a 5
}
?>
