# Soluciones — 20 Formularios y validación

> **Antes de mirar:** intenta el ejercicio por tu cuenta al menos 20 minutos. Si te bloqueas, lee solo
> la pista del enunciado y vuelve a intentarlo. Cuando lo termines (o si de verdad no sale), compara con esta
> solución: es **una** forma de resolverlo, no la única.
>
> Los archivos se suponen en `20-formularios-validacion/ejercicios/`.

---

## 1. GET contra POST

**`ej01.php`**

```php
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Contacto</title></head>
<body>
    <!-- Paso 1: method="GET". Paso 2: cambiar a method="POST" -->
    <form action="ej01_mostrar.php" method="POST">
        <input type="text"  name="nombre" placeholder="Nombre"><br><br>
        <input type="email" name="correo" placeholder="Correo"><br><br>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
```

**`ej01_mostrar.php`**

```php
<?php
// Con GET se lee $_GET; con POST, $_POST
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';

echo "Nombre: " . htmlspecialchars($nombre) . "<br>";
echo "Correo: " . htmlspecialchars($correo) . "<br>";

/*
 * - Con GET los datos se ven en la URL: ej01_mostrar.php?nombre=Ana&correo=ana%40x.co
 *   Con POST viajan en el cuerpo de la petición (se ven en F12 > Red, pero no en la URL).
 * - Login: POST. La contraseña no debe quedar en la URL, en el historial ni en los logs del servidor.
 * - Buscador: GET. Así la búsqueda se puede compartir, guardar en favoritos y recargar sin avisos.
 * - Con GET, el favorito guarda la URL completa: al abrirlo se vuelve a enviar la misma búsqueda.
 */
```

---

## 2. Todo en un solo archivo

```php
<?php
$nombre  = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre'] ?? '');
    $mensaje = $nombre === '' ? 'Escribe tu nombre' : "¡Hola, $nombre!";
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Saludo</title></head>
<body>
    <?php if ($mensaje): ?>
        <h2><?= htmlspecialchars($mensaje) ?></h2>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Tu nombre">
        <button type="submit">Saludar</button>
    </form>
</body>
</html>
```

---

## 3. Celular y cédula

```php
<?php
function limpiarNumero(string $numero): string
{
    return str_replace([' ', '.', '-'], '', trim($numero));
}

function validarCelular(string $numero): bool
{
    return preg_match('/^3\d{9}$/', limpiarNumero($numero)) === 1;
}

function validarCedula(string $numero): bool
{
    return preg_match('/^\d{6,10}$/', limpiarNumero($numero)) === 1;
}

$celulares = ['3001234567', '300 123 4567', '6044441234', '30012345'];
$cedulas   = ['1036123456', '98765', '10.361.234', 'ABC123'];

foreach ($celulares as $c) {
    echo "Celular $c: " . (validarCelular($c) ? 'sí' : 'no') . "<br>";
}
echo "<hr>";
foreach ($cedulas as $c) {
    echo "Cédula $c: " . (validarCedula($c) ? 'sí' : 'no') . "<br>";
}
```

**Para entender:** `^` y `$` obligan a que **todo** el texto cumpla el patrón. Sin ellos, `"abc3001234567xyz"`
pasaría porque *contiene* un celular. `\d{9}` = exactamente 9 dígitos.

---

## 4. Select con lista blanca

```php
<?php
$ciudades = ['medellin' => 'Medellín', 'itagui' => 'Itagüí', 'envigado' => 'Envigado', 'bello' => 'Bello'];
$mensaje  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ciudad = $_POST['ciudad'] ?? '';

    if (array_key_exists($ciudad, $ciudades)) {
        $mensaje = 'Elegiste: ' . $ciudades[$ciudad];
    } else {
        $mensaje = 'Ciudad no válida';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Ciudad</title></head>
<body>
    <?php if ($mensaje): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST">
        <select name="ciudad">
            <option value="">-- Elige una ciudad --</option>
            <?php foreach ($ciudades as $clave => $nombre): ?>
                <option value="<?= $clave ?>"><?= $nombre ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
```

