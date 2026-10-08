# Ejercicios resueltos para analizar — 05 Arrays

Estos archivos **no son para copiar**: son para leer, ejecutar, romper y explicar.
En PHP un mismo tipo, el **array**, hace de lista y de diccionario. Eso es cómodo, pero trae comportamientos
que en Python no existen: claves que cambian de tipo, huecos después de borrar, funciones que pierden las claves.

## Orden recomendado

| # | Archivo | Tipo | Qué vas a entender |
|---|---|---|---|
| 1 | `01-resuelto-agrupar-por-ficha.php` | Resuelto y comentado | Agrupar datos en arrays anidados y calcular por grupo |
| 2 | `02-predice-claves.php` | Predice la salida | Claves `"1"` = `1`, huecos con `unset`, `+` frente a `array_merge`, `sort` frente a `asort`, `array_search` y el 0 |
| 3 | `03-encuentra-error-bodega.php` | Encuentra el error | Cuatro funciones con las trampas del archivo 2 |
| 4 | `04-compara-lista-vs-indice.php` | Compara soluciones | Buscar recorriendo frente a buscar por clave (`array_column`) |

## Cómo trabajar cada tipo

- **Resuelto y comentado:** lee el enunciado y "Cómo se pensó". Agrega un `print_r` para ver la estructura
  que se arma y compárala con la traza.
- **Predice la salida:** **no ejecutes el archivo** hasta haber escrito tu predicción de cada caso.
- **Encuentra el error:** ejecútalo, mira qué pruebas fallan y corrige en el mismo archivo.
- **Compara soluciones:** ejecútalo varias veces y explica por qué los números cambian.

Cada archivo tiene una sección **Trampa de PHP** y una sección **Para analizar** con preguntas tipo sustentación.

## Antes de empezar

Repasa `1.php` y las carpetas `indexado/` y `asociativo/`. Ten a mano `04-condicionales/resueltos/02`:
varias trampas de arrays vienen de cómo PHP compara con `==`.
