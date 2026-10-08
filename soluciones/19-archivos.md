# Soluciones — 19 Archivos

> **Antes de mirar:** intenta el ejercicio por tu cuenta al menos 20 minutos. Si te bloqueas, lee solo
> la pista del enunciado y vuelve a intentarlo. Cuando lo termines (o si de verdad no sale), compara con esta
> solución: es **una** forma de resolverlo, no la única.

---

## 1. Mis notas

```php
<?php
$notas   = ["Repasar strings", "Hacer taller de fechas", "Instalar Composer"];
$archivo = __DIR__ . '/notas.txt';

file_put_contents($archivo, implode(PHP_EOL, $notas));

$lineas = file($archivo, FILE_IGNORE_NEW_LINES);
foreach ($lineas as $i => $nota) {
    echo ($i + 1) . ". $nota<br>";
}
```

---

## 2. Contador de visitas

```php
<?php
$archivo = __DIR__ . '/contador.txt';

$visitas = file_exists($archivo) ? (int) file_get_contents($archivo) : 0;
$visitas++;
file_put_contents($archivo, $visitas);

echo "Esta página ha sido visitada $visitas " . ($visitas === 1 ? 'vez' : 'veces');
```

**Para entender:** si dos personas entran exactamente al mismo tiempo, las dos pueden leer "6" y escribir "7".
Se soluciona con `flock()` o, mejor, con una base de datos.

---

## 3. Estadísticas de un texto

```php
<?php
$archivo = __DIR__ . '/poema.txt';

file_put_contents($archivo, "Caminante, son tus huellas
el camino y nada más;
caminante, no hay camino,
se hace camino al andar.");

$lineas    = file($archivo, FILE_IGNORE_NEW_LINES);
$contenido = file_get_contents($archivo);

$masLarga = '';
foreach ($lineas as $linea) {
    if (mb_strlen($linea) > mb_strlen($masLarga)) {
        $masLarga = $linea;
    }
}

echo "Líneas: "      . count($lineas) . "<br>";
echo "Palabras: "    . count(preg_split('/\s+/', trim($contenido))) . "<br>";
echo "Caracteres: "  . mb_strlen($contenido) . "<br>";
echo "Más larga: \"$masLarga\"<br>";
```

---

## 4. Leer sin romper nada

```php
<?php
function leerSeguro(string $ruta): string
{
    if (!file_exists($ruta)) {
        return "El archivo $ruta no existe";
    }
    if (is_dir($ruta)) {
        return "$ruta es una carpeta, no un archivo";
    }
    if (filesize($ruta) === 0) {
        return "El archivo $ruta está vacío";
    }
    return file_get_contents($ruta);
}

file_put_contents(__DIR__ . '/lleno.txt', 'Hola');
file_put_contents(__DIR__ . '/vacio.txt', '');

foreach (['lleno.txt', 'no_existe.txt', '.', 'vacio.txt'] as $nombre) {
    echo leerSeguro(__DIR__ . "/$nombre") . "<br>";
}
```

---

## 5. Libro de visitas (página web)

```php
<?php
date_default_timezone_set('America/Bogota');

$archivo = __DIR__ . '/visitas.txt';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Quitar saltos de línea y "|" para no romper el formato del archivo
    $limpiar = fn(string $s) => trim(str_replace(["\r", "\n", "|"], ' ', $s));

    $nombre  = $limpiar($_POST['nombre']  ?? '');
    $mensaje = $limpiar($_POST['mensaje'] ?? '');

    if ($nombre === '' || $mensaje === '') {
        $error = 'Escribe tu nombre y un mensaje.';
    } else {
        $linea = date('Y-m-d H:i') . "|$nombre|$mensaje" . PHP_EOL;
        file_put_contents($archivo, $linea, FILE_APPEND);

        // Patrón PRG (Post/Redirect/Get): así F5 no reenvía el formulario
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

$visitas = file_exists($archivo) ? file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$visitas = array_reverse($visitas); // la más nueva primero
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libro de visitas</title>
</head>
<body>
    <h1>Libro de visitas</h1>

    <?php if ($error): ?>
        <p style="color:#c0392b"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Tu nombre"><br><br>
        <textarea name="mensaje" rows="3" cols="40" placeholder="Tu mensaje"></textarea><br>
        <button type="submit">Firmar</button>
    </form>

    <hr>

    <?php foreach ($visitas as $visita): ?>
        <?php [$fecha, $nombre, $mensaje] = explode('|', $visita, 3); ?>
        <p>
            <strong><?= htmlspecialchars($nombre) ?></strong>
            <small>(<?= htmlspecialchars($fecha) ?>)</small><br>
            <?= htmlspecialchars($mensaje) ?>
        </p>
    <?php endforeach; ?>
</body>
</html>
```

