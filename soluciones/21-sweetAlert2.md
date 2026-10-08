# Soluciones — 21 SweetAlert2 + MVC

> **Antes de mirar:** intenta el ejercicio por tu cuenta al menos 20 minutos. Si te bloqueas, lee solo
> la pista del enunciado y vuelve a intentarlo. Cuando lo termines (o si de verdad no sale), compara con esta
> solución: es **una** forma de resolverlo, no la única.
>
> **Solución completa y probada** (ábrela solo después de construir la tuya): la carpeta [`21-proyecto-final/`](21-proyecto-final/) tiene el proyecto con los
> 12 ejercicios terminados. Para verlo funcionando:
>
> 1. Ejecuta `21-proyecto-final/migracion.sql` en phpMyAdmin (agrega `telefono`, `cursos` y `curso_id` a `sena_mvc`).
> 2. Abre `http://localhost/Pestrada/soluciones/21-proyecto-final/`.
>
> Abajo está **cada paso por separado**, en el mismo orden en que lo vas construyendo, para que compares
> tu avance ejercicio por ejercicio. Los fragmentos de los pasos 1 a 10 usan todavía los mensajes por URL (`status` / `mensaje`); el
> paso 11 los cambia por mensajes en sesión.

---

## 1. Toasts para los mensajes de éxito

**`views/estudiantes/index.php`** — reemplazar el `Swal.fire` de los mensajes flash:

```js
if (status && alertas[status]) {
    if (status === 'error') {
        Swal.fire({ icon: 'error', title: alertas.error.title, text: mensaje });
    } else {
        Swal.fire({
            toast:             true,
            position:          'top-end',
            icon:              alertas[status].icon,
            title:             mensaje,
            showConfirmButton: false,
            timer:             3000,
            timerProgressBar:  true,
        });
    }
}
```

---

## 2. Contador de estudiantes

**Modelo:**

```php
public function contar(): int
{
    return (int) $this->db->query("SELECT COUNT(*) FROM estudiantes")->fetchColumn();
}
```

**Controlador, `index()`:**

```php
$total = $this->model->contar();
$this->cargarVista('estudiantes/index', compact('estudiantes', 'status', 'mensaje', 'total'));
```

**Vista, debajo del `<h2>`:**

```php
<small><?php echo $total; ?> estudiante<?php echo $total === 1 ? '' : 's'; ?> registrado<?php echo $total === 1 ? '' : 's'; ?></small>
```

**Para entender:** `fetchColumn()` devuelve la primera columna de la primera fila: ideal para `COUNT(*)`.

---

## 3. Validar la ficha

**Controlador:** método privado que reúne todas las validaciones.

```php
// Devuelve el primer error encontrado, o null si todo está bien
private function validar(array $d): ?string
{
    if ($d['nombre'] === '' || $d['email'] === '') {
        return 'El nombre y el email son obligatorios.';
    }
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        return 'El formato del email no es valido.';
    }
    if ($d['ficha'] !== '' && !preg_match('/^\d{7}$/', $d['ficha'])) {
        return 'La ficha debe tener exactamente 7 digitos.';
    }
    return null;
}
```

**En `guardar()`** (en `actualizar()` igual, pero redirigiendo a `editar` con el `$id`):

```php
$datos = ['nombre' => $nombre, 'email' => $email, 'ficha' => $ficha];

if ($error = $this->validar($datos)) {
    $this->redirigir('crear', 'error', urlencode($error));
    return;
}
```

**Vistas `crear.php` y `editar.php`:**

```html
<input type="text" name="ficha" class="form-control" pattern="[0-9]{7}" maxlength="7">
```

**Para entender:** `if ($error = $this->validar(...))` asigna y evalúa a la vez: si `validar` devuelve un texto, entra.

---

## 4. Cambios sin guardar

**`views/estudiantes/editar.php`** — el enlace *Volver*:

```html
<a href="index.php?action=index" class="btn btn--secondary" onclick="return volver(event, this.href)">
```