**Para entender:** nunca se muestra `$_POST['ciudad']` directamente; se muestra el valor **de nuestro array**.
Así, aunque el usuario mande basura, solo puede salir un texto que nosotros escribimos.

---

## 5. Formulario que recuerda (*sticky*)

```php
<?php
$roles   = ['estudiante' => 'Estudiante', 'docente' => 'Docente', 'admin' => 'Administrador'];
$errores = [];
$datos   = ['nombre' => '', 'email' => '', 'edad' => '', 'rol' => ''];
$valido  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($datos as $campo => $_) {
        $datos[$campo] = trim($_POST[$campo] ?? '');
    }
    $password  = $_POST['password']  ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($datos['nombre'] === '') {
        $errores['nombre'] = 'El nombre es obligatorio.';
    } elseif (mb_strlen($datos['nombre']) < 3) {
        $errores['nombre'] = 'Mínimo 3 caracteres.';
    }

    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'Escribe un correo válido.';
    }

    if (filter_var($datos['edad'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 120]]) === false) {
        $errores['edad'] = 'La edad debe ser un número entre 1 y 120.';
    }

    if (strlen($password) < 6) {
        $errores['password'] = 'Mínimo 6 caracteres.';
    }
    if ($password !== $password2) {
        $errores['password2'] = 'Las contraseñas no coinciden.';
    }

    if (!array_key_exists($datos['rol'], $roles)) {
        $errores['rol'] = 'Selecciona un rol.';
    }

    $valido = empty($errores);
}

// Pequeños ayudantes para no repetir en el HTML
function viejo(array $datos, string $campo): string
{
    return htmlspecialchars($datos[$campo] ?? '');
}

function error(array $errores, string $campo): string
{
    return isset($errores[$campo])
        ? '<small style="color:#c0392b">' . htmlspecialchars($errores[$campo]) . '</small>'
        : '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 480px; margin: 30px auto; }
        input, select { width: 100%; padding: 6px; box-sizing: border-box; }
        .campo { margin-bottom: 14px; }
    </style>
</head>
<body>
<?php if ($valido): ?>

    <h2>Registro válido</h2>
    <p>Nombre: <?= viejo($datos, 'nombre') ?></p>
    <p>Email: <?= viejo($datos, 'email') ?></p>
    <p>Edad: <?= (int) $datos['edad'] ?></p>
    <p>Rol: <?= $roles[$datos['rol']] ?></p>
    <a href="">Registrar otro</a>

<?php else: ?>

    <h2>Registro</h2>
    <form method="POST" novalidate>
        <div class="campo">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?= viejo($datos, 'nombre') ?>">
            <?= error($errores, 'nombre') ?>
        </div>
        <div class="campo">
            <label>Email</label>
            <input type="email" name="email" value="<?= viejo($datos, 'email') ?>">
            <?= error($errores, 'email') ?>
        </div>
        <div class="campo">
            <label>Edad</label>
            <input type="number" name="edad" value="<?= viejo($datos, 'edad') ?>">
            <?= error($errores, 'edad') ?>
        </div>
        <div class="campo">
            <label>Contraseña</label>
            <input type="password" name="password">  <!-- nunca se rellena -->
            <?= error($errores, 'password') ?>
        </div>
        <div class="campo">
            <label>Confirmar contraseña</label>
            <input type="password" name="password2">
            <?= error($errores, 'password2') ?>
        </div>
        <div class="campo">
            <label>Rol</label>
            <select name="rol">
                <option value="">-- Selecciona --</option>
                <?php foreach ($roles as $clave => $texto): ?>
                    <option value="<?= $clave ?>" <?= $datos['rol'] === $clave ? 'selected' : '' ?>><?= $texto ?></option>
                <?php endforeach; ?>
            </select>
            <?= error($errores, 'rol') ?>
        </div>
        <button type="submit">Registrar</button>
    </form>

<?php endif; ?>
</body>
</html>
```

