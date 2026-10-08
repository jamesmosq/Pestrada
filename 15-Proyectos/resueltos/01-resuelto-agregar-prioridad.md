# Resuelto 01 — Agregar "prioridad" al CRUD de tareas

**Tipo:** resuelto y comentado

## Enunciado

El CRUD de `15-Proyectos/CRUD` maneja título y descripción. Se pide que cada tarea tenga además una
**prioridad** (alta, media o baja): elegirla al crear, cambiarla al editar, verla en la tabla con un color
y que la lista muestre primero las de prioridad alta.

## Cómo se pensó (antes de tocar código)

1. **¿Dónde vive el dato?** En la base de datos. La tabla `tareas` de `database/setup.sql` **ya tiene** la
   columna `prioridad ENUM('baja','media','alta') DEFAULT 'media'`, así que no hay que crearla.
   (Si no existiera, el primer paso sería un `ALTER TABLE`.)
2. **¿Qué archivos tocan las tareas?** Hay que seguir el dato por todo su recorrido:

   | Archivo | Qué hace con las tareas | ¿Qué cambia? |
   |---|---|---|
   | `index.php` | Formulario de crear + tabla | Agregar el `<select>`, la columna y el orden |
   | `guardar_tarea.php` | INSERT | Recibir, validar y guardar la prioridad |
   | `actualizar_tarea.php` | SELECT + formulario + UPDATE | Mostrarla seleccionada, validar y actualizar |
   | `eliminar_tarea.php` | DELETE | Nada: borrar no depende de la prioridad |

3. **¿Dónde se escribe la lista "alta, media, baja"?** Se usa en tres archivos. Si se escribe tres veces,
   algún día alguien cambiará una y olvidará las otras. Entonces: **un solo archivo** con la lista, y los
   demás lo leen.
4. **¿Qué puede salir mal?** Que alguien envíe por F12 una prioridad que no existe (`urgentisima`).
   Se valida con una lista blanca, igual que en `08-superglobales/resueltos/01`.

## Paso 1: la lista en un solo lugar

Archivo nuevo **`includes/prioridades.php`**:

```php
<?php
// Lista de prioridades permitidas: clave que se guarda en la BD => texto y color para mostrar.
// Un solo lugar para cambiarlas: el formulario, la validación y la tabla leen de aquí.
return [
    'alta'  => ['texto' => 'Alta',  'color' => 'danger'],
    'media' => ['texto' => 'Media', 'color' => 'warning'],
    'baja'  => ['texto' => 'Baja',  'color' => 'secondary'],
];
```

Un archivo PHP puede **devolver** un valor con `return`. Quien lo incluye lo recibe así:

```php
$prioridades = require "includes/prioridades.php";
```

Los archivos de configuración de Laravel (`config/app.php`, `config/database.php`) funcionan exactamente así.

## Paso 2: `index.php`, el formulario de crear

Al inicio, debajo de `include("db.php")`:

```php
<?php $prioridades = require "includes/prioridades.php"; ?>
```

Dentro del formulario, después de la descripción:

```php
<div class="form-group">
    <label for="prioridad">Prioridad</label>
    <select name="prioridad" id="prioridad" class="form-control">
        <?php foreach ($prioridades as $clave => $p): ?>
            <option value="<?php echo $clave ?>" <?php echo $clave === 'media' ? 'selected' : '' ?>><?php echo $p['texto'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
```

Las opciones **salen de la lista**: si mañana se agrega "crítica" en `prioridades.php`, aparece sola en el formulario.

## Paso 3: `guardar_tarea.php`, validar y guardar

```php
<?php
include("db.php");
$prioridades = require "includes/prioridades.php";

if (isset($_POST['guardar_tarea'])) {
    $titulo      = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $prioridad   = $_POST['prioridad'] ?? '';

    // Lista blanca: solo se acepta una prioridad que exista en el archivo de prioridades
    if (!array_key_exists($prioridad, $prioridades)) {
        $_SESSION['message']      = 'Prioridad no válida';
        $_SESSION['message_type'] = 'danger';
    } else {
        $stmt = $conn->prepare("INSERT INTO tareas (titulo, descripcion, prioridad) VALUES (:titulo, :descripcion, :prioridad)");
        $stmt->execute([':titulo' => $titulo, ':descripcion' => $descripcion, ':prioridad' => $prioridad]);

        $_SESSION['message']      = 'Tarea guardada satisfactoriamente';
        $_SESSION['message_type'] = 'success';
    }
}

// Siempre redirigir y terminar el script con exit
header("Location: index.php");
exit;
```

