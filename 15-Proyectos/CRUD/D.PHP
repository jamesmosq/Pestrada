<?php
// Ejemplo de un programa estructurado para calcular el factorial de un número

// Función para calcular el factorial
function factorial($n) {
    if ($n <= 1) {
        return 1;
    } else {
        return $n * factorial($n - 1);
    }
}

// Función principal del programa
function main() {
    echo "Calculadora de Factorial\n";
    
    // Bucle para permitir múltiples cálculos
    while (true) {
        // Entrada del usuario
        echo "Ingrese un número (o 'q' para salir): ";
        $input = trim(fgets(STDIN));
        
        // Estructura de control para salir del programa
        if ($input === 'q') {
            echo "Gracias por usar la calculadora. ¡Hasta luego!\n";
            break;
        }
        
        // Convertir la entrada a un número entero
        $number = intval($input);
        
        // Estructura de control para validar la entrada
        if ($number < 0) {
            echo "Por favor, ingrese un número no negativo.\n";
            continue;
        }
        
        // Cálculo y salida del resultado
        $result = factorial($number);
        echo "El factorial de $number es $result\n";
    }
}

// Llamada a la función principal
main();
?>