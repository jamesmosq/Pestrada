<?php
// Ejemplo 1: Imprimir números del 0 al 5
for ($i = 0; $i <= 5; $i++) {
    echo $i . "<br>";
}
?>

<?php
// Ejemplo 2: Bucle for con incremento de 2
for ($i = 0; $i <= 10; $i += 2) {
    echo $i . "<br>";
}
?>

<?php
// Ejemplo 3: Recorrer un array con for
$frutas = array("Manzana", "Banana", "Naranja", "Uva");

for ($i = 0; $i < count($frutas); $i++) {
    echo $frutas[$i] . "<br>";
}
?>