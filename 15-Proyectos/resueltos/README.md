# Ejercicios resueltos para analizar — 15 Proyectos

Estos archivos **no son para copiar**: son para leer, ejecutar, romper y explicar.
Aquí ya no se trata de una función o una clase sueltas, sino de **proyectos con varios archivos** y una base
de datos: cómo se recorre un cambio por todo el proyecto, qué devuelve PDO, cómo se ataca un sistema mal
escrito y por qué los proyectos terminan organizados en MVC.

## Orden recomendado

| # | Archivo | Tipo | Qué vas a entender |
|---|---|---|---|
| 1 | `01-resuelto-agregar-prioridad.md` | Resuelto y comentado | Seguir un dato por todos los archivos de un CRUD para agregar un campo |
| 2 | `02-predice-pdo.php` | Predice la salida | Qué devuelven `fetch`, `fetchAll`, `rowCount`, `lastInsertId`... cuando no encuentran nada |
| 3 | `03-encuentra-error-buscador.php` | Encuentra el error | Inyección SQL y XSS en vivo, sobre una tabla temporal |
| 4 | `04-compara-tres-versiones.md` | Compara soluciones | Las tres versiones de la lista de tareas y su camino hacia MVC y Laravel |

## Requisitos

- `database/setup.sql` importado (base `tareas_crud`).
- `config.php` configurado en la raíz del proyecto.

Los archivos 2 y 3 crean una **tabla temporal** que desaparece sola al terminar: no modifican tus tareas.

## Cómo trabajar cada tipo

- **Resuelto y comentado:** antes de leer los pasos, haz tú la lista de archivos que crees que hay que
  tocar. Después sigue el resuelto y, si quieres, aplícalo de verdad al CRUD (en una copia).
- **Predice la salida:** **no ejecutes el archivo** hasta haber escrito tu predicción de cada caso.
- **Encuentra el error:** ejecútalo, mira qué pruebas fallan y corrige en el mismo archivo.
- **Compara soluciones:** llena la tabla de la Parte 1 abriendo las carpetas, y luego compara.

## Antes de empezar

Haz los resueltos de `08-superglobales`, `09-clases` y `12-sesion`. Después de estos estás listo para
`21-sweetAlert2`, que junta todo lo anterior en un proyecto MVC completo.
