# Soluciones — 17 Fechas

> **Antes de mirar:** intenta el ejercicio por tu cuenta al menos 20 minutos. Si te bloqueas, lee solo
> la pista del enunciado y vuelve a intentarlo. Cuando lo termines (o si de verdad no sale), compara con esta
> solución: es **una** forma de resolverlo, no la única.
>
> Todas las soluciones empiezan con `date_default_timezone_set('America/Bogota');`.

---

## 1. Saludo según la hora

```php
<?php
date_default_timezone_set('America/Bogota');

function saludo(int $hora): string
{
    if ($hora >= 5 && $hora < 12) {
        return "Buenos días";
    } elseif ($hora >= 12 && $hora < 19) {
        return "Buenas tardes";
    }
    return "Buenas noches";
}

echo "Son las " . date('H:i') . ". " . saludo((int) date('G')) . "<br><hr>";

foreach ([4, 9, 15, 21] as $h) {
    echo "$h:00 → " . saludo($h) . "<br>";
}
```

---

## 2. Fecha en español

```php
<?php
date_default_timezone_set('America/Bogota');

function fechaEnEspanol(DateTime $fecha): string
{
    $dias  = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    $meses = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
              'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

    $dia = $dias[(int) $fecha->format('w')];
    $mes = $meses[(int) $fecha->format('n')];

    return "$dia, " . $fecha->format('j') . " de $mes de " . $fecha->format('Y');
}

echo fechaEnEspanol(new DateTime('2026-10-07')) . "<br>"; // miércoles, 7 de octubre de 2026
echo fechaEnEspanol(new DateTime()) . "<br>";             // hoy
```

**Para entender:** `[1 => 'enero', 'febrero', ...]` hace que el array empiece en 1 y los demás
índices sigan solos (2, 3, …). Así `$meses[10]` es octubre sin restar 1.
*(Existe `IntlDateFormatter` para esto, pero el ejercicio es practicar arrays.)*

---

## 3. ¿Es mayor de edad?

```php
<?php
date_default_timezone_set('America/Bogota');

function esMayorDeEdad(string $fechaNacimiento): bool
{
    $nacimiento = new DateTime($fechaNacimiento);
    $hoy        = new DateTime('today');
    return $nacimiento->diff($hoy)->y >= 18;
}

$pruebas = [
    'Cumple 18 hoy'    => date('Y-m-d', strtotime('-18 years')),
    'Cumple 18 mañana' => date('Y-m-d', strtotime('-18 years +1 day')),
    'Tiene 30'         => date('Y-m-d', strtotime('-30 years')),
];

foreach ($pruebas as $caso => $fecha) {
    echo "$caso ($fecha): " . (esMayorDeEdad($fecha) ? 'mayor' : 'menor') . "<br>";
}
```

**Para entender:** restar años "a mano" (`date('Y') - 1995`) falla si la persona aún no ha cumplido años
este año. `diff()` sí lo tiene en cuenta.

---

## 4. Diferentes formatos

```php
<?php
date_default_timezone_set('America/Bogota');

$fecha = new DateTime('2026-12-24 20:30:00');

echo "Base de datos: " . $fecha->format('Y-m-d H:i:s') . "<br>";
echo "Corto: "         . $fecha->format('d/m/Y') . "<br>";
echo "AM/PM: "         . $fecha->format('d/m/Y h:i A') . "<br>";
echo "Solo hora: "     . $fecha->format('H:i') . "<br>";
echo "Día "            . ($fecha->format('z') + 1) . " del año<br>"; // z empieza en 0
```

---

## 5. Edad exacta

```php
<?php
date_default_timezone_set('America/Bogota');

$nacimiento = new DateTime('1995-03-20');
$hoy        = new DateTime('today');
$edad       = $nacimiento->diff($hoy);

echo "Naciste el " . $nacimiento->format('d/m/Y') . ".<br>";
echo "Tienes {$edad->y} años, {$edad->m} meses y {$edad->d} días.<br>";
echo "Has vivido " . number_format($edad->days, 0, ',', '.') . " días.<br>";
```

