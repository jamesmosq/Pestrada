<?php

$saldoInicial = 1000;
$retiro = 100;

echo "Bienvenido al cajero automático". "<br>";
echo "Saldo actual: $" . $saldoInicial . "<br>";

while ($retiro <= $saldoInicial) {
    $saldoInicial -= $retiro;
    echo "Has retirado $" . $retiro . "<br>";
    echo "Saldo actual: $" . $saldoInicial . "<br>";

    // Se puede agregar una pausa para simular el tiempo entre retiros
    // sleep(1); // Descomentar si quieres agregar una pausa de 1 segundo

    // Aquí podrías agregar una condición para simular una nueva cantidad de retiro
    // Por ejemplo, podrías pedir al usuario que ingrese una nueva cantidad
    // o cambiar la cantidad de retiro en cada iteración de alguna forma.
    // Para este ejemplo, mantendremos la cantidad de retiro constante.
}

echo "Fondos insuficientes para continuar retirando";
?>
