<?php
// ── Formato y presentación ───────────────────────────────────────────────────

// sprintf - construye un string formateado
$nombre = "Carlos";
$edad   = 28;
$precio = 1234.5;

$mensaje = sprintf("Me llamo %s, tengo %d años y pagué $%.2f", $nombre, $edad, $precio);
echo $mensaje . "<br>";

// Especificadores comunes:
// %s = string   %d = entero   %f = float   %05d = entero con 5 dígitos relleno de ceros

echo sprintf("Número de orden: %05d<br>", 42);   // Número de orden: 00042
echo sprintf("Porcentaje: %.1f%%<br>", 87.5);    // Porcentaje: 87.5%

echo "<hr>";

// number_format - formato numérico con separadores
$monto = 1234567.891;
echo number_format($monto)           . "<br>"; // 1,234,568
echo number_format($monto, 2)        . "<br>"; // 1,234,567.89
echo number_format($monto, 2, ',', '.') . "<br>"; // 1.234.567,89 (formato colombiano)

echo "<hr>";

// str_pad - rellenar hasta alcanzar un ancho
echo str_pad("7",  3, "0", STR_PAD_LEFT)  . "<br>"; // 007
echo str_pad("PHP", 10, "-")              . "<br>"; // PHP-------
echo str_pad("PHP", 10, "-", STR_PAD_BOTH). "<br>"; // ---PHP----

echo "<hr>";

// nl2br - convierte saltos de línea en <br>
$multilinea = "Línea 1\nLínea 2\nLínea 3";
echo nl2br($multilinea);