**Para entender:** `fn(string $s) => ...` es una *arrow function*, como `lambda s: ...` en Python.
`<?= ... ?>` es la forma corta de `<?php echo ... ?>`; en Blade se escribe `{{ ... }}` y escapa solo.

---

## 6. Leer un CSV

```php
<?php
$archivo = __DIR__ . '/estudiantes.csv';

file_put_contents($archivo, "nombre,edad,ficha
Ana Gómez,22,2758634
Luis Pérez,19,2758634
Marta Ruiz,25,2812045
Juan Díaz,31,2812045
");

$f           = fopen($archivo, 'r');
$encabezados = fgetcsv($f);
$filas       = [];

while (($fila = fgetcsv($f)) !== false) {
    $filas[] = array_combine($encabezados, $fila); // ['nombre' => 'Ana', 'edad' => '22', ...]
}
fclose($f);

echo "<table border='1' cellpadding='6'><tr>";
foreach ($encabezados as $titulo) {
    echo "<th>" . htmlspecialchars(ucfirst($titulo)) . "</th>";
}
echo "</tr>";
foreach ($filas as $fila) {
    echo "<tr>";
    foreach ($fila as $valor) {
        echo "<td>" . htmlspecialchars($valor) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

$edades   = array_column($filas, 'edad');
$promedio = array_sum($edades) / count($edades);
echo "Edad promedio: " . number_format($promedio, 1, ',', '.') . "<br>";

$porFicha = array_count_values(array_column($filas, 'ficha'));
foreach ($porFicha as $ficha => $cantidad) {
    echo "Ficha $ficha: $cantidad estudiantes<br>";
}
```

**Para entender:** `array_column` y `array_count_values` reemplazan ciclos enteros. Son muy parecidas a lo que
harían en Python con comprensiones de listas o `collections.Counter`.

---

## 7. Exportar a CSV

```php
<?php
$ventas = [
    ['producto' => 'Teclado', 'cantidad' => 2, 'total' => 90000],
    ['producto' => 'Mouse, inalámbrico', 'cantidad' => 3, 'total' => 55500],
];

$f = fopen(__DIR__ . '/reporte.csv', 'w');

fwrite($f, "\xEF\xBB\xBF");                        // BOM: Excel reconoce las tildes (UTF-8)
fputcsv($f, array_keys($ventas[0]), ';');          // encabezados
foreach ($ventas as $venta) {
    fputcsv($f, $venta, ';');                      // ";" = separador que Excel en español espera
}
fclose($f);

echo "<pre>" . htmlspecialchars(file_get_contents(__DIR__ . '/reporte.csv')) . "</pre>";
```

**Respuestas:** 1) `fputcsv` encierra el valor entre comillas: `"Mouse, inalámbrico"`, para que la coma no se tome
como separador. 2) Excel en configuración regional de Colombia usa `;` como separador de listas, y sin BOM abre el
archivo como ANSI (las tildes salen como `Ã¡`).

---

## 8. Inventario en JSON

```php
<?php
$archivo = __DIR__ . '/productos.json';

// Datos iniciales (solo la primera vez)
if (!file_exists($archivo)) {
    file_put_contents($archivo, json_encode([
        ['id' => 1, 'nombre' => 'Teclado', 'precio' => 45000],
        ['id' => 2, 'nombre' => 'Ratón',   'precio' => 18000],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// 1. Leer
$productos = json_decode(file_get_contents($archivo), true);

// 2. Agregar con id = mayor + 1
$nuevoId     = max(array_column($productos, 'id')) + 1;
$productos[] = ['id' => $nuevoId, 'nombre' => 'Monitor', 'precio' => 650000];

// 3. Subir 10 %
foreach ($productos as &$p) {     // & para modificar el elemento original
    $p['precio'] = round($p['precio'] * 1.10);
}
unset($p);                        // buena práctica después de un foreach por referencia

// 4. Guardar legible y con tildes
file_put_contents($archivo, json_encode($productos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 5. Mostrar
foreach ($productos as $p) {
    echo "{$p['id']}. {$p['nombre']} — $ " . number_format($p['precio'], 0, ',', '.') . "<br>";
}
echo "<pre>" . file_get_contents($archivo) . "</pre>";
```

**Para entender:** cada vez que se ejecute, agrega otro Monitor y vuelve a subir los precios: el archivo
*guarda estado* entre ejecuciones, igual que una base de datos.