Y en el `<script>`:

```js
const form    = document.getElementById('formEditar');
const inicial = new URLSearchParams(new FormData(form)).toString();   // "foto" al cargar

function hayCambios() {
    return new URLSearchParams(new FormData(form)).toString() !== inicial;
}

function volver(evento, destino) {
    if (!hayCambios()) {
        return true;                 // sin cambios: el enlace funciona normal
    }
    evento.preventDefault();
    Swal.fire({
        icon:              'warning',
        title:             'Tienes cambios sin guardar',
        text:              'Si sales ahora, se perderan.',
        showCancelButton:  true,
        confirmButtonText: 'Salir sin guardar',
        cancelButtonText:  'Seguir editando',
    }).then((r) => {
        if (r.isConfirmed) {
            window.location.href = destino;
        }
    });
    return false;
}
```

---

## 5. Nuevo campo: celular

**Modelo** — `crear()` (en `actualizar()` se agrega `telefono = :telefono` al `SET`):

```php
$stmt = $this->db->prepare(
    "INSERT INTO estudiantes (nombre, email, ficha, telefono)
     VALUES (:nombre, :email, :ficha, :telefono)"
);
// ...
':telefono' => $datos['telefono'] ?: null,   // texto vacío -> NULL en la BD
```

**Controlador** — al leer el formulario y en `validar()`:

```php
$telefono = str_replace([' ', '-'], '', trim($_POST['telefono'] ?? ''));
```

```php
if ($d['telefono'] !== '' && !preg_match('/^3\d{9}$/', $d['telefono'])) {
    return 'El telefono debe ser un celular de 10 digitos que empiece por 3.';
}
```

**Vista `editar.php`** (en `crear.php` igual, sin `value`):

```php
<input type="tel" name="telefono" class="form-control form-control--warning"
       pattern="3[0-9]{9}" placeholder="Ej: 3001234567"
       value="<?php echo htmlspecialchars($estudiante['telefono'] ?? ''); ?>">
```

**Para entender:** `?? ''` porque `telefono` puede ser `NULL`, y `htmlspecialchars(null)` da un aviso de *deprecated* en PHP 8.1+.

---

## 6. Buscador

**Modelo** — `obtenerTodos()` y `contar()` reciben el texto:

```php
public function obtenerTodos(string $buscar = ''): array
{
    $stmt = $this->db->prepare(
        "SELECT * FROM estudiantes
         WHERE nombre LIKE :buscar1 OR email LIKE :buscar2
         ORDER BY id DESC"
    );
    $stmt->execute([':buscar1' => "%{$buscar}%", ':buscar2' => "%{$buscar}%"]);
    return $stmt->fetchAll();
}

public function contar(string $buscar = ''): int
{
    $stmt = $this->db->prepare(
        "SELECT COUNT(*) FROM estudiantes WHERE nombre LIKE :buscar1 OR email LIKE :buscar2"
    );
    $stmt->execute([':buscar1' => "%{$buscar}%", ':buscar2' => "%{$buscar}%"]);
    return (int) $stmt->fetchColumn();
}
```

**Controlador `index()`:**

```php
$buscar      = trim($_GET['q'] ?? '');
$estudiantes = $this->model->obtenerTodos($buscar);
$total       = $this->model->contar($buscar);
```

**Vista:**

```php
<form class="search" method="GET" action="index.php">
    <input type="text" name="q" class="form-control" placeholder="Buscar por nombre o email..."
           value="<?php echo htmlspecialchars($buscar); ?>">
    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-magnifying-glass"></i></button>
    <?php if ($buscar !== ''): ?>
        <a href="index.php" class="btn btn--secondary">Limpiar</a>
    <?php endif; ?>
</form>
```

**Para entender:**
- `:buscar1` y `:buscar2` en vez de usar `:buscar` dos veces: con algunas configuraciones de PDO (prepares nativos)
  no se puede repetir un mismo nombre de parámetro.
