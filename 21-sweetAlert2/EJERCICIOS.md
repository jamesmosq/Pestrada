# Ejercicios — 21 SweetAlert2 + MVC (proyecto del fin de semana)

Estos ejercicios **se acumulan**: al terminar tendrás una aplicación con buscador, paginación,
mensajes en sesión y dos módulos relacionados, que es justo lo que verás después en Laravel.

## Antes de empezar

1. Copia la carpeta `21-sweetAlert2` completa y llámala `21-mi-proyecto`. **Trabaja siempre en la copia.**
2. Ábrela en `http://localhost/Pestrada/21-mi-proyecto/` y comprueba que funciona igual que la original.
   **Siempre se abre `index.php` (el router), nunca un archivo de `views/`.** En PhpStorm, el botón de
   "abrir en el navegador" abre el archivo que estás editando: si estás en una vista, verás la página sin estilos
   y con errores. Abre `index.php` y usa ese botón desde ahí.
3. Repasa la ruta de una petición:
   `index.php` (router) → `Controller` → `Model` → `Controller` → `View`.

| Nivel | Ejercicios | Archivos que tocas |
|---|---|---|
| Básico | 1 – 4 | Una vista o un método |
| Intermedio | 5 – 9 | Modelo + controlador + vistas |
| Reto | 10 – 12 | Varias capas y la base de datos |

> **Regla de MVC:** el SQL va **solo** en el modelo; el HTML, **solo** en las vistas; el controlador coordina.
> Si en una vista escribes `SELECT` o en un modelo escribes `<div>`, algo está en el lugar equivocado.

---

## 1. Toasts para los mensajes de éxito

Los mensajes de *creado / editado / eliminado* abren un modal en el centro. Cámbialos por un **toast**:
un aviso pequeño en la esquina superior derecha que desaparece solo a los 3 segundos.
Los **errores** siguen siendo modales (el usuario debe leerlos).

**Pista:** opciones `toast: true`, `position: 'top-end'`, `timer`. Documentación: <https://sweetalert2.github.io/#examples>.
**Archivo:** `views/estudiantes/index.php`.

---

## 2. Contador de estudiantes

Debajo del título *Gestión de Estudiantes* muestra `3 estudiantes registrados` (y `1 estudiante registrado` en singular).

- El número sale de un método nuevo del modelo, `contar(): int`, con `SELECT COUNT(*)`.
- **No** uses `count($estudiantes)` en la vista: en el ejercicio 10 la lista tendrá solo 5 por página y el total sería falso.

---

## 3. Validar la ficha

La ficha es opcional, pero si se escribe debe tener **exactamente 7 dígitos**.

- En el servidor: valida en `guardar()` y en `actualizar()`. Si falla, vuelve al formulario con
  el error `La ficha debe tener exactamente 7 dígitos.`
- En el navegador: agrega `pattern="[0-9]{7}"` y `maxlength="7"` al input.

Ahora `guardar()` y `actualizar()` repiten las mismas validaciones. Muévelas a un método privado
`validar(array $datos): ?string` que devuelva el primer error o `null`.

---

## 4. Cambios sin guardar

En **Editar**, si el usuario modificó algún campo y presiona *Volver*, muestra una confirmación de SweetAlert2:
*"Tienes cambios sin guardar"* con los botones **Salir sin guardar** / **Seguir editando**.
Si no cambió nada, *Volver* funciona normal.

**Pista:** al cargar la página guarda una "foto" del formulario:
`new URLSearchParams(new FormData(form)).toString()`; al presionar Volver, compárala con la actual.

---

## 5. Nuevo campo: celular

1. Agrega la columna (phpMyAdmin → SQL):
   ```sql
   ALTER TABLE estudiantes ADD telefono VARCHAR(10) NULL AFTER ficha;
   ```
2. **Modelo:** incluye `telefono` en el `INSERT` y en el `UPDATE`.
3. **Controlador:** recíbelo, quítale espacios y guiones, y valida (si no está vacío) que sea un celular
   colombiano: 10 dígitos que empiezan por 3. Si viene vacío, guarda `NULL`.
4. **Vistas:** agrega el input en *crear* y en *editar* (con su valor actual).

Prueba guardar `300 123 4567`: en la base de datos debe quedar `3001234567`.

---

## 6. Buscador

Agrega arriba de la tabla un buscador que filtre por **nombre o email**.

- El formulario usa `method="GET"`: así la búsqueda queda en la URL y se puede recargar o compartir.
- El modelo recibe el texto y usa `LIKE` **con consulta preparada** (`'%' . $buscar . '%'` va en el parámetro, no en el SQL).
- El contador del ejercicio 2 debe contar solo los resultados: `2 estudiantes encontrados`.
- Si no hay resultados: `Ningún estudiante coincide con "xyz".` y un botón **Limpiar**.

Prueba buscar `gom`: ¿aparece *Gómez*? ¿Por qué MySQL ignora la tilde?

---

## 7. Vista de detalle

Al hacer clic en el nombre de un estudiante, abre una página con todos sus datos
(nombre, email, ficha, celular y fecha de registro con formato `dd/mm/aaaa HH:mm`).

- Acción nueva `ver` → agrégala a la **lista blanca** del router (`index.php`). ¿Qué pasa si se te olvida?
- Método `ver()` en el controlador y vista `views/estudiantes/ver.php`.
- Si el `id` no existe, vuelve al listado con el error *Estudiante no encontrado*.

> **Puente a Laravel:** En Laravel, las acciones `index`, `create`, `store`, `show`, `edit`, `update` y `destroy` se llaman *resource*.
> Ya tienes las 7: compáralas con las tuyas.

