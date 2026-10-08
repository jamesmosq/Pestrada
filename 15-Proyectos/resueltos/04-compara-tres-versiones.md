# Resuelto 04 — Tres versiones de la misma aplicación

**Tipo:** compara dos (en este caso, tres) soluciones

En `15-Proyectos` hay tres aplicaciones que hacen casi lo mismo, una lista de tareas, escritas con tres
organizaciones distintas. Las tres funcionan. Este ejercicio es entender **en qué se diferencian** y
**por qué los proyectos grandes terminan organizados como la tercera**.

| Versión | Carpeta | Organización |
|---|---|---|
| A | `CRUD/` | Un archivo por acción, SQL y HTML mezclados |
| B | `todo-list-estruct/` | Funciones para el SQL, vistas para el HTML, un `index.php` que decide |
| C | `todo-list-poo/` | Clases: Modelo (SQL), Controlador (decide) y vistas (HTML) |

## Parte 1: investiga tú

Abre las tres carpetas y responde, para cada versión, **con el nombre del archivo**:

| Pregunta | A (`CRUD`) | B (`todo-list-estruct`) | C (`todo-list-poo`) |
|---|---|---|---|
| ¿Dónde está el SQL que **lista** las tareas? | | | |
| ¿Dónde está el HTML de la **tabla o lista**? | | | |
| ¿Quién decide qué hacer cuando llega una petición? | | | |
| ¿Cuántos archivos `.php` abre el navegador directamente? | | | |
| Si cambias el nombre de la tabla en la BD, ¿cuántos archivos tocas? | | | |

## Parte 2: respuesta

Léela solo cuando hayas llenado la tuya.

| Pregunta | A (`CRUD`) | B (`todo-list-estruct`) | C (`todo-list-poo`) |
|---|---|---|---|
| SQL que lista | `index.php`, **en medio del HTML** | `includes/functions.php` → `getTodos()` | `models/TodoItem.php` → `read()` |
| HTML de la lista | `index.php` (+ `includes/header.php` y `footer.php`) | `views/todo_list.php` | `views/todo_list.php` |
| Quién decide | El **nombre del archivo**: el navegador pide `guardar_tarea.php`, `eliminar_tarea.php`... | `index.php` con un `switch ($action)` | `index.php` llama a un **método** de `TodoController` |
| Archivos que abre el navegador | 4 (`index`, `guardar_tarea`, `actualizar_tarea`, `eliminar_tarea`) | 1 (`index.php?action=...`) | 1 (`index.php?action=...`) |
| Cambiar el nombre de la tabla | 4 archivos (hay SQL en `index`, `guardar`, `actualizar` y `eliminar`) | 1 (`functions.php`) | 1 (`TodoItem.php`) |

### Lo que muestra la comparación

- **A** es la más rápida de escribir y la más fácil de entender el primer día: cada archivo hace una cosa
  y se lee de arriba abajo. El problema aparece al crecer: el SQL está repartido en cuatro archivos y mezclado
  con HTML, así que un cambio en la base de datos obliga a buscar por todas partes.
- **B** separa por **tipo de código**: el SQL en funciones, el HTML en vistas. Un solo punto de entrada
  (`index.php`) decide qué hacer. Ya es mucho más fácil de mantener.
- **C** hace lo mismo que B, pero con **clases**: el modelo agrupa los datos de una tarea con las operaciones
  sobre ella, y el controlador agrupa las acciones. Es el patrón **MVC** (Modelo - Vista - Controlador).

Ninguna es "incorrecta". Para un ejercicio de una tarde, A está bien. Para algo que van a mantener
varias personas durante meses, C es mucho mejor.

### El camino continúa

`21-sweetAlert2` es la versión C llevada más lejos (validación, mensajes, seguridad). Y Laravel es esa misma
organización, pero ya construida:

| En C (`todo-list-poo`) | En `21-sweetAlert2` | En Laravel |
|---|---|---|
| `index.php` con `switch` | `index.php` con lista blanca de acciones | `routes/web.php` |
| `controllers/TodoController.php` | `controllers/EstudianteController.php` | `app/Http/Controllers/...` |
| `models/TodoItem.php` (escribe el SQL) | `models/EstudianteModel.php` (escribe el SQL) | `app/Models/...` (Eloquent escribe el SQL por ti) |
| `views/todo_list.php` | `views/estudiantes/index.php` | `resources/views/....blade.php` |
| `config/Database.php` | `config/Database.php` + `config.php` | `.env` + `config/database.php` |

## Trampa de PHP (viniendo de Python)

La versión A usa `include("db.php")`. Si ese archivo no existe, `include` solo muestra un **Warning y sigue
ejecutando**: el error de verdad aparece más abajo, como *"Call to a member function prepare() on null"*,
y parece que el problema está en la consulta. Con `require` el script se detiene **en la línea correcta**:
*"Failed opening required 'db.php'"*. En Python, un `import` que falla también se detiene de inmediato.
Para archivos sin los cuales la página no puede funcionar (conexión, configuración), usa `require_once`.

## Para analizar

1. En la versión A, ¿por qué `index.php` necesita `include("includes/header.php")` y la versión C no tiene
   nada parecido? ¿Cómo repetirías la cabecera en todas las vistas de C?
2. Agregar un campo nuevo (como la prioridad del resuelto 01): ¿cuántos archivos tocarías en B y en C?
   Haz la lista.
3. En C, el controlador hace `include 'views/todo_list.php'` y la vista usa `$todos`. ¿De dónde sale
   `$todos`? ¿Cómo hace `21-sweetAlert2` lo mismo de forma más ordenada? (busca `extract`)
4. Si tuvieras que explicarle MVC a alguien en tres frases usando la versión C, ¿qué dirías?