**Para entender:** `{$edad->y}` dentro de comillas dobles: las llaves le dicen a PHP dónde termina la variable
(como las f-strings de Python).

---

## 6. ¿Cuánto falta para mi cumpleaños?

```php
<?php
date_default_timezone_set('America/Bogota');

function diasParaCumpleanos(string $fechaNacimiento): int
{
    $nacimiento = new DateTime($fechaNacimiento);
    $hoy        = new DateTime('today');

    // Cumpleaños de este año
    $cumple = new DateTime($hoy->format('Y') . '-' . $nacimiento->format('m-d'));

    if ($cumple < $hoy) {
        $cumple->modify('+1 year'); // ya pasó: el próximo es el otro año
    }

    return $hoy->diff($cumple)->days;
}

$pruebas = [
    date('Y-m-d', strtotime('-20 years')),          // hoy
    date('Y-m-d', strtotime('-20 years +10 days')), // en 10 días
    date('Y-m-d', strtotime('-20 years -1 day')),   // fue ayer
];

foreach ($pruebas as $fecha) {
    $dias = diasParaCumpleanos($fecha);
    echo "$fecha → " . ($dias === 0 ? "¡Feliz cumpleaños!" : "faltan $dias días") . "<br>";
}
```

---

## 7. Vencimiento de facturas

```php
<?php
date_default_timezone_set('America/Bogota');

$facturas = [
    ['numero' => 'F-001', 'emision' => date('Y-m-d', strtotime('-10 days'))],
    ['numero' => 'F-002', 'emision' => date('Y-m-d', strtotime('-30 days'))],
    ['numero' => 'F-003', 'emision' => date('Y-m-d', strtotime('-45 days'))],
];

$hoy = new DateTime('today');

foreach ($facturas as $f) {
    $vence = (new DateTime($f['emision']))->modify('+30 days');
    $dias  = (int) $hoy->diff($vence)->format('%r%a'); // %r pone el signo si es negativo

    if ($dias > 0) {
        $estado = "Vigente (faltan $dias días)";
    } elseif ($dias === 0) {
        $estado = "Vence hoy";
    } else {
        $estado = "Vencida hace " . abs($dias) . " días";
    }

    echo "{$f['numero']} — vence el " . $vence->format('d/m/Y') . " — $estado<br>";
}
```

**Para entender:** `->days` siempre es positivo. Para saber si la fecha ya pasó se usa
`format('%r%a')` o se compara `$vence < $hoy`.

---

## 8. Sumar días hábiles

```php
<?php
date_default_timezone_set('America/Bogota');

function sumarDiasHabiles(string $fecha, int $dias, array $festivos = []): string
{
    $actual = new DateTime($fecha);

    while ($dias > 0) {
        $actual->modify('+1 day');

        $esFinDeSemana = $actual->format('N') >= 6; // 6 = sábado, 7 = domingo
        $esFestivo     = in_array($actual->format('Y-m-d'), $festivos);

        if (!$esFinDeSemana && !$esFestivo) {
            $dias--;
        }
    }

    return $actual->format('Y-m-d');
}

echo sumarDiasHabiles('2026-10-09', 3) . "<br>";                 // 2026-10-14
echo sumarDiasHabiles('2026-10-07', 1) . "<br>";                 // 2026-10-08
echo sumarDiasHabiles('2026-10-09', 3, ['2026-10-12']) . "<br>"; // 2026-10-15
```

---

## 9. Validar una fecha del formulario

```php
<?php
function validarFecha(string $texto): bool
{
    $partes = explode('/', $texto);

    if (count($partes) !== 3) {
        return false;
    }

    [$dia, $mes, $anio] = $partes;

    if (!ctype_digit($dia) || !ctype_digit($mes) || !ctype_digit($anio) || strlen($anio) !== 4) {
        return false;
    }

    return checkdate((int) $mes, (int) $dia, (int) $anio);
}

foreach (['15/06/2026', '31/02/2026', '2026-06-15', 'hola', '29/02/2028'] as $prueba) {
    echo "$prueba → " . (validarFecha($prueba) ? 'válida' : 'inválida') . "<br>";
}
```

