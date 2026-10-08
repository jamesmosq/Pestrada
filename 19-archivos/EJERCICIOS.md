# Ejercicios — 19 Archivos

Antes de empezar, repasa `1.php` (file_put_contents / file_get_contents), `2.php` (fopen / fgets)
y `3.php` (carpetas).

**Cómo trabajar:** carpeta `ejercicios/`, un archivo por ejercicio. Los archivos que creen tus scripts
(`.txt`, `.csv`, `.json`) quedarán dentro de `ejercicios/`.

| Nivel | Ejercicios |
|---|---|
| Básico | 1 – 4 |
| Intermedio | 5 – 9 |
| Reto | 10 – 12 |

> **Ruta segura:** usa `__DIR__ . '/archivo.txt'` en lugar de `'archivo.txt'`. Así el archivo
> siempre queda junto a tu script, sin importar desde dónde se ejecute.

---

## 1. Mis notas

Guarda este array en `notas.txt`, una nota por línea. Luego léelo y muéstralo numerado.

```php
$notas = ["Repasar strings", "Hacer taller de fechas", "Instalar Composer"];
```

**Salida esperada:**

```text
1. Repasar strings
2. Hacer taller de fechas
3. Instalar Composer
```

**Pista:** `implode(PHP_EOL, $notas)` para guardar, `file(..., FILE_IGNORE_NEW_LINES)` para leer.

---

## 2. Contador de visitas

Cada vez que se recargue la página, el número debe aumentar en uno:
*"Esta página ha sido visitada 7 veces"*. El número se guarda en `contador.txt`.

**Pista:** si el archivo no existe, empieza en 0. Recuerda convertir lo leído con `(int)`.

---

## 3. Estadísticas de un texto

Crea `poema.txt` con un poema corto (mínimo 4 líneas) y muestra:

- Número de líneas
- Número de palabras
- Número de caracteres
- La línea más larga

**Pista:** `file()` para las líneas, `str_word_count` o `explode` para las palabras, `mb_strlen` para los caracteres.

---

## 4. Leer sin romper nada

Crea `leerSeguro(string $ruta): string` que devuelva el contenido del archivo, o uno de estos mensajes:

- `"El archivo X no existe"`
- `"X es una carpeta, no un archivo"`
- `"El archivo X está vacío"`

Pruébala con un archivo que exista, uno que no, una carpeta y un archivo vacío.

**Pista:** `file_exists`, `is_dir`, `filesize`.

---

## 5. Libro de visitas (página web)

Haz una página con un formulario (nombre y mensaje). Al enviarlo:

1. Guarda una línea en `visitas.txt` con el formato `fecha|nombre|mensaje`.
2. Debajo del formulario, muestra todos los mensajes **del más nuevo al más viejo**.

Reglas:

- Valida que ningún campo esté vacío.
- Quita los caracteres `|` y los saltos de línea de lo que escriba el usuario (romperían tu formato).
- Al mostrar, usa `htmlspecialchars`. Prueba escribiendo `<script>alert(1)</script>` como mensaje.
- Después de guardar, redirige a la misma página (`header('Location: ...')`) para que **F5** no duplique el mensaje.

---

## 6. Leer un CSV

Crea `estudiantes.csv`:

```text
nombre,edad,ficha
Ana Gómez,22,2758634
Luis Pérez,19,2758634
Marta Ruiz,25,2812045
Juan Díaz,31,2812045
```

Léelo con `fgetcsv` y muéstralo en una tabla HTML. Debajo muestra la **edad promedio** y
**cuántos estudiantes hay por ficha**.

**Salida esperada (final):** `Edad promedio: 24,3` — `Ficha 2758634: 2 estudiantes` — `Ficha 2812045: 2 estudiantes`

---

## 7. Exportar a CSV

Al revés que el anterior: a partir de este array genera `reporte.csv` con `fputcsv`.

```php
$ventas = [
    ['producto' => 'Teclado', 'cantidad' => 2, 'total' => 90000],
    ['producto' => 'Mouse, inalámbrico', 'cantidad' => 3, 'total' => 55500],
];
```

