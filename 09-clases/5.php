<?php
class Calculadora {
    public static function sumar($a, $b) {
        return $a + $b;
    }

    public static function restar($a, $b) {
        return $a - $b;
    }
}

// Usar métodos estáticos sin crear un objeto
echo Calculadora::sumar(5, 3) . "\n";
echo Calculadora::restar(10, 4);
//Ejemplo 4: Clase con métodos estáticos
?>