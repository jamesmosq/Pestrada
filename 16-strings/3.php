<?php
// ── Dividir, unir y limpiar ──────────────────────────────────────────────────

// explode - convierte string en array usando un separador
$csv = "Ana,Luis,Pedro,María,Juan";
$nombres = explode(",", $csv);
print_r($nombres);
echo "<br>";

// Limitar el número de partes
$partes = explode(",", $csv, 3);
print_r($partes); // ["Ana", "Luis", "Pedro,María,Juan"]

echo "<hr>";

// implode (o join) - une array en string
$unido = implode(" | ", $nombres);
echo $unido . "<br>"; // Ana | Luis | Pedro | María | Juan

echo "<hr>";

// trim - quitar espacios (o caracteres) al inicio y al final
$sucio = "   Hola Mundo   ";
echo "'" . trim($sucio)  . "'<br>"; // 'Hola Mundo'
echo "'" . ltrim($sucio) . "'<br>"; // 'Hola Mundo   '  (solo izquierda)
echo "'" . rtrim($sucio) . "'<br>"; // '   Hola Mundo'  (solo derecha)

// trim también puede quitar caracteres específicos
echo trim("***PHP***", "*") . "<br>"; // PHP

echo "<hr>";

// chunk_split - divide en trozos (útil para formatear)
echo chunk_split("ABCDEFGHIJ", 2, "-") . "<br>"; // AB-CD-EF-GH-IJ-
