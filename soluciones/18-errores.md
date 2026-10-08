# Soluciones — 18 Manejo de errores

> **Antes de mirar:** intenta el ejercicio por tu cuenta al menos 20 minutos. Si te bloqueas, lee solo
> la pista del enunciado y vuelve a intentarlo. Cuando lo termines (o si de verdad no sale), compara con esta
> solución: es **una** forma de resolverlo, no la única.

---

## 1. División segura

```php
<?php
$operaciones = [[10, 2], [7, 0], [9, 3], [5, 0]];

foreach ($operaciones as [$a, $b]) {
    try {
        echo "$a / $b = " . ($a / $b) . "<br>";
    } catch (DivisionByZeroError $e) {
        echo "$a / $b → No se puede dividir entre cero<br>";
    }
}
```

**Para entender:** si el `try` envuelve todo el `foreach`, al fallar `7 / 0` el ciclo se corta y
`9 / 3` nunca se calcula. `foreach ($x as [$a, $b])` desestructura cada par.

---

## 2. Validar edades

```php
<?php
function validarEdad(int $edad): int
{
    if ($edad < 0 || $edad > 120) {
        throw new InvalidArgumentException("La edad $edad no es válida");
    }
    return $edad;
}

$edades  = [25, -3, 130, 0, 80];
$validas = 0;

foreach ($edades as $edad) {
    try {
        validarEdad($edad);
        echo "[OK] $edad<br>";
        $validas++;
    } catch (InvalidArgumentException $e) {
        echo "[ERROR] " . $e->getMessage() . "<br>";
    }
}

echo "Edades válidas: $validas de " . count($edades);
```

---

## 3. Convertir texto a número

```php
<?php
function aEntero(string $valor): int
{
    $numero = filter_var(trim($valor), FILTER_VALIDATE_INT);

    if ($numero === false) {
        throw new InvalidArgumentException("'$valor' no es un número entero");
    }
    return $numero;
}

foreach (["42", "  7 ", "3.5", "abc", "", "0"] as $prueba) {
    try {
        echo "\"$prueba\" → " . aEntero($prueba) . "<br>";
    } catch (InvalidArgumentException $e) {
        echo "\"$prueba\" → Error: " . $e->getMessage() . "<br>";
    }
}
```

**Para entender:** con `if (!$numero)` el `"0"` se rechazaría por error. Por eso `=== false`.

---

## 4. ¿Cuándo se ejecuta finally?

```php
<?php
function probar(int $n): string {
    try {
        echo "Inicio $n | ";
        if ($n === 2) {
            throw new RuntimeException("falló");
        }
        return "retorno normal";
    } catch (RuntimeException $e) {
        return "retorno desde catch";
    } finally {
        echo "finally $n | ";
    }
}

echo probar(1) . "<br>";
echo probar(2) . "<br>";
```

**Salida:**

```text
Inicio 1 | finally 1 | retorno normal
Inicio 2 | finally 2 | retorno desde catch
```

**Explicación:** `finally` se ejecuta **antes** de que la función devuelva su valor, incluso si hay un `return`
dentro del `try` o del `catch`. Por eso "finally" aparece antes que el texto retornado. Es el lugar para cerrar
archivos o conexiones: se ejecuta pase lo que pase.

---

## 5. Producto agotado

```php
<?php
class ProductoAgotadoException extends Exception
{
    private string $producto;

    public function __construct(string $producto)
    {
        $this->producto = $producto;
        parent::__construct("No hay unidades suficientes de \"$producto\".");
    }

    public function getProducto(): string
    {
        return $this->producto;
    }
}

function vender(array &$inventario, string $producto, int $cantidad): void
{
    if (($inventario[$producto] ?? 0) < $cantidad) {
        throw new ProductoAgotadoException($producto);
    }
    $inventario[$producto] -= $cantidad;
}

$inventario = ['teclado' => 5, 'mouse' => 0];

try {
    vender($inventario, 'teclado', 2);
    echo "Venta OK. Quedan {$inventario['teclado']} teclados.<br>";

    vender($inventario, 'mouse', 1);
    echo "Esta línea no se imprime.<br>";
} catch (ProductoAgotadoException $e) {
    echo $e->getMessage() . " Producto: " . $e->getProducto() . "<br>";
}
```