**Para entender:** `novalidate` desactiva la validación del navegador, así puedes comprobar que la validación de PHP funciona por sí sola.
`viejo()` es la misma idea que `old()` de Laravel.

---

## 6. Casillas de verificación

```php
<?php
$opciones = ['php' => 'PHP', 'laravel' => 'Laravel', 'js' => 'JavaScript', 'bd' => 'Bases de datos', 'git' => 'Git'];
$elegidos = [];
$error    = '';
$mensaje  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $elegidos = $_POST['intereses'] ?? [];          // si no marcan nada, la clave no existe

    if (!is_array($elegidos)) {
        $elegidos = [];
    }
    $invalidos = array_diff($elegidos, array_keys($opciones));

    if ($invalidos) {
        $error = 'Hay opciones no válidas.';
    } elseif (count($elegidos) < 2) {
        $error = 'Marca al menos 2 temas.';
    } else {
        $nombres = array_map(fn($clave) => $opciones[$clave], $elegidos);
        $ultimo  = array_pop($nombres);
        $mensaje = 'Te interesa: ' . ($nombres ? implode(', ', $nombres) . ' y ' : '') . $ultimo;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Intereses</title></head>
<body>
    <?php if ($mensaje): ?><p><?= htmlspecialchars($mensaje) ?></p><?php endif; ?>
    <?php if ($error): ?><p style="color:#c0392b"><?= $error ?></p><?php endif; ?>

    <form method="POST">
        <p>¿Qué te interesa aprender?</p>
        <?php foreach ($opciones as $clave => $texto): ?>
            <label>
                <input type="checkbox" name="intereses[]" value="<?= $clave ?>"
                    <?= in_array($clave, $elegidos) ? 'checked' : '' ?>>
                <?= $texto ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
```

---

## 7. Fecha de nacimiento

```php
<?php
date_default_timezone_set('America/Bogota');

$dias    = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
$valor   = '';
$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['nacimiento'] ?? '';
    $fecha = DateTime::createFromFormat('!Y-m-d', $valor);
    $hoy   = new DateTime('today');

    if (!$fecha || $fecha->format('Y-m-d') !== $valor) {
        $error = 'Escribe una fecha válida.';
    } elseif ($fecha > $hoy) {
        $error = 'La fecha no puede ser futura.';
    } else {
        $edad = $fecha->diff($hoy)->y;

        if ($edad < 14 || $edad > 100) {
            $error = 'Debes tener entre 14 y 100 años.';
        } else {
            $mensaje = "Tienes $edad años. Naciste un " . $dias[(int) $fecha->format('w')] . ".";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Fecha de nacimiento</title></head>
<body>
    <?php if ($mensaje): ?><p><?= $mensaje ?></p><?php endif; ?>
    <?php if ($error): ?><p style="color:#c0392b"><?= $error ?></p><?php endif; ?>

    <form method="POST">
        <input type="date" name="nacimiento" value="<?= htmlspecialchars($valor) ?>">
        <button type="submit">Calcular</button>
    </form>
</body>
</html>
```

---

## 8. Calculadora de IMC