---

## 8. Confirmar escribiendo el nombre

Hoy basta un clic en "Sí, eliminar". Haz que, para eliminar, el usuario tenga que **escribir el nombre exacto**
del estudiante en la alerta (como hace GitHub al borrar un repositorio). Si no coincide, la alerta muestra
*"El nombre no coincide"* y no deja continuar.

**Pista:** SweetAlert2 tiene `input: 'text'` e `inputValidator`.

---

## 9. Ordenar por columnas

Haz que los encabezados **#, Nombre, Email y Ficha** sean enlaces que ordenen la tabla. Un clic ordena ascendente
(`▲`) y otro clic descendente (`▼`). La búsqueda del ejercicio 6 **no** se debe perder al ordenar.

**Seguridad:** `ORDER BY` no acepta parámetros `?`. Si haces `"ORDER BY " . $_GET['orden']`, cualquiera puede
inyectar SQL. Usa una **lista blanca**:

```php
const ORDENABLES = ['id' => 'id', 'nombre' => 'nombre', 'email' => 'email', 'ficha' => 'ficha'];
```

Prueba la URL `?orden=nombre;DROP TABLE estudiantes`: debe ordenar por id y no pasar nada más.

---

## 10. Paginación

Muestra **5 estudiantes por página** con enlaces `« Anterior  1  2  3  Siguiente »` (la página actual resaltada).

- El modelo recibe `$limite` y `$offset` y los usa en `LIMIT :limite OFFSET :offset`, enlazados con
  `bindValue(..., PDO::PARAM_INT)`. ¿Qué pasa si los pasas como texto?
- `offset = (pagina - 1) * 5`. Total de páginas: `ceil(total / 5)`.
- Una página fuera de rango (`?pagina=99` o `?pagina=-3`) debe mostrar la última o la primera, sin errores.
- Los enlaces de página conservan la búsqueda y el orden, y al buscar u ordenar se vuelve a la página 1.

**Pista:** `http_build_query(array_merge($parametrosActuales, ['pagina' => 2]))` arma la URL.
Agrega más estudiantes de prueba para tener al menos 3 páginas.

---

## 11. Mensajes en la sesión (flash)

Hoy los mensajes viajan en la URL: `?status=creado&mensaje=El+estudiante...`. Eso es feo, se puede falsificar
(prueba poner tu propio mensaje en la URL) y el mensaje vuelve a salir si recargas.

1. En el controlador, crea `flash(string $tipo, string $mensaje)`, que lo guarde en `$_SESSION['flash']`.
2. Simplifica `redirigir()` para que solo reciba la acción y el id.
3. En `cargarVista()`, lee el flash, pásalo a la vista y **bórralo** de la sesión.
4. Las tres vistas tenían el mismo código de SweetAlert2: muévelo a **un solo archivo**
   `views/partials/flash.php` e inclúyelo con `require`.
5. Comprueba: la URL queda limpia y al recargar el mensaje ya no aparece.

> **Puente a Laravel:** En Laravel: `return redirect()->route('estudiantes.index')->with('creado', 'Mensaje');`
> y en la vista `@include('partials.flash')`.

---

## 12. Segundo módulo: cursos (con relación)

Un estudiante pertenece a un curso. Construye el módulo **Cursos** y relaciónalo con los estudiantes.

**Base de datos:**

```sql
CREATE TABLE cursos (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);
INSERT INTO cursos (nombre) VALUES ('ADSO'), ('Programacion de Software'), ('Gestion de Redes');

ALTER TABLE estudiantes
    ADD curso_id INT NULL,
    ADD CONSTRAINT fk_estudiantes_curso FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE SET NULL;
```

**Lo que debe hacer:**

1. Página de cursos (`index.php?c=cursos`): lista de cursos con **cuántos estudiantes** tiene cada uno, formulario
   para agregar uno y botón para eliminar (con SweetAlert2 y por POST).
2. El router decide el controlador según `?c=` (`estudiantes` por defecto) y cada controlador tiene su lista blanca de acciones.
3. En *crear* y *editar* estudiante, un `<select>` con los cursos (opción "Sin curso"). Valida en el servidor que el curso exista.
4. En el listado, una columna **Curso** (con `LEFT JOIN`) que también se pueda ordenar.
5. Al eliminar un curso, sus estudiantes quedan *sin curso* (eso lo hace `ON DELETE SET NULL`).

**Para no repetir código:** `cargarVista`, `redirigir`, `flash` y el CSRF ahora los necesitan los dos controladores.
Muévelos a una clase padre `abstract class Controller` y haz que `EstudianteController` y `CursoController`
la extiendan (`extends Controller`). Es la herencia que viste en `09-clases` y `13-taller`.

> **Puente a Laravel:** Esto en Laravel es: `class CursoController extends Controller`, `Route::resource('cursos', ...)`
> y en el modelo `public function curso() { return $this->belongsTo(Curso::class); }`.
> Cuando lo veas, ya sabrás qué SQL hay detrás.

---

## Lista de chequeo final

- [ ] Ninguna acción que modifica datos funciona por GET.
- [ ] Todos los formularios POST llevan el token CSRF.
- [ ] Todo dato que se imprime en HTML pasa por `htmlspecialchars` (o `json_encode` dentro de `<script>`).
- [ ] Todas las consultas con datos del usuario son preparadas.
- [ ] No hay SQL en las vistas ni HTML en los modelos.
- [ ] Con F12 → Consola abierta, no aparece ningún error de JavaScript.
