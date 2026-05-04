<?php
// ── Buscar, extraer y reemplazar ─────────────────────────────────────────────

$frase = "Me gusta programar en PHP y PHP es genial";

// strpos - posición de la primera aparición (-1 si no existe)
$pos = strpos($frase, "PHP");
echo "Primera aparición de PHP en posición: $pos<br>"; // 22

// strrpos - última aparición
echo "Última aparición de PHP en posición: " . strrpos($frase, "PHP") . "<br>";

echo "<hr>";

// substr - extraer parte de un string
echo substr("Hola Mundo", 5)     . "<br>"; // Mundo
echo substr("Hola Mundo", 0, 4)  . "<br>"; // Hola
echo substr("Hola Mundo", -5)    . "<br>"; // Mundo (desde el final)

echo "<hr>";

// str_replace - reemplazar texto
$nuevo = str_replace("PHP", "Python", $frase);
echo $nuevo . "<br>";

// str_ireplace - igual pero sin distinción mayúsculas/minúsculas
echo str_ireplace("php", "JavaScript", $frase) . "<br>";

echo "<hr>";

// str_contains, str_starts_with, str_ends_with (PHP 8+)
echo str_contains($frase, "PHP")      ? "Contiene 'PHP'<br>"     : "No contiene<br>";
echo str_starts_with($frase, "Me")    ? "Empieza con 'Me'<br>"   : "No empieza<br>";
echo str_ends_with($frase, "genial")  ? "Termina en 'genial'<br>": "No termina<br>";