```php
<?php
function aDecimal(string $texto, float $min, float $max): float|false
{
    $texto = str_replace(',', '.', trim($texto));
    return filter_var($texto, FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => $min, 'max_range' => $max]]);
}

function clasificarImc(float $imc): string
{
    return match (true) {
        $imc < 18.5 => 'Bajo peso',
        $imc < 25   => 'Normal',
        $imc < 30   => 'Sobrepeso',
        default     => 'Obesidad',
    };
}

$peso = $estatura = '';
$errores = [];
$resultado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peso     = $_POST['peso']     ?? '';
    $estatura = $_POST['estatura'] ?? '';

    $p = aDecimal($peso, 20, 300);
    $e = aDecimal($estatura, 1.0, 2.5);

    if ($p === false) $errores[] = 'El peso debe estar entre 20 y 300 kg.';
    if ($e === false) $errores[] = 'La estatura debe estar entre 1,0 y 2,5 m.';

    if (!$errores) {
        $imc = $p / ($e ** 2);
        $resultado = 'Tu IMC es ' . number_format($imc, 1, ',', '.') . ' (' . clasificarImc($imc) . ')';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>IMC</title></head>
<body>
    <?php foreach ($errores as $e): ?><p style="color:#c0392b"><?= $e ?></p><?php endforeach; ?>
    <?php if ($resultado): ?><h2><?= $resultado ?></h2><?php endif; ?>

    <form method="POST">
        <input type="text" name="peso"     placeholder="Peso (kg)"     value="<?= htmlspecialchars($peso) ?>">
        <input type="text" name="estatura" placeholder="Estatura (m)" value="<?= htmlspecialchars($estatura) ?>">
        <button type="submit">Calcular</button>
    </form>
</body>
</html>
```

**Para entender:** `match (true)` evalúa cada condición en orden y devuelve la primera verdadera (PHP 8).
Es más corto que una cadena de `if/elseif` cuando solo hay que devolver un valor. `**` es la potencia, igual que en Python.

---

## 9. Post/Redirect/Get y mensaje flash

```php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');

    if ($nombre !== '') {
        // ... aquí se guardaría en la base de datos ...
        $_SESSION['flash'] = "Registro exitoso: $nombre";
        header('Location: ' . $_SERVER['PHP_SELF']);   // Redirect -> el navegador hace un GET
        exit;
    }
    $error = 'Escribe tu nombre';
}

// Leer el flash y borrarlo: solo se muestra una vez
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>PRG</title></head>
<body>
    <?php if ($flash): ?>
        <p style="color:#27ae60"><?= htmlspecialchars($flash) ?></p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p style="color:#c0392b"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Tu nombre">
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
```

**Para entender:** si hay error **no** se redirige, porque necesitamos mostrar el formulario con los datos y
el error. Solo se redirige cuando todo salió bien.

---

## 10. Guardar el registro en MySQL + SweetAlert2

