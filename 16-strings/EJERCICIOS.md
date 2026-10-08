# Ejercicios — 16 Strings

Antes de empezar, repasa los ejemplos `1.php` a `4.php` de esta carpeta.

**Cómo trabajar:** crea una carpeta `ejercicios/` aquí mismo y un archivo por ejercicio
(`ej01.php`, `ej02.php`, …). Ábrelos en el navegador:
`http://localhost/Pestrada/16-strings/ejercicios/ej01.php`

| Nivel | Ejercicios | Qué se espera |
|---|---|---|
| Básico | 1 – 4 | Usar una o dos funciones de la guía |
| Intermedio | 5 – 9 | Combinar funciones y escribir tus propias funciones |
| Reto | 10 – 12 | Resolver un problema completo, investigando si hace falta |

> **Ojo con las tildes.** `strlen`, `strtolower` y `ucwords` no entienden la `á` ni la `ñ`.
> Para texto en español usa sus versiones `mb_`: `mb_strlen`, `mb_strtolower`, `mb_convert_case`.

---

## 1. Limpiar un nombre

Un usuario escribió su nombre así:

```php
$nombre = "   cArLoS anDRÉS   pérez  ";
```

Muéstralo limpio: sin espacios a los lados y con la primera letra de cada palabra en mayúscula.

**Salida esperada:** `Carlos Andrés Pérez`

**Pista:** `trim()`, `mb_convert_case(..., MB_CASE_TITLE)`. ¿Qué pasa con los espacios dobles del medio? Investiga `preg_replace('/\s+/', ' ', ...)`.

---

## 2. Estadísticas de una frase

Dada `$frase = "La programación en PHP es muy útil"`, muestra:

- Número de caracteres (con tildes contadas como una letra)
- Número de palabras
- La frase en MAYÚSCULAS
- La frase al revés

**Salida esperada (parcial):** `Caracteres: 34` — `Palabras: 7`

**Pista:** `mb_strlen`, `explode(' ', ...)` + `count`, `mb_strtoupper`.

---

## 3. Iniciales

Crea la función `iniciales(string $nombre): string` que reciba un nombre completo
y devuelva sus iniciales separadas por punto.

```php
echo iniciales("Ana María López Restrepo"); // A.M.L.R.
```

**Pista:** `explode`, un `foreach` y `mb_substr($palabra, 0, 1)`.

---

## 4. Código de producto

Una tienda identifica sus productos con el formato `PROD-00042` (siempre 5 dígitos).

1. Escribe una función que reciba el número `42` y devuelva `PROD-00042`.
2. Escribe otra que haga lo contrario: reciba `PROD-00042` y devuelva el entero `42`.

**Pista:** `sprintf('%05d', ...)`, `substr` o `str_replace` y `(int)`.

---

## 5. Censurar palabras

Crea `censurar(string $texto, array $prohibidas): string`. Cada palabra prohibida debe
reemplazarse por asteriscos **del mismo largo**, sin importar mayúsculas.

```php
echo censurar("Eres un TONTO y muy feo", ["tonto", "feo"]);
// Eres un ***** y muy ***
```

**Pista:** `str_ireplace` dentro de un `foreach`, `str_repeat('*', strlen($palabra))`.

---

## 6. ¿Es palíndromo?

Un palíndromo se lee igual al derecho y al revés (sin contar espacios ni mayúsculas).
Crea `esPalindromo(string $texto): bool` y pruébala con:

| Texto | Resultado |
|---|---|
| `Anita lava la tina` | sí |
| `Somos o no somos` | sí |
| `Hola mundo` | no |

**Pista:** `str_replace(' ', '', ...)`, `strtolower`, `strrev` y comparar.

---

## 7. Desarmar un correo

Dado `$correo = "laura.gomez@sena.edu.co"`, muestra:

- Usuario: `laura.gomez`
- Dominio: `sena.edu.co`
- ¿Es institucional? (termina en `sena.edu.co`): `Sí`

Luego valida el correo con `filter_var($correo, FILTER_VALIDATE_EMAIL)` y prueba con uno inválido como `laura@`.

**Pista:** `explode('@', ...)` o `strstr`, `str_ends_with`.

---

## 8. Factura alineada

Con este array imprime una factura con columnas alineadas dentro de una etiqueta `<pre>`.
Los precios van en formato colombiano.

```php
$items = [
    ['producto' => 'Teclado',   'cantidad' => 2, 'precio' => 45000],
    ['producto' => 'Mouse',     'cantidad' => 3, 'precio' => 18500.5],
    ['producto' => 'Monitor',   'cantidad' => 1, 'precio' => 650000],
];
```

**Salida esperada:**

```text
PRODUCTO        CANT        SUBTOTAL
------------------------------------
Teclado            2        $ 90.000
Mouse              3        $ 55.502
Monitor            1       $ 650.000
------------------------------------
TOTAL                      $ 795.502
```

**Pista:** `str_pad` (con `STR_PAD_LEFT` para los números) y `number_format($n, 0, ',', '.')`.

---

## 9. De CSV a tabla HTML

Convierte este texto en una tabla HTML. La primera línea son los encabezados.

```php
$csv = "nombre,edad,ciudad
Ana,25,Medellín
Luis,31,Itagüí
<b>Pedro</b>,28,Envigado";
```

La fila de Pedro trae HTML a propósito: debe verse el texto `<b>Pedro</b>`, **no** en negrita.

**Pista:** `explode("\n", ...)` para las líneas, `explode(',', ...)` para las columnas, `htmlspecialchars`.

---

## 10. Generador de slugs

Las URL amigables (como las de un blog) usan *slugs*. Crea `slug(string $titulo): string`:

```php
echo slug("¡Hola Mundo! Aprendiendo PHP 8 en Itagüí");
// hola-mundo-aprendiendo-php-8-en-itagui
```

Reglas: minúsculas, sin tildes (`á→a`, `ñ→n`, `ü→u`), cualquier cosa que no sea letra o número
se convierte en `-`, sin guiones repetidos ni guiones al inicio o al final.

**Pista:** `strtr` con un array de reemplazos para las tildes, `preg_replace('/[^a-z0-9]+/', '-', ...)`, `trim($s, '-')`.

> **Puente a Laravel:** Laravel trae esto hecho: `Str::slug()`. Ahora sabrás lo que hace por dentro.

---

## 11. Validar contraseña segura

Crea `validarPassword(string $pass): array` que devuelva un array con **todos** los errores encontrados
(vacío si la contraseña es válida). Reglas:

- Mínimo 8 caracteres
- Al menos una mayúscula
- Al menos una minúscula
- Al menos un número
- No puede contener la palabra `password` (sin importar mayúsculas)

Prueba con: `abc`, `password123A`, `Sena2026segura`.

**Pista:** `preg_match('/[A-Z]/', $pass)`, `stripos`.

---

## 12. Cifrado César

El cifrado César desplaza cada letra `n` posiciones en el alfabeto (`A` con desplazamiento 3 → `D`;
`Z` con desplazamiento 3 → `C`). Crea:

- `cifrar(string $texto, int $n): string`
- `descifrar(string $texto, int $n): string`

Mantén mayúsculas y minúsculas, y deja igual lo que no sea letra (espacios, números, signos).

```php
echo cifrar("Hola Mundo!", 3);     // Krod Pxqgr!
echo descifrar("Krod Pxqgr!", 3);  // Hola Mundo!
```

**Pista:** `ord()` convierte una letra en su código, `chr()` hace lo contrario. El operador `%` te ayuda a "dar la vuelta" después de la `Z`.
