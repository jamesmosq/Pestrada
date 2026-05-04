<?php
// ── date() y time() ──────────────────────────────────────────────────────────

// time() retorna el timestamp Unix actual (segundos desde 1970-01-01 00:00:00)
echo "Timestamp ahora: " . time() . "<br>";

echo "<hr>";

// date(formato, timestamp) - formatea una fecha
// Si no se pasa timestamp, usa la fecha/hora actual

echo date("d/m/Y")          . "<br>"; // 03/05/2026
echo date("d/m/Y H:i:s")    . "<br>"; // 03/05/2026 14:30:00
echo date("Y-m-d")          . "<br>"; // 2026-05-03 (formato ISO, útil para BD)

echo "<hr>";

// Letras de formato más usadas:
// Y = año 4 dígitos    y = año 2 dígitos
// m = mes 01-12        M = mes abreviado (Jan, Feb...)
// d = día 01-31        D = día abreviado (Mon, Tue...)
// H = hora 00-23       h = hora 01-12
// i = minutos          s = segundos
// N = día semana 1=Lun 7=Dom
// t = días del mes actual

echo "Año:     " . date("Y")  . "<br>";
echo "Mes:     " . date("m")  . "<br>";
echo "Día:     " . date("d")  . "<br>";
echo "Hora:    " . date("H")  . "<br>";
echo "Minutos: " . date("i")  . "<br>";
echo "Segundos:" . date("s")  . "<br>";
echo "Día semana (1=Lun): " . date("N") . "<br>";
echo "Días en este mes: "   . date("t") . "<br>";

echo "<hr>";

// Timestamp de una fecha específica - mktime(hora, min, seg, mes, dia, año)
$navidad = mktime(0, 0, 0, 12, 25, 2026);
echo "Navidad 2026: " . date("d/m/Y", $navidad) . "<br>";

// Días que faltan para navidad
$dias = round(($navidad - time()) / 86400);
echo "Faltan $dias días para Navidad<br>";