- `gom` encuentra *Gómez* porque la tabla usa un *collation* `_ci` / `_unicode_ci`: compara sin mayúsculas ni tildes.

---

## 7. Vista de detalle

**Router `index.php`:** agregar `'ver' => 'ver'` a `$accionesPermitidas`. Si se olvida, el router redirige al listado:
la lista blanca hace su trabajo.

**Controlador:**

```php
public function ver(): void
{
    $id         = intval($_GET['id'] ?? 0);
    $estudiante = $id > 0 ? $this->model->obtenerPorId($id) : false;

    if (!$estudiante) {
        $this->redirigir('index', 'error', urlencode('Estudiante no encontrado.'));
        return;
    }
    $this->cargarVista('estudiantes/ver', compact('estudiante'));
}
```

**Vista `views/estudiantes/ver.php`:** ver [`21-proyecto-final/views/estudiantes/ver.php`](21-proyecto-final/views/estudiantes/ver.php).
Lo esencial:

```php
<dt>Registrado</dt>
<dd><?php echo date('d/m/Y H:i', strtotime($estudiante['created_at'])); ?></dd>
```

**Enlace en el listado:**

```php
<a href="index.php?action=ver&id=<?php echo (int) $est['id']; ?>"><?php echo htmlspecialchars($est['nombre']); ?></a>
```

---

## 8. Confirmar escribiendo el nombre

**`views/estudiantes/index.php`** — en `confirmarEliminar()`:

```js
Swal.fire({
    icon:              'warning',
    title:             'Eliminar estudiante',
    html:              `Esta accion no se puede deshacer.<br>
                        Escribe <strong>${escaparHTML(nombre)}</strong> para confirmar:`,
    input:             'text',
    inputPlaceholder:  nombre,
    showCancelButton:  true,
    confirmButtonText: 'Eliminar',
    cancelButtonText:  'Cancelar',
    confirmButtonColor:'#d9534f',
    inputValidator: (valor) => {
        if (valor.trim() !== nombre) {
            return 'El nombre no coincide';   // devolver un texto = mostrar error y no cerrar
        }
    },
}).then((result) => {
    if (result.isConfirmed) {
        document.getElementById('eliminarId').value = id;
        document.getElementById('formEliminar').submit();
    }
});
```

**Para entender:** esto es una ayuda para el usuario, **no** seguridad: alguien puede enviar el POST sin pasar por
la alerta. La seguridad la dan el POST y el token CSRF.

---

## 9. Ordenar por columnas

**Modelo:**

```php
// nombre que llega en la URL => columna real
public const ORDENABLES = ['id' => 'id', 'nombre' => 'nombre', 'email' => 'email', 'ficha' => 'ficha'];

public function obtenerTodos(string $buscar = '', string $orden = 'id', string $dir = 'desc'): array
{
    $columna   = self::ORDENABLES[$orden] ?? 'id';      // si no está en la lista: id
    $direccion = $dir === 'asc' ? 'ASC' : 'DESC';       // solo dos valores posibles

    $stmt = $this->db->prepare(
        "SELECT * FROM estudiantes
         WHERE nombre LIKE :buscar1 OR email LIKE :buscar2
         ORDER BY {$columna} {$direccion}"
    );
    // ... execute igual que en el ejercicio 6
}
```

**Controlador `index()`:**

```php
$orden = $_GET['orden'] ?? 'id';
if (!array_key_exists($orden, EstudianteModel::ORDENABLES)) {
    $orden = 'id';
}
$dir = ($_GET['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
```

**Vista** — un ayudante para los encabezados (al inicio del archivo):