---

## 9. Mi propio logger

```php
<?php
function registrarLog(string $nivel, string $mensaje): void
{
    $nivel     = strtoupper($nivel);
    $permitidos = ['INFO', 'WARNING', 'ERROR'];

    if (!in_array($nivel, $permitidos)) {
        throw new InvalidArgumentException("Nivel de log no válido: $nivel");
    }

    $carpeta = __DIR__ . '/logs';
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    $linea = '[' . date('Y-m-d H:i:s') . "] $nivel: $mensaje" . PHP_EOL;
    file_put_contents("$carpeta/app.log", $linea, FILE_APPEND);
}

function ultimasLineas(string $archivo, int $n): array
{
    if (!file_exists($archivo)) {
        return [];
    }
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES);
    return array_slice($lineas, -$n);
}

date_default_timezone_set('America/Bogota');

registrarLog('info', 'Usuario admin inició sesión');
registrarLog('WARNING', 'Intento de acceso con contraseña incorrecta');
registrarLog('ERROR', 'No se pudo conectar a la base de datos');

try {
    registrarLog('PANICO', 'esto no debería guardarse');
} catch (InvalidArgumentException $e) {
    echo "[ERROR] " . $e->getMessage() . "<br><hr>";
}

foreach (ultimasLineas(__DIR__ . '/logs/app.log', 5) as $linea) {
    echo htmlspecialchars($linea) . "<br>";
}
```

---

## 10. Explorador de carpeta

```php
<?php
function tamanoLegible(int $bytes): string
{
    $unidades = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    $valor = $bytes;
    while ($valor >= 1024 && $i < count($unidades) - 1) {
        $valor /= 1024;
        $i++;
    }
    return ($i === 0 ? $valor : number_format($valor, 1, ',', '.')) . ' ' . $unidades[$i];
}

$carpeta = __DIR__;   // cambia por la ruta que quieras explorar
$items   = [];

foreach (scandir($carpeta) as $nombre) {
    if ($nombre === '.' || $nombre === '..') {
        continue;
    }
    $ruta    = "$carpeta/$nombre";
    $items[] = [
        'nombre'  => $nombre,
        'carpeta' => is_dir($ruta),
        'tamano'  => is_dir($ruta) ? 0 : filesize($ruta),
        'fecha'   => filemtime($ruta),
    ];
}

// Carpetas primero; dentro de cada grupo, orden alfabético
usort($items, function ($a, $b) {
    if ($a['carpeta'] !== $b['carpeta']) {
        return $a['carpeta'] ? -1 : 1;
    }
    return strcasecmp($a['nombre'], $b['nombre']);
});

$totalArchivos = 0;
$totalBytes    = 0;

echo "<table border='1' cellpadding='6'><tr><th>Nombre</th><th>Tamaño</th><th>Modificado</th></tr>";
foreach ($items as $item) {
    echo "<tr>";
    echo "<td>" . ($item['carpeta'] ? '[carpeta] ' : '[archivo] ') . htmlspecialchars($item['nombre']) . "</td>";
    echo "<td>" . ($item['carpeta'] ? '—' : tamanoLegible($item['tamano'])) . "</td>";
    echo "<td>" . date('d/m/Y H:i', $item['fecha']) . "</td>";
    echo "</tr>";

    if (!$item['carpeta']) {
        $totalArchivos++;
        $totalBytes += $item['tamano'];
    }
}
echo "</table>";
echo "<p>$totalArchivos archivos — " . tamanoLegible($totalBytes) . "</p>";

echo tamanoLegible(512) . " | " . tamanoLegible(3482) . " | " . tamanoLegible(1258291);
// 512 B | 3,4 KB | 1,2 MB
```

**Para entender:** `usort` recibe una función que compara dos elementos y devuelve negativo, 0 o positivo.
En Python sería `sorted(items, key=...)`.

---

## 11. Subir archivos (página web)