## Paso 4: `index.php`, la tabla

Encabezado nuevo, entre "Descripción" y "Creado":

```html
<th>Prioridad</th>
```

La consulta, para que salgan primero las de prioridad alta:

```php
// FIELD() ordena según la posición en la lista: primero alta, luego media, luego baja
$stmt = $conn->query("SELECT * FROM tareas ORDER BY FIELD(prioridad, 'alta', 'media', 'baja'), fecha_creacion DESC");
```

La celda, entre la descripción y la fecha:

```php
<td>
    <?php $p = $prioridades[$row['prioridad']] ?? ['texto' => '-', 'color' => 'light']; ?>
    <span class="badge badge-<?php echo $p['color'] ?>"><?php echo $p['texto'] ?></span>
</td>
```

El `?? [...]` protege la página si en la base de datos hubiera un valor que no está en la lista.

## Paso 5: `actualizar_tarea.php`, editar

Arriba, igual que en los otros archivos:

```php
$prioridades = require "includes/prioridades.php";
```

Al leer la tarea, guardar también su prioridad actual:

```php
$titulo      = $row['titulo'];
$descripcion = $row['descripcion'];
$prioridad   = $row['prioridad'];
```

Al recibir el formulario:

```php
$prioridad   = $_POST['prioridad'] ?? '';

if (!array_key_exists($prioridad, $prioridades)) {
    $prioridad = 'media';   // si llega algo extraño, se usa la prioridad por defecto
}

$stmt = $conn->prepare("UPDATE tareas SET titulo = :titulo, descripcion = :descripcion, prioridad = :prioridad WHERE id = :id");
$stmt->execute([':titulo' => $titulo, ':descripcion' => $descripcion, ':prioridad' => $prioridad, ':id' => $id]);
```

Y en el formulario, el `<select>` con la prioridad actual seleccionada:

```php
<div class="form-group">
    <select name="prioridad" class="form-control">
        <?php foreach ($prioridades as $clave => $p): ?>
            <option value="<?php echo $clave ?>" <?php echo $clave === $prioridad ? 'selected' : '' ?>><?php echo $p['texto'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
```

## Cómo comprobar que funciona

| Prueba | Resultado esperado |
|---|---|
| Abrir `index.php` | Columna "Prioridad" con colores; las de prioridad alta arriba |
| Crear una tarea con prioridad Alta | Mensaje verde y la tarea aparece arriba con etiqueta roja |
| Con F12, cambiar una opción a `value="urgentisima"` y guardar | Mensaje rojo "Prioridad no válida" y no se crea nada |
| Editar esa tarea | El select muestra "Alta" seleccionada |
| Cambiarla a Baja | La etiqueta pasa a gris y la tarea baja en la lista |

## Trampa de PHP (viniendo de Python)

En Python, `import config` carga un módulo **una sola vez** aunque lo importes en diez lugares. En PHP,
`require` **ejecuta el archivo cada vez** que se llama, y `require_once` lo ejecuta solo la primera vez
(las siguientes no hace nada y devuelve `true`, no el array). Por eso aquí se usa `require`:
cada archivo necesita que le devuelvan la lista.

## Para analizar

1. ¿Cuántos archivos tocaste para agregar un solo campo? Anota cuáles. En `21-sweetAlert2`, con MVC,
   ¿cuántos tocarías para lo mismo? (Mira el ejercicio 5 de `21-sweetAlert2/EJERCICIOS.md`.)
2. Al **guardar**, una prioridad inválida se rechaza con un mensaje; al **actualizar**, se cambia por "media"
   sin avisar. ¿Cuál de los dos comportamientos te parece mejor? ¿Por qué? Unifica el código.
3. ¿Qué pasaría si en lugar de `array_key_exists` se escribiera la prioridad en el SQL directamente
   (`"... VALUES ('$prioridad')"`)? Mira el resuelto 03 de esta carpeta.
4. ¿Por qué `FIELD(prioridad, 'alta', 'media', 'baja')` y no `ORDER BY prioridad`? Pruébalo:
   ¿en qué orden salen con `ORDER BY prioridad`?

## Para modificar

- Haz lo mismo con la columna `estado` (pendiente, en proceso, completada), que también existe en la tabla.
- Agrega arriba de la tabla un filtro para ver solo las tareas de una prioridad (`index.php?prioridad=alta`).
