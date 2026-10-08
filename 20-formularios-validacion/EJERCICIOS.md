# Ejercicios — 20 Formularios y validación

Antes de empezar, repasa `index.html` + `validar.php` y la guía `formularios_php_progresivos.txt`.

**Cómo trabajar:** carpeta `ejercicios/`, un archivo por ejercicio. Todos se abren en el navegador:
`http://localhost/Pestrada/20-formularios-validacion/ejercicios/ej01.php`

| Nivel | Ejercicios |
|---|---|
| Básico | 1 – 4 |
| Intermedio | 5 – 9 |
| Reto | 10 – 12 |

> **Regla de oro:** la validación de HTML (`required`, `type="email"`, `min`) es solo comodidad para el usuario.
> Cualquiera la salta en 5 segundos con las herramientas del navegador (F12). **La validación que cuenta es la de PHP.**

---

## 1. GET contra POST

1. Haz `ej01.php` con un formulario de contacto (nombre y correo) que envíe con `method="GET"` a `ej01_mostrar.php`,
   y que esa página muestre los datos recibidos.
2. Cámbialo a `method="POST"` (y en `ej01_mostrar.php` usa `$_POST`).

Responde en un comentario dentro del código:

- ¿Dónde se veían los datos con GET? ¿Y con POST?
- ¿Cuál usarías para un **login**? ¿Y para un **buscador** de productos? ¿Por qué?
- Con GET, ¿qué pasa si guardas la página en favoritos y la vuelves a abrir?

---

## 2. Todo en un solo archivo

Haz un formulario que pida tu nombre y, **en el mismo archivo**, lo procese: si llegó por POST, muestra
`¡Hola, Ana!` encima del formulario.

- El `action` del formulario queda vacío (`action=""`) o se omite.
- Si el nombre llega vacío, muestra `Escribe tu nombre`.
- Prueba escribiendo `<b>Ana</b>` como nombre: debe verse tal cual, no en negrita.

**Pista:** `if ($_SERVER['REQUEST_METHOD'] === 'POST')`.

---

## 3. Celular y cédula

Escribe dos funciones y pruébalas con la tabla:

```php
validarCelular(string $numero): bool   // 10 dígitos y empieza por 3
validarCedula(string $numero): bool    // solo dígitos, entre 6 y 10
```

| Celular | ¿Válido? | Cédula | ¿Válida? |
|---|---|---|---|
| `3001234567` | sí | `1036123456` | sí |
| `300 123 4567` | sí (se aceptan espacios) | `98765` | no (muy corta) |
| `6044441234` | no (fijo) | `10.361.234` | sí (se aceptan puntos) |
| `30012345` | no | `ABC123` | no |

**Pista:** limpia primero con `str_replace([' ', '.'], '', $numero)`, luego `preg_match('/^3\d{9}$/', ...)`.

---

## 4. Select con lista blanca

Haz un formulario con un `<select>` de ciudades. Las opciones **se generan desde un array de PHP** con `foreach`:

```php
$ciudades = ['medellin' => 'Medellín', 'itagui' => 'Itagüí', 'envigado' => 'Envigado', 'bello' => 'Bello'];
```

Al enviarlo, muestra `Elegiste: Itagüí`.

**Prueba de seguridad:** con F12, edita el `value` de una opción a `bogota<script>` y envía.
Tu PHP debe responder `Ciudad no válida`.

**Pista:** `array_key_exists($_POST['ciudad'], $ciudades)`.

---

## 5. Formulario que recuerda (*sticky*)

Rehaz el registro de `validar.php` (nombre, email, edad, contraseña, confirmar, rol) en **un solo archivo** con
estas mejoras:

- Cada error aparece **debajo de su campo**, en rojo, no en una lista arriba.
- Si hay errores, los campos conservan lo que el usuario escribió (excepto las contraseñas).
- El `<select>` de rol también conserva la opción elegida.
- Si todo está bien, muestra un resumen en lugar del formulario.

**Pista:** `value="<?= htmlspecialchars($nombre) ?>"` y `<?= $rol === 'docente' ? 'selected' : '' ?>`.

> **Puente a Laravel:** En Laravel esto se escribe `value="{{ old('nombre') }}"` y `@error('nombre') ... @enderror`.

---

## 6. Casillas de verificación

Agrega a un formulario la pregunta *"¿Qué te interesa aprender?"* con casillas: PHP, Laravel, JavaScript, Bases de datos, Git.

- Deben marcarse **al menos 2**.
- Cada valor recibido debe estar en la lista permitida.
- Si hay error, las casillas marcadas siguen marcadas.
- Si está bien: `Te interesa: PHP, Laravel y Git` (fíjate en la "y" antes del último).

**Pista:** `name="intereses[]"` hace que PHP reciba un **array**. Si no se marca ninguna, `$_POST['intereses']` **no existe**.

---

