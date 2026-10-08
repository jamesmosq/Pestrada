# Ejercicios resueltos para analizar — 04 Condicionales

Estos archivos **no son para copiar**: son para leer, ejecutar, romper y explicar.
Los `if` existen en todos los lenguajes, pero PHP **compara y decide "verdadero o falso"** distinto de
Python. Este módulo se concentra en esas diferencias, que causan muchos errores silenciosos.

## Orden recomendado

| # | Archivo | Tipo | Qué vas a entender |
|---|---|---|---|
| 1 | `01-resuelto-factura-estrato.php` | Resuelto y comentado | Validar primero, `if/elseif` para reglas distintas, `match` para valores exactos |
| 2 | `02-predice-comparaciones.php` | Predice la salida | `==` frente a `===`, qué cuenta como falso (`"0"`), `switch` frente a `match`, `??` frente a `?:` |
| 3 | `03-encuentra-error-boleta.php` | Encuentra el error | Orden de los `elseif`, `&&` frente a `\|\|`, `break` olvidado, `match` incompleto |
| 4 | `04-compara-if-switch-match-array.php` | Compara soluciones | Cuándo usar `if`, `switch`, `match` o un array como tabla |

**Empieza por el 02** si vienes de Python: es el que más sorpresas trae.

## Cómo trabajar cada tipo

- **Resuelto y comentado:** lee el enunciado y "Cómo se pensó", luego haz la traza en tu cuaderno.
- **Predice la salida:** **no ejecutes el archivo** hasta haber escrito tu predicción de cada caso.
- **Encuentra el error:** ejecútalo, mira qué pruebas fallan y corrige en el mismo archivo.
- **Compara soluciones:** las cuatro funcionan; decide cuál usarías en cada situación y por qué.

Cada archivo tiene una sección **Trampa de PHP** y una sección **Para analizar** con preguntas tipo sustentación.

## Antes de empezar

Repasa `1.php` de esta carpeta. Los ejercicios de `2.php` (edad, número positivo, día de la semana...)
los puedes resolver después de estos resueltos.
