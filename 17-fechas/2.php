<?php
// ── strtotime() — convertir texto a timestamp ────────────────────────────────

// strtotime convierte strings de fecha/hora en timestamp Unix

// Fechas absolutas
echo date("d/m/Y", strtotime("2026-06-15"))        . "<br>"; // 15/06/2026
echo date("d/m/Y", strtotime("June 15, 2026"))     . "<br>"; // 15/06/2026
echo date("d/m/Y", strtotime("15-06-2026"))        . "<br>"; // 15/06/2026

echo "<hr>";

// Fechas relativas — muy útil para cálculos
echo date("d/m/Y", strtotime("+1 day"))      . "<br>"; // mañana
echo date("d/m/Y", strtotime("-1 day"))      . "<br>"; // ayer
echo date("d/m/Y", strtotime("+1 week"))     . "<br>"; // próxima semana
echo date("d/m/Y", strtotime("+1 month"))    . "<br>"; // próximo mes
echo date("d/m/Y", strtotime("+1 year"))     . "<br>"; // próximo año
echo date("d/m/Y", strtotime("-6 months"))   . "<br>"; // hace 6 meses

echo "<hr>";

// Expresiones en lenguaje natural
echo date("d/m/Y", strtotime("next Monday"))   . "<br>"; // próximo lunes
echo date("d/m/Y", strtotime("last Friday"))   . "<br>"; // último viernes
echo date("d/m/Y", strtotime("first day of next month")) . "<br>"; // primer día del mes siguiente
echo date("d/m/Y", strtotime("last day of this month"))  . "<br>"; // último día de este mes

echo "<hr>";

// Caso práctico: fechas de vencimiento
$hoy          = time();
$vencimiento  = strtotime("+30 days");
$dias_restantes = round(($vencimiento - $hoy) / 86400);

echo "Fecha de vencimiento: " . date("d/m/Y", $vencimiento) . "<br>";
echo "Días restantes: $dias_restantes<br>";