**Alternativa con DateTime:**

```php
<?php
function validarFecha(string $texto): bool
{
    $fecha = DateTime::createFromFormat('!d/m/Y', $texto);
    // Si PHP tuvo que "acomodar" la fecha (31/02 -> 03/03), ya no coincide con el texto
    return $fecha !== false && $fecha->format('d/m/Y') === $texto;
}

foreach (['15/06/2026', '31/02/2026', '2026-06-15', 'hola'] as $prueba) {
    echo "$prueba → " . (validarFecha($prueba) ? 'válida' : 'inválida') . "<br>";
}
```

---

## 10. Calendario del mes

```php
<?php
date_default_timezone_set('America/Bogota');

function calendario(int $mes, int $anio): void
{
    $primerDia   = mktime(0, 0, 0, $mes, 1, $anio);
    $diasDelMes  = (int) date('t', $primerDia);
    $inicio      = (int) date('N', $primerDia); // 1 = lunes ... 7 = domingo
    $hoy         = date('Y-m-d');

    echo "<table border='1' cellpadding='6' style='border-collapse:collapse; text-align:center'>";
    echo "<tr><th>Lu</th><th>Ma</th><th>Mi</th><th>Ju</th><th>Vi</th><th>Sa</th><th>Do</th></tr><tr>";

    // Celdas vacías antes del día 1
    for ($i = 1; $i < $inicio; $i++) {
        echo "<td></td>";
    }

    for ($dia = 1; $dia <= $diasDelMes; $dia++) {
        $fecha  = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
        $estilo = $fecha === $hoy ? " style='background:#39a900; color:white'" : "";
        echo "<td$estilo>$dia</td>";

        // Si el día cae en domingo, cerramos la fila
        if (($dia + $inicio - 1) % 7 === 0 && $dia < $diasDelMes) {
            echo "</tr><tr>";
        }
    }

    echo "</tr></table>";
}

calendario(10, 2026);
```

---

## 11. "Hace cuánto"

```php
<?php
date_default_timezone_set('America/Bogota');

function plural(int $n, string $singular, string $plural): string
{
    return "hace $n " . ($n === 1 ? $singular : $plural);
}

function haceCuanto(int $timestamp): string
{
    $segundos = time() - $timestamp;

    if ($segundos < 60) {
        return "hace un momento";
    }
    if ($segundos < 3600) {
        return plural(intdiv($segundos, 60), 'minuto', 'minutos');
    }
    if ($segundos < 86400) {
        return plural(intdiv($segundos, 3600), 'hora', 'horas');
    }
    if ($segundos < 86400 * 30) {
        return plural(intdiv($segundos, 86400), 'día', 'días');
    }
    return date('d/m/Y', $timestamp);
}

foreach (['-20 seconds', '-1 minute', '-5 minutes', '-3 hours', '-1 day', '-2 days', '-60 days'] as $hace) {
    echo str_pad($hace, 12) . " → " . haceCuanto(strtotime($hace)) . "<br>";
}
```

**Para entender:** `intdiv` hace la división entera. En Laravel esto se escribe
`$fecha->diffForHumans()` (librería Carbon).

---

## 12. Cronograma de la ficha

```php
<?php
date_default_timezone_set('America/Bogota');

$diasClase = [1, 3, 5]; // N: 1 = lunes, 3 = miércoles, 5 = viernes
$festivos  = ['2026-10-12', '2026-11-02'];
$nombres   = [1 => 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];

$fecha   = new DateTime('2026-10-05');
$sesion  = 1;
$total   = 12;

while ($sesion <= $total) {
    $n = (int) $fecha->format('N');

    if (in_array($n, $diasClase) && !in_array($fecha->format('Y-m-d'), $festivos)) {
        echo str_pad("Sesión $sesion", 10) . " — {$nombres[$n]} " . $fecha->format('d/m/Y') . "<br>";
        $sesion++;
    }

    $fecha->modify('+1 day');
}
```