```php
<?php
session_start();
require_once __DIR__ . '/../../config.php';   // trae getDBConnection() y DB_NAME_MVC

$roles   = ['estudiante' => 'Estudiante', 'docente' => 'Docente', 'admin' => 'Administrador'];
$errores = [];
$datos   = ['nombre' => '', 'email' => '', 'rol' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($datos as $campo => $_) {
        $datos[$campo] = trim($_POST[$campo] ?? '');
    }
    $password  = $_POST['password']  ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (mb_strlen($datos['nombre']) < 3)                     $errores['nombre']    = 'Mínimo 3 caracteres.';
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) $errores['email']     = 'Escribe un correo válido.';
    if (strlen($password) < 6)                               $errores['password']  = 'Mínimo 6 caracteres.';
    if ($password !== $password2)                            $errores['password2'] = 'Las contraseñas no coinciden.';
    if (!array_key_exists($datos['rol'], $roles))            $errores['rol']       = 'Selecciona un rol.';

    if (!$errores) {
        $db   = getDBConnection(DB_NAME_MVC);
        $stmt = $db->prepare(
            "INSERT INTO registros (nombre, email, password, rol)
             VALUES (:nombre, :email, :password, :rol)"
        );

        try {
            $stmt->execute([
                ':nombre'   => $datos['nombre'],
                ':email'    => $datos['email'],
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':rol'      => $datos['rol'],
            ]);

            $_SESSION['flash'] = ['icon' => 'success', 'title' => 'Registro guardado',
                                  'text' => "Bienvenido, {$datos['nombre']}"];
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {          // violación de UNIQUE: email repetido
                $errores['email'] = 'Este correo ya está registrado.';
            } else {
                error_log($e->getMessage());
                $errores['general'] = 'No se pudo guardar. Intenta más tarde.';
            }
        }
    }
}

// Si hubo errores, también se anuncian con SweetAlert2
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
if ($errores) {
    $flash = ['icon' => 'error', 'title' => 'Revisa el formulario', 'text' => implode(' ', $errores)];
}

function viejo(array $datos, string $campo): string
{
    return htmlspecialchars($datos[$campo] ?? '');
}

function error(array $errores, string $campo): string
{
    return isset($errores[$campo])
        ? '<small style="color:#c0392b">' . htmlspecialchars($errores[$campo]) . '</small>'
        : '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 480px; margin: 30px auto; }
        input, select { width: 100%; padding: 6px; box-sizing: border-box; }
        .campo { margin-bottom: 14px; }
    </style>
</head>
<body>
    <h2>Registro</h2>
    <form method="POST" novalidate>
        <div class="campo">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?= viejo($datos, 'nombre') ?>">
            <?= error($errores, 'nombre') ?>
        </div>
        <div class="campo">
            <label>Email</label>
            <input type="email" name="email" value="<?= viejo($datos, 'email') ?>">
            <?= error($errores, 'email') ?>
        </div>
        <div class="campo">
            <label>Contraseña</label>
            <input type="password" name="password">
            <?= error($errores, 'password') ?>
        </div>
        <div class="campo">
            <label>Confirmar contraseña</label>
            <input type="password" name="password2">
            <?= error($errores, 'password2') ?>
        </div>
        <div class="campo">
            <label>Rol</label>
            <select name="rol">
                <option value="">-- Selecciona --</option>
                <?php foreach ($roles as $clave => $texto): ?>
                    <option value="<?= $clave ?>" <?= $datos['rol'] === $clave ? 'selected' : '' ?>><?= $texto ?></option>
                <?php endforeach; ?>
            </select>
            <?= error($errores, 'rol') ?>
        </div>
        <button type="submit">Registrar</button>
    </form>

    <?php if ($flash): ?>
    <script>
        // json_encode convierte el array PHP en un objeto JavaScript seguro
        Swal.fire(<?= json_encode($flash) ?>);
    </script>
    <?php endif; ?>
</body>
</html>
```

**Para entender:** en phpMyAdmin la contraseña se ve como `$2y$10$...`. Para el login se usa
`password_verify($escrita, $hash)` (como en `11-miniproyecto/login.php`). `Swal.fire({icon, title, text})`
recibe directamente el objeto que generó PHP.

---

## 11. Mi propio validador

