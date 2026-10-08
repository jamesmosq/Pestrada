# Soluciones — 16 Strings

> **Antes de mirar:** intenta el ejercicio por tu cuenta al menos 20 minutos. Si te bloqueas, lee solo
> la pista del enunciado y vuelve a intentarlo. Cuando lo termines (o si de verdad no sale), compara con esta
> solución: es **una** forma de resolverlo, no la única.

---

## 1. Limpiar un nombre

```php
<?php
$nombre = "   cArLoS anDRÉS   pérez  ";

$limpio = trim($nombre);                          // quita espacios a los lados
$limpio = preg_replace('/\s+/', ' ', $limpio);    // varios espacios seguidos -> uno
$limpio = mb_convert_case($limpio, MB_CASE_TITLE, 'UTF-8');

echo $limpio; // Carlos Andrés Pérez
```

**Para entender:** con `ucwords(strtolower(...))` el resultado sería `Carlos AndrÉs PÉrez`,
porque esas funciones no reconocen letras con tilde.

---

## 2. Estadísticas de una frase

```php
<?php
$frase = "La programación en PHP es muy útil";

echo "Caracteres: " . mb_strlen($frase) . "<br>";
echo "Palabras: "   . count(explode(' ', $frase)) . "<br>";
echo "Mayúsculas: " . mb_strtoupper($frase) . "<br>";

// strrev() invierte BYTES y rompe las tildes. Invertimos letra por letra:
$letras = mb_str_split($frase);
echo "Al revés: " . implode('', array_reverse($letras)) . "<br>";
```

**Para entender:** `strlen($frase)` da 36, no 34 (cada tilde ocupa 2 bytes en UTF-8).
`str_word_count` tampoco sirve aquí: corta "programación" en dos al llegar a la `ó`.

---

## 3. Iniciales

```php
<?php
function iniciales(string $nombre): string
{
    $resultado = '';
    foreach (explode(' ', trim($nombre)) as $palabra) {
        if ($palabra !== '') {
            $resultado .= mb_strtoupper(mb_substr($palabra, 0, 1)) . '.';
        }
    }
    return $resultado;
}

echo iniciales("Ana María López Restrepo") . "<br>"; // A.M.L.R.
echo iniciales("  óscar   ñañez ") . "<br>";         // Ó.Ñ.
```

---

## 4. Código de producto

```php
<?php
function codigoProducto(int $numero): string
{
    return sprintf('PROD-%05d', $numero);
}

function numeroProducto(string $codigo): int
{
    return (int) str_replace('PROD-', '', $codigo);
}

echo codigoProducto(42) . "<br>";            // PROD-00042
echo numeroProducto('PROD-00042') . "<br>";  // 42
var_dump(numeroProducto('PROD-00042'));      // int(42)
```

---

## 5. Censurar palabras

```php
<?php
function censurar(string $texto, array $prohibidas): string
{
    foreach ($prohibidas as $palabra) {
        $asteriscos = str_repeat('*', mb_strlen($palabra));
        $texto = str_ireplace($palabra, $asteriscos, $texto);
    }
    return $texto;
}

echo censurar("Eres un TONTO y muy feo", ["tonto", "feo"]);
// Eres un ***** y muy ***
```

**Para entender:** ¿qué pasa con `"Federico"`? `str_ireplace` también censura el "fe" de *Fe*derico
si "fe" estuviera en la lista. Para palabras completas se usa `preg_replace('/\bfeo\b/i', ...)`.

---

## 6. ¿Es palíndromo?

```php
<?php
function esPalindromo(string $texto): bool
{
    $limpio = strtolower(str_replace(' ', '', $texto));
    return $limpio === strrev($limpio);
}

$pruebas = ["Anita lava la tina", "Somos o no somos", "Hola mundo"];

foreach ($pruebas as $p) {
    echo $p . ": " . (esPalindromo($p) ? "sí" : "no") . "<br>";
}
```

---

## 7. Desarmar un correo

```php
<?php
$correo = "laura.gomez@sena.edu.co";

[$usuario, $dominio] = explode('@', $correo);

echo "Usuario: $usuario<br>";
echo "Dominio: $dominio<br>";
echo "¿Es institucional? " . (str_ends_with($dominio, 'sena.edu.co') ? 'Sí' : 'No') . "<br>";

echo "<hr>";

foreach (["laura.gomez@sena.edu.co", "laura@"] as $c) {
    echo $c . " → " . (filter_var($c, FILTER_VALIDATE_EMAIL) ? "válido" : "inválido") . "<br>";
}
```

**Para entender:** `[$a, $b] = explode(...)` es *desestructuración*; equivale a la de Python `a, b = s.split('@')`.

---

## 8. Factura alineada