**Para entender:** el `&` en `array &$inventario` pasa el array **por referencia**: la función modifica el original.
Sin `&`, PHP trabajaría con una copia (en Python las listas siempre se pasan por referencia).

---

## 6. Familia de excepciones de validación

```php
<?php
class ValidacionException extends Exception {}
class CampoVacioException extends ValidacionException {}
class EmailInvalidoException extends ValidacionException {}

function validarUsuario(array $datos): void
{
    foreach (['nombre', 'email'] as $campo) {
        if (empty(trim($datos[$campo] ?? ''))) {
            throw new CampoVacioException("El campo '$campo' es obligatorio.");
        }
    }
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        throw new EmailInvalidoException("El email '{$datos['email']}' no es válido.");
    }
}

$pruebas = [
    ['nombre' => 'Ana', 'email' => 'ana@sena.edu.co'],
    ['nombre' => '',    'email' => 'x@x.co'],
    ['nombre' => 'Luis', 'email' => 'luis@'],
];

foreach ($pruebas as $datos) {
    try {
        validarUsuario($datos);
        echo "[OK] {$datos['nombre']} es válido<br>";
    } catch (ValidacionException $e) {   // atrapa las dos hijas
        echo "[ERROR] [" . get_class($e) . "] " . $e->getMessage() . "<br>";
    }
}
```

---

## 7. Reunir todos los errores

```php
<?php
function validarEdad(int $edad): int
{
    if ($edad < 0 || $edad > 120) {
        throw new InvalidArgumentException("La edad $edad no es válida");
    }
    return $edad;
}

function aEntero(string $valor): int
{
    $numero = filter_var(trim($valor), FILTER_VALIDATE_INT);
    if ($numero === false) {
        throw new InvalidArgumentException("'$valor' no es un número entero");
    }
    return $numero;
}

$registro = ['edad' => '150', 'hijos' => 'dos', 'año' => '2026'];
$errores  = [];

try {
    validarEdad(aEntero($registro['edad']));
} catch (InvalidArgumentException $e) {
    $errores['edad'] = $e->getMessage();
}

foreach (['hijos', 'año'] as $campo) {
    try {
        aEntero($registro[$campo]);
    } catch (InvalidArgumentException $e) {
        $errores[$campo] = $e->getMessage();
    }
}

if ($errores) {
    echo "Se encontraron " . count($errores) . " errores:<br>";
    foreach ($errores as $campo => $mensaje) {
        echo "- $campo: $mensaje<br>";
    }
} else {
    echo "Registro válido";
}
```

---

## 8. Cuenta bancaria

```php
<?php
class Cuenta
{
    private const LIMITE_DIARIO = 2000000;

    private float $saldo = 0;
    private float $retiradoHoy = 0;

    public function consignar(float $monto): void
    {
        if ($monto <= 0) {
            throw new InvalidArgumentException("El monto a consignar debe ser mayor que cero.");
        }
        $this->saldo += $monto;
    }

    public function retirar(float $monto): void
    {
        if ($monto <= 0) {
            throw new InvalidArgumentException("El monto a retirar debe ser mayor que cero.");
        }
        if ($monto > $this->saldo) {
            throw new RuntimeException("Saldo insuficiente.");
        }
        if ($this->retiradoHoy + $monto > self::LIMITE_DIARIO) {
            throw new RuntimeException("Supera el límite diario de retiro.");
        }
        $this->saldo       -= $monto;
        $this->retiradoHoy += $monto;
    }

    public function getSaldo(): float
    {
        return $this->saldo;
    }
}

$cuenta = new Cuenta();

$movimientos = [
    ['consignar', 3000000],
    ['retirar',   500000],
    ['retirar',   -100],
    ['retirar',   1800000],   // 500.000 + 1.800.000 supera el límite
    ['retirar',   1500000],
    ['retirar',   5000000],   // saldo insuficiente
    ['consignar', 0],
];

foreach ($movimientos as [$tipo, $monto]) {
    try {
        $cuenta->$tipo($monto); // llama al método cuyo nombre está en $tipo
        echo "[OK] $tipo $ " . number_format($monto, 0, ',', '.') . "<br>";
    } catch (InvalidArgumentException | RuntimeException $e) {
        echo "[ERROR] $tipo $ " . number_format($monto, 0, ',', '.') . " → " . $e->getMessage() . "<br>";
    }
}

echo "<strong>Saldo final: $ " . number_format($cuenta->getSaldo(), 0, ',', '.') . "</strong>";
```

