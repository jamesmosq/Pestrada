<?php
$calificacion = 'D';

switch ($calificacion) {
    case 'A':
        echo "Excelente\n";
        break; // Detiene la evaluación después de ejecutar este caso
    case 'B':
        echo "Muy bien\n";
        break; // Detiene la evaluación después de ejecutar este caso
    case 'C':
        echo "Bien\n";
        break; // Detiene la evaluación después de ejecutar este caso
    case 'D':
        echo "Pasable\n";
        break; // Detiene la evaluación después de ejecutar este caso
    default:
        echo "Calificación no válida\n";
        break; // Detiene la evaluación después de ejecutar este caso
}
?>