```php
<?php
$items = [
    ['producto' => 'Teclado',   'cantidad' => 2, 'precio' => 45000],
    ['producto' => 'Mouse',     'cantidad' => 3, 'precio' => 18500.5],
    ['producto' => 'Monitor',   'cantidad' => 1, 'precio' => 650000],
];

function pesos(float $valor): string
{
    return '$ ' . number_format($valor, 0, ',', '.');
}

$linea = str_repeat('-', 36);
$total = 0;

echo "<pre>";
echo str_pad('PRODUCTO', 12) . str_pad('CANT', 8, ' ', STR_PAD_LEFT)
   . str_pad('SUBTOTAL', 16, ' ', STR_PAD_LEFT) . "\n";
echo $linea . "\n";

foreach ($items as $item) {
    $subtotal = $item['cantidad'] * $item['precio'];
    $total   += $subtotal;

    echo str_pad($item['producto'], 12)
       . str_pad($item['cantidad'], 8, ' ', STR_PAD_LEFT)
       . str_pad(pesos($subtotal), 16, ' ', STR_PAD_LEFT) . "\n";
}

echo $linea . "\n";
echo str_pad('TOTAL', 20) . str_pad(pesos($total), 16, ' ', STR_PAD_LEFT) . "\n";
echo "</pre>";
```

---

## 9. De CSV a tabla HTML

```php
<?php
$csv = "nombre,edad,ciudad
Ana,25,Medellín
Luis,31,Itagüí
<b>Pedro</b>,28,Envigado";

$lineas      = explode("\n", trim($csv));
$encabezados = explode(',', array_shift($lineas)); // saca la primera línea

echo "<table border='1' cellpadding='6'>";
echo "<tr>";
foreach ($encabezados as $titulo) {
    echo "<th>" . htmlspecialchars(ucfirst($titulo)) . "</th>";
}
echo "</tr>";

foreach ($lineas as $linea) {
    echo "<tr>";
    foreach (explode(',', $linea) as $celda) {
        echo "<td>" . htmlspecialchars(trim($celda)) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";
```

**Para entender:** sin `htmlspecialchars`, Pedro se vería en negrita. Si en vez de `<b>` fuera un
`<script>`, se ejecutaría: eso es un ataque XSS.

---

## 10. Generador de slugs

```php
<?php
function slug(string $titulo): string
{
    $texto = mb_strtolower($titulo, 'UTF-8');

    $texto = strtr($texto, [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'ü' => 'u', 'ñ' => 'n',
    ]);

    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto); // todo lo demás -> guion
    return trim($texto, '-');                           // sin guiones en los extremos
}

echo slug("¡Hola Mundo! Aprendiendo PHP 8 en Itagüí") . "<br>";
// hola-mundo-aprendiendo-php-8-en-itagui
echo slug("  --Año   Nuevo--  ") . "<br>";
// ano-nuevo
```

---

## 11. Validar contraseña segura

```php
<?php
function validarPassword(string $pass): array
{
    $errores = [];

    if (strlen($pass) < 8) {
        $errores[] = "Debe tener mínimo 8 caracteres.";
    }
    if (!preg_match('/[A-Z]/', $pass)) {
        $errores[] = "Debe tener al menos una mayúscula.";
    }
    if (!preg_match('/[a-z]/', $pass)) {
        $errores[] = "Debe tener al menos una minúscula.";
    }
    if (!preg_match('/[0-9]/', $pass)) {
        $errores[] = "Debe tener al menos un número.";
    }
    if (stripos($pass, 'password') !== false) {
        $errores[] = "No puede contener la palabra 'password'.";
    }

    return $errores;
}

foreach (["abc", "password123A", "Sena2026segura"] as $prueba) {
    $errores = validarPassword($prueba);
    echo "<strong>$prueba</strong>: ";
    echo empty($errores) ? "[OK] válida" : implode(' ', $errores);
    echo "<br>";
}
```

**Para entender:** `stripos` devuelve `0` si la palabra está al inicio, y `0` es "falso".
Por eso se compara con `!== false` y no con `if (stripos(...))`.

---

## 12. Cifrado César

```php
<?php
function cifrar(string $texto, int $n): string
{
    $resultado = '';
    $n = (($n % 26) + 26) % 26; // acepta desplazamientos negativos o mayores a 26

    foreach (str_split($texto) as $caracter) {
        if (ctype_upper($caracter)) {
            $base = ord('A');
        } elseif (ctype_lower($caracter)) {
            $base = ord('a');
        } else {
            $resultado .= $caracter; // no es letra: se deja igual
            continue;
        }
        $posicion   = ord($caracter) - $base;          // A=0, B=1, ... Z=25
        $resultado .= chr($base + ($posicion + $n) % 26);
    }

    return $resultado;
}

function descifrar(string $texto, int $n): string
{
    return cifrar($texto, -$n);
}

echo cifrar("Hola Mundo!", 3) . "<br>";     // Krod Pxqgr!
echo descifrar("Krod Pxqgr!", 3) . "<br>";  // Hola Mundo!
echo cifrar("xyz XYZ", 3) . "<br>";         // abc ABC
```

**Para entender:** `descifrar` reutiliza `cifrar` con el desplazamiento negativo. Escribir una función
en términos de otra es una idea clave para no repetir código.