**Para entender:** `catch (A | B $e)` atrapa dos tipos en un solo bloque.
`$cuenta->$tipo(...)` es el mismo truco que usa el router de `21-sweetAlert2`.

---

## 9. Re-lanzar con más contexto

```php
<?php
function leerConfiguracion(string $archivo): array
{
    try {
        if (!file_exists($archivo)) {
            throw new RuntimeException("El archivo '$archivo' no existe.");
        }
        $texto = file_get_contents($archivo);
        return json_decode($texto, true, 512, JSON_THROW_ON_ERROR);
    } catch (RuntimeException | JsonException $e) {
        // mensaje general + la excepción original como "previous"
        throw new RuntimeException("No se pudo cargar la configuración", 0, $e);
    }
}

file_put_contents('bueno.json', '{"app": "Pestrada", "debug": true}');
file_put_contents('malo.json',  '{"app": "Pestrada", debug: true');

foreach (['bueno.json', 'malo.json', 'no_existe.json'] as $archivo) {
    try {
        $config = leerConfiguracion($archivo);
        echo "[OK] $archivo: app = {$config['app']}<br>";
    } catch (RuntimeException $e) {
        echo "[ERROR] $archivo: " . $e->getMessage()
           . " <small>(detalle: " . $e->getPrevious()->getMessage() . ")</small><br>";
    }
}
```

---

## 10. Conexión a la base de datos sin sustos

```php
<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/errores.log');

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=sena_mvc;charset=utf8mb4', 'root', 'clave_incorrecta', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Conectado";
} catch (PDOException $e) {
    error_log("Fallo de conexión: " . $e->getMessage()); // detalle técnico -> archivo
    echo "El servicio no está disponible, intenta más tarde.";  // mensaje -> usuario
}

echo "<br>Contenido de errores.log:<br><pre>" . htmlspecialchars(file_get_contents(__DIR__ . '/errores.log')) . "</pre>";
```

**Respuesta a la parte 1:** sin `try/catch` aparece *Fatal error: Uncaught PDOException…* con la ruta completa del
archivo y la línea; con Xdebug se ve incluso la pila de llamadas. Esa información le sirve a un atacante.

---

## 11. Manejador global

```php
<?php
set_exception_handler(function (Throwable $e) {
    $codigo = 'ERR-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

    $linea = sprintf(
        "[%s] %s %s: %s en %s:%d\n",
        date('Y-m-d H:i:s'), $codigo, get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()
    );
    file_put_contents(__DIR__ . '/errores.log', $linea, FILE_APPEND);

    http_response_code(500);
    echo "<h2>Ups, algo salió mal</h2>";
    echo "<p>Ya registramos el problema. Si contactas a soporte, menciona el código <strong>$codigo</strong>.</p>";
});

// Ojo: si antes del error ya se imprimió algo (un echo), http_response_code()
// falla con "headers already sent". Los encabezados van SIEMPRE antes del contenido.
throw new LogicException("Este error nadie lo capturó");
echo "Esta línea no se ejecuta";
```

---

## 12. Convertir *warnings* en excepciones

```php
<?php
// 1. Sin manejador: es un warning, el catch NO lo atrapa
try {
    $contenido = @file_get_contents('no_existe.txt'); // @ oculta el warning
    var_dump($contenido); // bool(false)
    echo "<br>El catch no se ejecutó: no hubo excepción.<br>";
} catch (Throwable $e) {
    echo "Atrapado: " . $e->getMessage();
}

echo "<hr>";

// 2. Convertimos cualquier warning/notice en ErrorException
set_error_handler(function (int $nivel, string $mensaje, string $archivo, int $linea) {
    throw new ErrorException($mensaje, 0, $nivel, $archivo, $linea);
});

// 3. Ahora sí lo atrapa
try {
    $contenido = file_get_contents('no_existe.txt');
} catch (ErrorException $e) {
    echo "Atrapado como excepción: " . $e->getMessage() . "<br>";
}

restore_error_handler();
```

**Respuesta a la parte 4:** así hay **una sola** forma de manejar los problemas (excepciones) en vez de dos
(excepciones + warnings que se cuelan y dejan variables en `false`). Laravel lo hace por defecto: por eso en Laravel
un warning detiene la petición y muestra la página de error.
