# Pestrada - Curso PHP

Repositorio educativo para aprender PHP desde cero hasta construir una aplicación MVC con base de datos,
como paso previo a Laravel.

## Estructura del proyecto

```
Pestrada/
├── 01-introPHP/                 # Sintaxis básica, echo, comentarios
├── 02-variables/                # Variables y tipos de datos
├── 03-operadores/               # Operadores aritméticos, de comparación y lógicos
├── 04-condicionales/            # if / else / switch (+ primer formulario)
├── 05-arrays/                   # Arrays indexados y asociativos
├── 06-ciclos/                   # for, while, foreach, break, continue
├── 07-funciones/                # Funciones, parámetros y retorno
├── 08-superglobales/            # $_GET, $_POST, $_SERVER
├── 09-clases/                   # Programación orientada a objetos
├── 10-ejemplos/                 # Ejemplos prácticos (calculadora, libros)
├── 11-miniproyecto/             # Login básico con base de datos
├── 12-sesion/                   # Sesiones, login seguro con CSRF
├── 13-taller/                   # Talleres (GET/POST, herencia)
├── 14-Investigacion/            # Documentos de investigación
├── 15-Proyectos/                # CRUD de tareas y Todo List (estructural y POO)
├── 16-strings/                  # Funciones de texto          + EJERCICIOS.md
├── 17-fechas/                   # date, strtotime, DateTime   + EJERCICIOS.md
├── 18-errores/                  # Excepciones                 + EJERCICIOS.md
├── 19-archivos/                 # Leer y escribir archivos    + EJERCICIOS.md
├── 20-formularios-validacion/   # Formularios y validación    + EJERCICIOS.md
├── 21-sweetAlert2/              # CRUD MVC con SweetAlert2    + EJERCICIOS.md
├── soluciones/                  # Soluciones de los ejercicios 16-21
├── database/setup.sql           # Crea todas las bases de datos del curso
├── config.example.php           # Plantilla de configuración (copiar como config.php)
└── helpers.php                  # Funciones auxiliares opcionales
```

## Requisitos

- WAMP (o XAMPP) con PHP 8.1 o superior y MySQL / MariaDB
- Extensiones de PHP: `pdo_mysql` y `mbstring` (vienen activas en WAMP)
- Un editor: PhpStorm o VS Code

## Instalación

### 1. Clonar el repositorio dentro de `www`

```bash
cd C:\wamp64\www
git clone <url-del-repositorio> Pestrada
```

### 2. Crear la configuración

Copia `config.example.php` y renómbralo como `config.php`. Ábrelo y pon la contraseña de **tu** MySQL:

```php
define('DB_PASS', '');   // en WAMP suele estar vacía
```

`config.php` no se sube al repositorio (está en `.gitignore`): cada persona tiene su propia contraseña.

### 3. Crear las bases de datos

En phpMyAdmin (`http://localhost/phpmyadmin`) ve a la pestaña **Importar** y selecciona `database/setup.sql`.
Se crean cuatro bases de datos:

| Base de datos | La usan |
|---|---|
| `tareas_crud` | `15-Proyectos/CRUD` |
| `todo_list` | `15-Proyectos/todo-list-estruct` y `todo-list-poo` |
| `login_db` | `11-miniproyecto` y `12-sesion` |
| `sena_mvc` | `21-sweetAlert2` |

Usuario de prueba para los login: **admin** / **admin123**.

### 4. Abrir el proyecto

- **Con WAMP:** enciende WAMP y entra a `http://localhost/Pestrada/`.
- **Con PhpStorm:** abre el archivo `.php` y usa el botón del navegador que aparece arriba a la derecha.

> En los proyectos con MVC (`15-Proyectos/todo-list-poo`, `21-sweetAlert2`) abre siempre el `index.php`
> de la carpeta, **nunca** un archivo de `views/`. Las vistas solo funcionan cuando las carga el controlador.

## Proyectos

### 15-Proyectos/CRUD — CRUD de tareas
Crear, listar, editar y eliminar tareas. PDO con consultas preparadas, Bootstrap 4 e `include` de
cabecera y pie. Mensajes de confirmación guardados en la sesión.

