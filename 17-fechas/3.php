<?php
// ── Clase DateTime — manejo orientado a objetos ──────────────────────────────

// Crear objeto con la fecha/hora actual
$hoy = new DateTime();
echo "Hoy: " . $hoy->format("d/m/Y H:i:s") . "<br>";

// Fecha específica
$fecha = new DateTime("1995-08-20");
echo "Fecha: " . $fecha->format("d \d\e F \d\e Y") . "<br>";

echo "<hr>";

// Modificar fechas con modify()
$fecha = new DateTime("2026-01-01");
$fecha->modify("+45 days");
echo "+45 días desde 01/01/2026: " . $fecha->format("d/m/Y") . "<br>";

// DateInterval — representar un intervalo de tiempo
$intervalo = new DateInterval("P1Y2M3D"); // P = Period, 1 año 2 meses 3 días
$fecha = new DateTime("2026-01-01");
$fecha->add($intervalo);
echo "Después de 1 año, 2 meses y 3 días: " . $fecha->format("d/m/Y") . "<br>";

echo "<hr>";

// Diferencia entre dos fechas — diff()
$nacimiento = new DateTime("1995-03-20");
$hoy        = new DateTime();
$diferencia = $hoy->diff($nacimiento);

echo "Edad: "    . $diferencia->y . " años<br>";
echo "Meses: "   . $diferencia->m . "<br>";
echo "Días:  "   . $diferencia->d . "<br>";
echo "Total días vividos: " . $diferencia->days . "<br>";

echo "<hr>";

// Comparar fechas
$fecha1 = new DateTime("2026-06-01");
$fecha2 = new DateTime("2026-12-01");

if ($fecha1 < $fecha2) {
    echo $fecha1->format("d/m/Y") . " es anterior a " . $fecha2->format("d/m/Y") . "<br>";
}

// DatePeriod — iterar sobre un rango de fechas
$inicio  = new DateTime("2026-01-01");
$fin     = new DateTime("2026-01-07");
$periodo = new DatePeriod($inicio, new DateInterval("P1D"), $fin);

echo "<hr>Primera semana de 2026:<br>";
foreach ($periodo as $dia) {
    echo $dia->format("d/m/Y - l") . "<br>";
}