```php
<?php
$th = function (string $columna, string $titulo) use ($buscar, $orden, $dir) {
    $nuevaDir = ($orden === $columna && $dir === 'asc') ? 'desc' : 'asc';
    $flecha   = $orden === $columna ? ($dir === 'asc' ? ' ▲' : ' ▼') : '';
    $url      = 'index.php?' . http_build_query(['q' => $buscar, 'orden' => $columna, 'dir' => $nuevaDir]);
    return '<a class="th-link" href="' . htmlspecialchars($url) . '">' . $titulo . $flecha . '</a>';
};
?>
...
<th><?php echo $th('nombre', 'Nombre'); ?></th>
```

**Para entender:** el valor del usuario **nunca** llega al SQL: solo se usa como *clave* para buscar en nuestro array.
`use (...)` es la forma de que una función anónima vea variables de afuera (en Python lo hace sola).

---

## 10. Paginación

**Modelo** — la consulta final (ver [`EstudianteModel.php`](21-proyecto-final/models/EstudianteModel.php)):

```php
public function listar(string $buscar, string $orden, string $dir, int $limite, int $offset): array
{
    $columna   = self::ORDENABLES[$orden] ?? 'id';
    $direccion = $dir === 'asc' ? 'ASC' : 'DESC';

    $stmt = $this->db->prepare(
        "SELECT * FROM estudiantes
         WHERE nombre LIKE :buscar1 OR email LIKE :buscar2
         ORDER BY {$columna} {$direccion}
         LIMIT :limite OFFSET :offset"
    );
    $stmt->bindValue(':buscar1', "%{$buscar}%");
    $stmt->bindValue(':buscar2', "%{$buscar}%");
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}
```

**Controlador `index()`:**

```php
private const POR_PAGINA = 5;
// ...
$total   = $this->model->contar($buscar);
$paginas = max(1, (int) ceil($total / self::POR_PAGINA));
$pagina  = min(max(1, (int) ($_GET['pagina'] ?? 1)), $paginas);   // siempre entre 1 y $paginas
$offset  = ($pagina - 1) * self::POR_PAGINA;

$estudiantes = $this->model->listar($buscar, $orden, $dir, self::POR_PAGINA, $offset);
```

**Vista** — un ayudante que arma URLs conservando lo actual, y la navegación:

```php
<?php
$url = fn(array $cambios = []) => 'index.php?' . http_build_query(array_merge(
    ['q' => $buscar, 'orden' => $orden, 'dir' => $dir, 'pagina' => $pagina],
    $cambios
));
?>
...
<?php if ($paginas > 1): ?>
    <nav class="pagination">
        <?php if ($pagina > 1): ?>
            <a href="<?php echo htmlspecialchars($url(['pagina' => $pagina - 1])); ?>">&laquo; Anterior</a>
        <?php endif; ?>
        <?php for ($p = 1; $p <= $paginas; $p++): ?>
            <a href="<?php echo htmlspecialchars($url(['pagina' => $p])); ?>"
               class="<?php echo $p === $pagina ? 'active' : ''; ?>"><?php echo $p; ?></a>
        <?php endfor; ?>
        <?php if ($pagina < $paginas): ?>
            <a href="<?php echo htmlspecialchars($url(['pagina' => $pagina + 1])); ?>">Siguiente &raquo;</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
```

En el ayudante `$th` del ejercicio 9 se agrega `'pagina' => 1` para que al ordenar se vuelva a la primera página.

**Respuesta:** si `LIMIT` recibe texto, PDO (con prepares emulados, que es lo predeterminado en MySQL) genera
`LIMIT '5'` con comillas y MySQL lanza un error de sintaxis. Por eso `PDO::PARAM_INT`.

> **Puente a Laravel:** En Laravel: `Estudiante::where(...)->orderBy(...)->paginate(5)` y en la vista `{{ $estudiantes->links() }}`.

---

## 11. Mensajes en la sesión (flash)

**Router:** `session_start();` ya está (se agregó para el CSRF).

**Controlador** — tres métodos cambian:

