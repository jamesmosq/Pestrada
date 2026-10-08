<?php
// ── Longitud y transformación de texto ──────────────────────────────────────

$texto = "Hola Mundo PHP";

// strlen - número de caracteres
echo strlen($texto) . "<br>";           // 14

// Mayúsculas / minúsculas
echo strtolower($texto) . "<br>";       // hola mundo php
echo strtoupper($texto) . "<br>";       // HOLA MUNDO PHP

// ucfirst - primera letra de la cadena en mayúscula
echo ucfirst("hola mundo") . "<br>";    // Hola mundo

// ucwords - primera letra de cada palabra en mayúscula
echo ucwords("hola mundo php") . "<br>"; // Hola Mundo Php

echo "<hr>";

// str_repeat - repetir una cadena
echo str_repeat("=-", 15) . "<br>";

// strrev - invertir la cadena
echo strrev("Hola") . "<br>";           // aloH
echo strrev("PHP") . "<br>";            // PHP (se lee igual al revés: es un palíndromo)

// str_word_count - contar palabras
echo str_word_count("Hola Mundo PHP") . "<br>"; // 3