### 15-Proyectos/todo-list-estruct — Todo List estructural
La misma idea organizada con funciones (`includes/functions.php`) y vistas separadas.

### 15-Proyectos/todo-list-poo — Todo List con POO
Clases `Database`, `TodoItem` (modelo) y `TodoController`: primer acercamiento a MVC.

### 21-sweetAlert2 — CRUD de estudiantes con MVC
Router (`index.php`) con lista blanca de acciones, controlador, modelo con PDO y vistas.
Alertas con SweetAlert2, eliminación por POST y protección CSRF. Es la base conceptual de Laravel.
Su manual paso a paso está en `21-sweetAlert2/manual_sweetalert2_mvc.txt`.

## Ejercicios resueltos para analizar

Algunos módulos tienen una carpeta `resueltos/` con ejercicios pensados para **leer, ejecutar y explicar**
antes de practicar solos. Hay cinco tipos: resuelto y comentado, predice la salida, encuentra el error,
compara soluciones y sigue el recorrido. Cada carpeta tiene un `README.md` con el orden recomendado.

| Módulo | Carpeta |
|---|---|
| 04 Condicionales | `04-condicionales/resueltos/` |
| 05 Arrays | `05-arrays/resueltos/` |
| 06 Ciclos | `06-ciclos/resueltos/` |
| 07 Funciones | `07-funciones/resueltos/` |
| 08 Superglobales | `08-superglobales/resueltos/` |
| 09 Clases | `09-clases/resueltos/` |
| 12 Sesión | `12-sesion/resueltos/` |
| 15 Proyectos | `15-Proyectos/resueltos/` |
| 13 Talleres | `13-taller/resueltos/` (modelos de sustentación con rúbrica) |

Orden sugerido en cada módulo: **ejemplos → resueltos → ejercicios → soluciones**.

## Ejercicios y soluciones

Los módulos 16 a 21 tienen un `EJERCICIOS.md` con 12 ejercicios cada uno, en tres niveles
(básico, intermedio y reto). Las soluciones están en [`soluciones/`](soluciones/README.md).

La idea es **practicar de manera consciente**: intentar cada ejercicio primero y usar la solución para
comparar, no para copiar.

El módulo 21 es el proyecto del fin de semana: los ejercicios se acumulan hasta tener buscador, paginación,
mensajes en sesión y un segundo módulo relacionado. El resultado completo está en `soluciones/21-proyecto-final/`.

## Buenas prácticas que se aplican en el curso

- Consultas preparadas con PDO para evitar inyección SQL.
- `htmlspecialchars()` en todo dato que se imprime en HTML (y `json_encode()` dentro de `<script>`).
- Contraseñas con `password_hash()` / `password_verify()`.
- Las acciones que modifican datos (guardar, editar, eliminar) solo se aceptan por POST.
- Token CSRF en los formularios (`12-sesion`, `21-sweetAlert2`).
- Credenciales fuera del código, en `config.php`.

## Solución de problemas

| Problema | Causa probable |
|---|---|
| `Access denied for user 'root'` | La contraseña de `config.php` no es la de tu MySQL. |
| `Unknown database` o `Table ... doesn't exist` | Falta importar `database/setup.sql`. |
| `Failed opening required ... config.php` | Falta copiar `config.example.php` como `config.php`. |
| `Headers already sent` | Hay un espacio, una línea en blanco o un `echo` antes de `header()` o de `session_start()`. |
| La página se ve sin estilos y con *Undefined variable* | Se abrió una vista (`views/...`) en lugar del `index.php`. |
| Un ejemplo dice "se ejecuta en la consola" (`06-ciclos/while/1.php`, `15-Proyectos/CRUD/D.PHP`) | Lee del teclado con `readline()` o `STDIN`: ejecútalo con `php archivo.php` en la terminal. |

## Recursos

- [Manual oficial de PHP (español)](https://www.php.net/manual/es/)
- [PHP: The Right Way](https://phptherightway.com/)
- [SweetAlert2](https://sweetalert2.github.io/)
