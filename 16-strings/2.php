<?php
// ── Buscar, extraer y reemplazar ─────────────────────────────────────────────

$frase = "Me gusta programar en PHP y PHP es genial";

// strpos - posición de la primera aparición (empieza a contar en 0).
// Si NO la encuentra devuelve false (no -1 como en JavaScript).
$pos = strpos($frase, "PHP");
echo "Primera aparición de PHP en posición: $pos<br>"; // 22

// Cuidado: si el texto está al inicio, strpos devuelve 0, y 0 se evalúa como falso.
// Por eso SIEMPRE se compara con !== false
if (strpos($frase, "Me") !== false) {
    echo "'Me' sí está en la frase (en la posición 0)<br>";
}
if (strpos($frase, "Java") === false) {
    echo "'Java' no está en la frase<br>";
}

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