```php
private function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

private function redirigir(string $action = 'index', int $id = 0): void
{
    $url = "index.php?action={$action}";
    if ($id > 0) {
        $url .= "&id={$id}";
    }
    header("Location: {$url}");
    exit;
}

private function cargarVista(string $vista, array $datos = []): void
{
    $datos['csrf']  = $this->csrfToken();
    $datos['flash'] = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);           // se muestra una sola vez

    extract($datos);
    require __DIR__ . "/../views/{$vista}.php";
}
```

Y cada redirección con mensaje pasa de:

```php
$this->redirigir('index', 'creado', urlencode("El estudiante \"$nombre\" fue registrado correctamente."));
```

a:

```php
$this->flash('creado', "El estudiante \"$nombre\" fue registrado correctamente.");
$this->redirigir('index');
```

Las acciones ya no leen `$_GET['status']` ni `$_GET['mensaje']`.

**Vista parcial `views/partials/flash.php`:** ver [`21-proyecto-final/views/partials/flash.php`](21-proyecto-final/views/partials/flash.php).
En cada vista, antes del `<script>` propio:

```php
<?php require __DIR__ . '/../partials/flash.php'; ?>
```

**Para entender:** como `redirigir()` termina con `exit`, ya no hace falta el `return;` después de cada llamada.

---

## 12. Segundo módulo: cursos (con relación)

Todo está en [`21-proyecto-final/`](21-proyecto-final/). Archivos nuevos o con cambios grandes:

| Archivo | Qué tiene |
|---|---|
| `controllers/Controller.php` | Clase padre abstracta: `cargarVista`, `redirigir`, `flash`, `exigirPost` (POST + CSRF) |
| `controllers/EstudianteController.php` | `extends Controller`; carga los cursos para el `<select>` y valida que el curso exista |
| `controllers/CursoController.php` | `index`, `guardar`, `eliminar` |
| `models/CursoModel.php` | `obtenerTodos()` con `COUNT` + `GROUP BY`, `existe()`, `crear()`, `eliminar()` |
| `models/EstudianteModel.php` | `LEFT JOIN cursos` en `listar()` y `obtenerPorId()`; `curso_id` en INSERT/UPDATE |
| `index.php` | Router con `?c=` y una lista blanca de acciones por controlador |
| `views/cursos/index.php` | Lista, formulario y eliminación con SweetAlert2 |

**El router:**

```php
$rutas = [
    'estudiantes' => [EstudianteController::class,
                      ['index', 'ver', 'crear', 'guardar', 'editar', 'actualizar', 'eliminar']],
    'cursos'      => [CursoController::class,
                      ['index', 'guardar', 'eliminar']],
];

$c      = $_GET['c']      ?? 'estudiantes';
$action = $_GET['action'] ?? 'index';

if (isset($rutas[$c]) && in_array($action, $rutas[$c][1], true)) {
    [$clase] = $rutas[$c];
    $controller = new $clase();
    $controller->$action();
} else {
    header('Location: index.php');
    exit;
}
```

**La consulta con la relación:**

```sql
SELECT e.*, c.nombre AS curso
FROM estudiantes e
LEFT JOIN cursos c ON c.id = e.curso_id
```

**Para entender:**
- `LEFT JOIN` y no `JOIN`: con `JOIN` desaparecerían de la lista los estudiantes sin curso.
- Cada hijo cambia `protected string $modulo = 'cursos';` y el `redirigir()` del padre arma la URL correcta.
  Es polimorfismo aplicado.
- `ON DELETE SET NULL` hace en la base de datos lo que, de otra forma, habría que programar en PHP.
- El ordenamiento por curso funciona agregando `'curso' => 'c.nombre'` a `ORDENABLES`: la lista blanca
  también protege columnas de otras tablas.

**Qué se probó en la solución:** listado con 7 estudiantes en 2 páginas, página fuera de rango, búsqueda sin
tildes, orden por cada columna, intento de inyección en `orden`, validaciones de ficha / celular / curso
inexistente / email duplicado, editar y eliminar (con y sin token), crear curso repetido y eliminar un curso
con estudiantes (quedan sin curso), y router con controlador o acción no permitidos.