```php
<?php
function validar(array $datos, array $reglas): array
{
    $errores = [];

    foreach ($reglas as $campo => $textoReglas) {
        $valor = trim((string) ($datos[$campo] ?? ''));

        foreach (explode('|', $textoReglas) as $regla) {
            // 'min:3' -> $nombre = 'min', $parametro = '3'
            [$nombre, $parametro] = array_pad(explode(':', $regla, 2), 2, null);

            // Campo vacío y no requerido: no se valida nada más
            if ($valor === '' && $nombre !== 'requerido') {
                break;
            }

            $error = match ($nombre) {
                'requerido' => $valor === '' ? "El campo $campo es obligatorio." : null,
                'email'     => !filter_var($valor, FILTER_VALIDATE_EMAIL) ? "El campo $campo debe ser un correo válido." : null,
                'min'       => mb_strlen($valor) < (int) $parametro ? "El campo $campo debe tener mínimo $parametro caracteres." : null,
                'max'       => mb_strlen($valor) > (int) $parametro ? "El campo $campo debe tener máximo $parametro caracteres." : null,
                'entero'    => filter_var($valor, FILTER_VALIDATE_INT) === false ? "El campo $campo debe ser un número entero." : null,
                'entre'     => (function () use ($valor, $parametro, $campo) {
                                   [$a, $b] = explode(',', $parametro);
                                   return ($valor < $a || $valor > $b) ? "El campo $campo debe estar entre $a y $b." : null;
                               })(),
                'en'        => !in_array($valor, explode(',', $parametro), true) ? "El valor de $campo no es válido." : null,
                default     => throw new InvalidArgumentException("Regla desconocida: $nombre"),
            };

            if ($error) {
                $errores[$campo] = $error;
                break;               // solo el primer error de cada campo
            }
        }
    }

    return $errores;
}

// ── Prueba ────────────────────────────────────────────────────────
$reglas = [
    'nombre' => 'requerido|min:3|max:60',
    'email'  => 'requerido|email',
    'edad'   => 'requerido|entero|entre:1,120',
    'rol'    => 'requerido|en:estudiante,docente,admin',
    'ficha'  => 'entero',                       // opcional
];

$pruebas = [
    ['nombre' => 'Ana Gómez', 'email' => 'ana@sena.edu.co', 'edad' => '22', 'rol' => 'docente', 'ficha' => ''],
    ['nombre' => 'Al', 'email' => 'ana@', 'edad' => '150', 'rol' => 'hacker', 'ficha' => 'abc'],
    [],
];

foreach ($pruebas as $i => $datos) {
    $errores = validar($datos, $reglas);
    echo "<strong>Prueba " . ($i + 1) . ":</strong> ";
    echo $errores ? '<br>' . implode('<br>', $errores) : '[OK] sin errores';
    echo "<br><br>";
}
```

**Así queda `validar.php` usando la función** (la parte de validación pasa de ~45 líneas a 7):

```php
<?php
require __DIR__ . '/validador.php'; // contiene SOLO la función validar() de arriba (sin la prueba)

$errores = validar($_POST, [
    'nombre'    => 'requerido|min:3|max:60',
    'email'     => 'requerido|email',
    'edad'      => 'requerido|entero|entre:1,120',
    'password'  => 'requerido|min:6',
    'rol'       => 'requerido|en:estudiante,docente,admin',
]);

if (($_POST['password'] ?? '') !== ($_POST['password2'] ?? '')) {
    $errores['password2'] = 'Las contraseñas no coinciden.';
}
```

**Para entender:** la función anónima que se ejecuta de una vez `(function () { ... })()` permite tener varias
líneas dentro de un `match`. Si se ve muy enredado, la regla `entre` también puede ir en un `if` aparte.

---

## 12. Protección CSRF

**`ej12_atacante.html`** — se abre y envía el formulario solo:

```html
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>¡Ganaste un premio!</title></head>
<body>
    <h1>Cargando tu premio...</h1>

    <form id="trampa" action="http://localhost/Pestrada/20-formularios-validacion/ejercicios/ej10.php" method="POST" style="display:none">
        <input name="nombre"    value="Usuario Falso">
        <input name="email"     value="falso@atacante.com">
        <input name="password"  value="123456">
        <input name="password2" value="123456">
        <input name="rol"       value="admin">
    </form>

    <script>document.getElementById('trampa').submit();</script>
</body>
</html>
```

**Cambios en `ej10.php`:**

```php
<?php
session_start();
require_once __DIR__ . '/../../config.php';

// 1. Generar el token una vez por sesión
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. Verificarlo ANTES de hacer cualquier otra cosa
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(419);   // el mismo código que usa Laravel
        exit('La página expiró. Recarga el formulario e intenta de nuevo.');
    }

    // ... resto de la validación y el INSERT igual que en el ejercicio 10 ...
}
```

Y dentro del `<form>`:

```html
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
```

**Para entender:**
- La página atacante no puede leer el token porque está en otro sitio: el navegador no le deja ver nuestro HTML.
- `hash_equals` compara en tiempo constante; con `===` un atacante podría adivinar el token midiendo tiempos.
- El código `419` es justo el *"Page Expired"* que verán en Laravel cuando olviden poner `@csrf`.
