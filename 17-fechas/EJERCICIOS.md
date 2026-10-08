# Ejercicios — 17 Fechas

Antes de empezar, repasa `1.php` (date / time / mktime), `2.php` (strtotime) y `3.php` (DateTime).

**Cómo trabajar:** crea la carpeta `ejercicios/` y un archivo por ejercicio (`ej01.php`, …).

| Nivel | Ejercicios |
|---|---|
| Básico | 1 – 4 |
| Intermedio | 5 – 9 |
| Reto | 10 – 12 |

> **Primera línea de cada ejercicio:**
> ```php
> date_default_timezone_set('America/Bogota');
> ```
> Si no la pones, PHP puede usar la hora de Londres (UTC) y tus resultados saldrán 5 horas corridos.

---

## 1. Saludo según la hora

Muestra la hora actual (`14:35`) y un saludo:

| Hora | Saludo |
|---|---|
| 05:00 – 11:59 | Buenos días |
| 12:00 – 18:59 | Buenas tardes |
| 19:00 – 04:59 | Buenas noches |

Haz que el saludo salga de una función `saludo(int $hora): string` y pruébala con varias horas
(`saludo(4)`, `saludo(9)`, `saludo(15)`, `saludo(21)`).

**Pista:** `(int) date('G')` da la hora sin cero adelante.

---

## 2. Fecha en español

`date('l, j F Y')` muestra la fecha en inglés. Escribe `fechaEnEspanol(DateTime $fecha): string`
para que se vea en español:

```php
echo fechaEnEspanol(new DateTime('2026-10-07')); // miércoles, 7 de octubre de 2026
```

**Pista:** dos arrays (`$dias` y `$meses`) y los formatos `w` (0 = domingo) y `n` (1 = enero).

---

## 3. ¿Es mayor de edad?

Crea `esMayorDeEdad(string $fechaNacimiento): bool`. Prueba con una persona que cumple 18 **hoy**,
otra que los cumple **mañana** y otra de 30 años.

**Pista:** `(new DateTime($fecha))->diff(new DateTime())->y`. Para construir las fechas de prueba usa
`date('Y-m-d', strtotime('-18 years'))`.

---

## 4. Diferentes formatos

Con la fecha `2026-12-24 20:30:00` muestra:

| Formato | Resultado |
|---|---|
| Para base de datos | `2026-12-24 20:30:00` |
| Corto | `24/12/2026` |
| Con hora AM/PM | `24/12/2026 08:30 PM` |
| Solo hora | `20:30` |
| Día del año | `Día 358 del año` |

**Pista:** `DateTime::format()`. Busca en la documentación las letras `h`, `A` y `z` (¡ojo, `z` empieza en 0!).

---

## 5. Edad exacta

Dada una fecha de nacimiento, muestra la edad exacta:

```text
Naciste el 20/03/1995.
Tienes 31 años, 6 meses y 17 días.   (el resultado depende del día de hoy)
Has vivido 11.524 días.
```

**Pista:** `diff()` devuelve un objeto con `->y`, `->m`, `->d` y `->days`.

---

## 6. ¿Cuánto falta para mi cumpleaños?

Crea `diasParaCumpleanos(string $fechaNacimiento): int`. Si el cumpleaños de este año ya pasó,
calcula hasta el del año siguiente. Si es hoy, devuelve 0 y muestra "¡Feliz cumpleaños!".

**Pista:** arma la fecha `date('Y') . '-' . $mes . '-' . $dia`; si es menor que hoy, súmale `+1 year`.

---

## 7. Vencimiento de facturas

Una factura vence 30 días después de su emisión. Con este array, muestra para cada factura su fecha de
vencimiento y el estado: **Vigente (faltan N días)**, **Vence hoy** o **Vencida hace N días**.

```php
$facturas = [
    ['numero' => 'F-001', 'emision' => date('Y-m-d', strtotime('-10 days'))],
    ['numero' => 'F-002', 'emision' => date('Y-m-d', strtotime('-30 days'))],
    ['numero' => 'F-003', 'emision' => date('Y-m-d', strtotime('-45 days'))],
];
```

**Salida esperada (estados):** F-001 → Vigente (faltan 20 días) · F-002 → Vence hoy · F-003 → Vencida hace 15 días

---

## 8. Sumar días hábiles

Crea `sumarDiasHabiles(string $fecha, int $dias): string` que avance saltándose sábados y domingos.

```php
echo sumarDiasHabiles('2026-10-09', 3); // 2026-10-14  (viernes + 3 hábiles = miércoles)
echo sumarDiasHabiles('2026-10-07', 1); // 2026-10-08
```

**Extra:** agrega un tercer parámetro `array $festivos` y sáltalos también.
Con `['2026-10-12']` el primer ejemplo debe dar `2026-10-15`.

**Pista:** un `while` que haga `modify('+1 day')` y solo cuente si `format('N') < 6`.

---

## 9. Validar una fecha del formulario

Un usuario escribe la fecha como texto `dd/mm/aaaa`. Crea `validarFecha(string $texto): bool`:

| Entrada | Resultado |
|---|---|
| `15/06/2026` | válida |
| `31/02/2026` | inválida (febrero no tiene 31) |
| `2026-06-15` | inválida (formato equivocado) |
| `hola` | inválida |

**Pista:** `explode('/', ...)` + `checkdate($mes, $dia, $anio)`. O investiga `DateTime::createFromFormat('d/m/Y', ...)`.

---

## 10. Calendario del mes

Dibuja en una tabla HTML el calendario de un mes, con la semana empezando en **lunes**
y el día de hoy resaltado.

```php
calendario(10, 2026); // octubre de 2026: el día 1 cae en jueves
```

```text
Lu  Ma  Mi  Ju  Vi  Sa  Do
             1   2   3   4
 5   6   7   8   9  10  11
...
```

**Pista:** `date('N', mktime(0, 0, 0, $mes, 1, $anio))` te dice cuántas celdas vacías dejar al inicio,
y `date('t', ...)` cuántos días tiene el mes.

---

## 11. "Hace cuánto"

Las redes sociales no muestran la fecha de un comentario, sino *"hace 5 minutos"*.
Crea `haceCuanto(int $timestamp): string`:

| Diferencia | Texto |
|---|---|
| menos de 60 segundos | `hace un momento` |
| menos de 1 hora | `hace 5 minutos` (o `hace 1 minuto`) |
| menos de 1 día | `hace 3 horas` |
| menos de 30 días | `hace 2 días` |
| más | la fecha `dd/mm/aaaa` |

Cuida el singular y el plural.

---

## 12. Cronograma de la ficha

Una ficha tiene clase **lunes, miércoles y viernes**. Empieza el `2026-10-05` y tiene **12 sesiones**.
Los festivos no hay clase: `['2026-10-12', '2026-11-02']`.

Imprime el cronograma numerado:

```text
Sesión 1  — lunes 05/10/2026
Sesión 2  — miércoles 07/10/2026
Sesión 3  — viernes 09/10/2026
Sesión 4  — miércoles 14/10/2026   ← el 12 es festivo
...
Sesión 12 — miércoles 04/11/2026
```

**Pista:** reutiliza la función del ejercicio 2 para el nombre del día. `in_array` para los festivos.
