# Ejercicios resueltos para analizar — 07 Funciones

Estos archivos **no son para copiar**: son para leer, ejecutar, romper y explicar.
Cada uno muestra cómo se piensa un problema, no solo cómo se escribe.

## Orden recomendado

| # | Archivo | Tipo | Qué vas a entender |
|---|---|---|---|
| 1 | `01-resuelto-nota-final.php` | Resuelto y comentado | Dividir un problema en funciones pequeñas, validar antes de calcular, lanzar excepciones |
| 2 | `02-predice-alcance.php` | Predice la salida | Qué variables ve una función, copia frente a referencia, `static`, arrow functions |
| 3 | `03-encuentra-error-carrito.php` | Encuentra el error | Cuatro errores que PHP no avisa y cómo detectarlos con pruebas |
| 4 | `04-compara-foreach-vs-funciones.php` | Compara dos soluciones | `foreach` frente a `array_filter` / `array_column`: cuándo usar cada uno |

## Cómo trabajar cada tipo

- **Resuelto y comentado:** lee primero el enunciado y la parte "Cómo se pensó". Antes de leer el código,
  intenta imaginar cómo lo harías tú. Después haz la traza en tu cuaderno y compárala.
- **Predice la salida:** **no ejecutes el archivo** hasta haber escrito tu predicción de cada caso.
  Acertar no es lo importante; lo importante es entender por qué fallaste.
- **Encuentra el error:** ejecútalo, mira qué pruebas fallan y corrige el código en el mismo archivo.
  La versión corregida está al final; mírala solo cuando todas las pruebas digan OK.
- **Compara dos soluciones:** las dos funcionan. Decide cuál prefieres y defiende por qué.

Cada archivo tiene una sección **Trampa de PHP**, con la diferencia frente a Python que más errores causa
en ese tema, y una sección **Para analizar**, con preguntas como las de una sustentación.

## Antes de empezar

Repasa los ejemplos `1.php` a `4.php` de esta carpeta. Después de los resueltos, ya estás listo para
escribir tus propias funciones sin ayuda.
