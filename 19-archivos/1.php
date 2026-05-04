<?php
// ── Leer y escribir con funciones simples ────────────────────────────────────

// file_put_contents — escribe un archivo (lo crea si no existe)
$texto = "Línea 1: Hola desde PHP\nLínea 2: Aprendiendo archivos\nLínea 3: Fácil y útil";
file_put_contents("ejemplo.txt", $texto);
echo "Archivo 'ejemplo.txt' creado.<br>";

// FILE_APPEND — añadir al final sin borrar el contenido existente
file_put_contents("ejemplo.txt", "\nLínea 4: Añadida con FILE_APPEND", FILE_APPEND);

echo "<hr>";

// file_get_contents — lee el archivo completo como string
$contenido = file_get_contents("ejemplo.txt");
echo "<pre>" . htmlspecialchars($contenido) . "</pre>";

echo "<hr>";

// file() — lee el archivo como array (un elemento por línea)
$lineas = file("ejemplo.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
echo "Total de líneas: " . count($lineas) . "<br>";
foreach ($lineas as $i => $linea) {
    echo "[$i] $linea<br>";
}

echo "<hr>";

// Verificaciones de existencia
echo file_exists("ejemplo.txt") ? "El archivo SÍ existe.<br>" : "No existe.<br>";
echo is_file("ejemplo.txt")     ? "Es un archivo.<br>"        : "No es archivo.<br>";
echo is_readable("ejemplo.txt") ? "Es legible.<br>"           : "No es legible.<br>";
echo is_writable("ejemplo.txt") ? "Es escribible.<br>"        : "No es escribible.<br>";