## 7. Fecha de nacimiento

Usa `<input type="date" name="nacimiento">`. En PHP valida:

- Que llegue y tenga el formato `aaaa-mm-dd` y sea una fecha real.
- Que no sea una fecha futura.
- Que la persona tenga entre 14 y 100 años.

Si es válida, muestra `Tienes 22 años. Naciste un martes.`

**Pista:** reutiliza lo del módulo 17: `DateTime::createFromFormat('!Y-m-d', ...)` y `diff()`.

---

## 8. Calculadora de IMC

Formulario con peso (kg) y estatura (m). Calcula el IMC = peso / estatura² y clasifícalo:

| IMC | Clasificación |
|---|---|
| < 18,5 | Bajo peso |
| 18,5 – 24,9 | Normal |
| 25 – 29,9 | Sobrepeso |
| ≥ 30 | Obesidad |

- Acepta decimales con coma o con punto (`1,75` y `1.75`).
- Peso válido: 20 a 300. Estatura válida: 1,0 a 2,5.
- Resultado con un decimal: `Tu IMC es 22,9 (Normal)`.

**Pista:** `str_replace(',', '.', ...)` y `filter_var($v, FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 20, 'max_range' => 300]])`.

---

## 9. Post/Redirect/Get y mensaje flash

Envía cualquier formulario del ejercicio 2 o 5 y presiona **F5**: el navegador pregunta si quieres reenviar los datos.
En una app real eso duplica registros.

Arréglalo:

1. Si los datos son válidos, guarda en `$_SESSION['flash']` el mensaje `Registro exitoso` y redirige con `header('Location: ...')`.
2. Al cargar la página, si hay un flash, muéstralo **y bórralo** (debe verse una sola vez).
3. Comprueba que F5 ya no pregunta nada y que el mensaje desaparece al recargar.

> **Puente a Laravel:** En Laravel: `return redirect('/registro')->with('flash', 'Registro exitoso');`.

---

## 10. Guardar el registro en MySQL + SweetAlert2

Crea esta tabla en `sena_mvc` (phpMyAdmin → SQL):

```sql
CREATE TABLE IF NOT EXISTS registros (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nombre   VARCHAR(60)  NOT NULL,
    email    VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol      ENUM('estudiante', 'docente', 'admin') NOT NULL,
    creado   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Toma tu formulario del ejercicio 5 y, si es válido:

1. Guarda el registro con PDO y consulta preparada. Conéctate con `getDBConnection(DB_NAME_MVC)` de `config.php`.
2. La contraseña se guarda con `password_hash()`. **Nunca** en texto plano.
3. Si el email ya existe, muestra el error en el campo email (no un *Fatal error*).
4. Usa PRG (ejercicio 9) y muestra el resultado con **SweetAlert2**: éxito en verde, error en rojo.

Revisa en phpMyAdmin cómo quedó guardada la contraseña.

---

## 11. Mi propio validador

Repetir `if (empty(...))` en cada formulario es tedioso. Escribe una función que valide con **reglas en texto**:

```php
function validar(array $datos, array $reglas): array

$errores = validar($_POST, [
    'nombre' => 'requerido|min:3|max:60',
    'email'  => 'requerido|email',
    'edad'   => 'requerido|entero|entre:1,120',
    'rol'    => 'requerido|en:estudiante,docente,admin',
]);
// ['email' => 'El campo email debe ser un correo válido.', ...]
```

Reglas mínimas: `requerido`, `email`, `min:n`, `max:n`, `entero`, `entre:a,b`, `en:a,b,c`.
Cada campo devuelve solo su **primer** error. Si un campo vacío no es `requerido`, no se valida lo demás.

Luego reescribe `validar.php` usándola. ¿Cuántas líneas te ahorraste?

> **Puente a Laravel:** Esto es, casi letra por letra, cómo valida Laravel:
> `$request->validate(['nombre' => 'required|min:3|max:60', 'email' => 'required|email'])`.

---

## 12. Protección CSRF

1. Crea `ej12_atacante.html`: una página "maliciosa" que, al abrirse, envíe automáticamente un formulario oculto
   por POST a tu ejercicio 10 con datos falsos. Ábrela: ¿se creó el registro?
2. Protege el ejercicio 10 con un token CSRF:
   - Al mostrar el formulario, genera un token aleatorio y guárdalo en `$_SESSION`.
   - Inclúyelo en un `<input type="hidden" name="csrf_token">`.
   - Al recibir el POST, si el token no coincide (usa `hash_equals`), rechaza la petición.
3. Vuelve a abrir la página del atacante: ahora debe fallar.

Mira `12-sesion/functions.php` y `21-sweetAlert2/controllers/EstudianteController.php`: ya tienen esto hecho.

> **Puente a Laravel:** En Laravel basta con escribir `@csrf` dentro del formulario. Ahora sabes qué hace.
