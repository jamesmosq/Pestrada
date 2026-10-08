# Ejercicios resueltos para analizar — 06 Ciclos

Estos archivos **no son para copiar**: son para leer, ejecutar, romper y explicar.
Los ciclos se parecen mucho a los de Python, pero hay detalles que cambian el resultado: `range()` incluye
el final, `foreach` recorre una copia, existen `do-while` y `break 2`, y una referencia `&` puede dañar un array.

## Orden recomendado

| # | Archivo | Tipo | Qué vas a entender |
|---|---|---|---|
| 1 | `01-resuelto-cajero-billetes.php` | Resuelto y comentado | Un `while` dentro de un `foreach`; cuándo usar cada uno; `intdiv` y `%` |
| 2 | `02-predice-ciclos.php` | Predice la salida | `range()`, `<=` frente a `<`, `do-while`, `break 2`, decimales en ciclos, el `foreach` con `&` |
| 3 | `03-encuentra-error-ciclos.php` | Encuentra el error | Acumulador mal ubicado, `range` traducido de Python, ciclos anidados con la misma variable, falta de `break` |
| 4 | `04-compara-while-for-dowhile.php` | Compara soluciones | El mismo problema con `while`, `for` + `break` y `do-while` |

## Cómo trabajar cada tipo

- **Resuelto y comentado:** lee el enunciado y "Cómo se pensó", y haz la traza para otro monto.
- **Predice la salida:** **no ejecutes el archivo** hasta haber escrito tu predicción de cada caso.
- **Encuentra el error:** ejecútalo, mira qué pruebas fallan y corrige en el mismo archivo.
- **Compara soluciones:** decide qué ciclo usarías en cada situación y por qué.

Cada archivo tiene una sección **Trampa de PHP** y una sección **Para analizar** con preguntas tipo sustentación.

## Antes de empezar

Repasa `for.php` y las carpetas `for/`, `while/`, `foreach/`, `break/` y `continue/`.
Recuerda que `while/1.php` se ejecuta en la consola (`php 1.php`), no en el navegador.