1. Ábrelo con el Bloc de notas: ¿qué hizo `fputcsv` con la coma de `"Mouse, inalámbrico"`?
2. Ábrelo con Excel. Si las tildes salen raras o todo queda en una columna, investiga cómo se soluciona
   (pistas: *BOM UTF-8* y el separador `;`).

---

## 8. Inventario en JSON

Crea `productos.json` con 2 productos (`id`, `nombre`, `precio`). Luego escribe un script que:

1. Lea el JSON y lo convierta en array.
2. Agregue un producto nuevo con `id` = el mayor id + 1.
3. Suba 10 % el precio de todos.
4. Guarde el JSON **legible** (con saltos de línea) y con las tildes sin escapar (`"Ratón"`, no `"Ratón"`).
5. Muestre la tabla final.

**Pista:** `json_decode($texto, true)`, `json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)`, `max(array_column(...))`.

---

## 9. Mi propio logger

Crea `registrarLog(string $nivel, string $mensaje): void` que agregue al archivo `logs/app.log` una línea como:

```text
[2026-10-07 14:30:12] ERROR: No se pudo conectar a la base de datos
```

- Si la carpeta `logs/` no existe, créala.
- Solo acepta los niveles `INFO`, `WARNING` y `ERROR` (cualquier otro lanza `InvalidArgumentException`).
- Escribe además `ultimasLineas(string $archivo, int $n): array` y muestra las últimas 5 líneas del log.

> **Puente a Laravel:** En Laravel: `Log::error('mensaje')` escribe en `storage/logs/laravel.log`. Es lo mismo.

---

## 10. Explorador de carpeta

Escribe una página que liste el contenido de una carpeta (por ejemplo, la carpeta del curso `../..`):

- Primero las carpetas y luego los archivos, cada grupo en orden alfabético.
- Tamaño legible: `512 B`, `3,4 KB`, `1,2 MB`.
- Fecha de modificación `dd/mm/aaaa HH:mm`.
- Al final: total de archivos y suma de sus tamaños.

**Pista:** `scandir`, `is_dir`, `filesize`, `filemtime`, `usort`. Escribe una función `tamanoLegible(int $bytes): string`.

---

## 11. Subir archivos (página web)

Haz un formulario para subir un archivo (`<form ... enctype="multipart/form-data">`). Validaciones:

| Regla | Mensaje |
|---|---|
| Se eligió un archivo | "Selecciona un archivo" |
| Extensión `jpg`, `png` o `pdf` | "Solo se permiten JPG, PNG o PDF" |
| Tipo real del contenido (no confiar en la extensión) | "El contenido no coincide con la extensión" |
| Máximo 2 MB | "El archivo supera los 2 MB" |

Guárdalo en `uploads/` con un nombre único (para que dos archivos `foto.jpg` no se pisen) y muestra la lista de
archivos subidos.

**Pista:** `$_FILES['archivo']`, `pathinfo(..., PATHINFO_EXTENSION)`, `finfo_file` / `mime_content_type`,
`move_uploaded_file`, `uniqid()`.

**Prueba de seguridad:** renombra un `.txt` a `foto.jpg` e intenta subirlo. Debe ser rechazado.

---

## 12. Agenda de contactos (CRUD con un archivo JSON)

Antes de usar MySQL, las aplicaciones guardaban datos en archivos. Construye una agenda en `contactos.json` con estas funciones:

```php
cargar(): array
guardar(array $contactos): void
agregar(string $nombre, string $telefono): int      // devuelve el id nuevo
eliminar(int $id): bool                              // false si no existía
buscar(string $texto): array                         // por nombre, sin importar mayúsculas
```

Demuéstralas: agrega 3 contactos, busca uno, elimina otro y muestra la lista final.

**Extra:** conviértelo en página web con formulario para agregar y botón para eliminar (por POST).

> **Puente a Laravel:** Fíjate: son las mismas 4 operaciones del CRUD de `15-Proyectos` y `21-sweetAlert2`,
> solo que guardando en un archivo en vez de MySQL. Laravel también permite cambiar *dónde* se guardan los datos sin cambiar el resto del código.