```php
<?php
$carpeta  = __DIR__ . '/uploads';
$mensaje  = '';
$tipo     = '';

// extensión permitida => tipo MIME real esperado
$permitidos = [
    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'pdf' => 'application/pdf',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archivo = $_FILES['archivo'] ?? null;

    if (!$archivo || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
        $mensaje = 'Selecciona un archivo';
    } elseif ($archivo['error'] === UPLOAD_ERR_INI_SIZE) {
        // php.ini (upload_max_filesize) ya lo rechazó antes de llegar a nuestro código
        $mensaje = 'El archivo supera los 2 MB';
    } elseif ($archivo['error'] !== UPLOAD_ERR_OK) {
        $mensaje = 'Error al subir el archivo (código ' . $archivo['error'] . ')';
    } else {
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $mimeReal  = mime_content_type($archivo['tmp_name']); // mira el CONTENIDO, no el nombre

        if (!array_key_exists($extension, $permitidos)) {
            $mensaje = 'Solo se permiten JPG, PNG o PDF';
        } elseif ($mimeReal !== $permitidos[$extension]) {
            $mensaje = 'El contenido no coincide con la extensión';
        } elseif ($archivo['size'] > 2 * 1024 * 1024) {
            $mensaje = 'El archivo supera los 2 MB';
        } else {
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $nombreNuevo = uniqid('archivo_', true) . '.' . $extension;
            move_uploaded_file($archivo['tmp_name'], "$carpeta/$nombreNuevo");

            $mensaje = 'Archivo subido como ' . $nombreNuevo;
            $tipo    = 'ok';
        }
    }
}

$subidos = is_dir($carpeta) ? array_diff(scandir($carpeta), ['.', '..']) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir archivos</title>
</head>
<body>
    <h1>Subir archivo</h1>

    <?php if ($mensaje): ?>
        <p style="color: <?= $tipo === 'ok' ? '#27ae60' : '#c0392b' ?>"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <!-- Sin enctype="multipart/form-data" el archivo NO se envía -->
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="archivo" accept=".jpg,.png,.pdf">
        <button type="submit">Subir</button>
    </form>

    <h2>Archivos subidos</h2>
    <ul>
        <?php foreach ($subidos as $nombre): ?>
            <li><?= htmlspecialchars($nombre) ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
```

**Para entender:**
- Nunca se guarda con el nombre original: podría llamarse `../../index.php` o pisar otro archivo.
- El `accept` del input solo es comodidad; quien quiera saltarlo, lo salta. La validación real es la del servidor.
- PHP tiene su propio límite: `upload_max_filesize` en `php.ini` (en WAMP viene en 2M). Si el archivo lo supera,
  llega con `error = UPLOAD_ERR_INI_SIZE` y nuestra validación de tamaño nunca se ejecuta; por eso se revisa ese código aparte.

---

## 12. Agenda de contactos (CRUD con un archivo JSON)

```php
<?php
const ARCHIVO = __DIR__ . '/contactos.json';

function cargar(): array
{
    if (!file_exists(ARCHIVO)) {
        return [];
    }
    return json_decode(file_get_contents(ARCHIVO), true) ?? [];
}

function guardar(array $contactos): void
{
    file_put_contents(ARCHIVO, json_encode(array_values($contactos), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function agregar(string $nombre, string $telefono): int
{
    $contactos = cargar();
    $id        = $contactos ? max(array_column($contactos, 'id')) + 1 : 1;

    $contactos[] = ['id' => $id, 'nombre' => $nombre, 'telefono' => $telefono];
    guardar($contactos);
    return $id;
}

function eliminar(int $id): bool
{
    $contactos = cargar();
    $filtrados = array_filter($contactos, fn($c) => $c['id'] !== $id);

    if (count($filtrados) === count($contactos)) {
        return false; // no existía
    }
    guardar($filtrados);
    return true;
}

function buscar(string $texto): array
{
    return array_values(array_filter(
        cargar(),
        fn($c) => stripos($c['nombre'], $texto) !== false
    ));
}

// ── Demostración ──────────────────────────────────────────────
@unlink(ARCHIVO); // empezar limpio para la demo

$idAna  = agregar('Ana Gómez', '3001234567');
$idLuis = agregar('Luis Pérez', '3109876543');
$idAnd  = agregar('Andrés Ríos', '3205551234');

echo "Buscar 'an': ";
foreach (buscar('an') as $c) {
    echo $c['nombre'] . "; ";
}
echo "<br>";

echo "Eliminar Luis: " . (eliminar($idLuis) ? 'sí' : 'no') . "<br>";
echo "Eliminar id 99: " . (eliminar(99) ? 'sí' : 'no') . "<br><hr>";

foreach (cargar() as $c) {
    echo "#{$c['id']} {$c['nombre']} — {$c['telefono']}<br>";
}
```

**Para entender:** `array_values` después de `array_filter` reinicia los índices (0, 1, 2…). Sin él, el JSON se
guardaría como objeto `{"0": ..., "2": ...}` en vez de lista. `array_filter` + `fn` es el equivalente de
`[c for c in contactos if ...]` en Python.
