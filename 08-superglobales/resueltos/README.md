# Ejercicios resueltos para analizar — 08 Superglobales

Estos archivos **no son para copiar**: son para leer, ejecutar, romper y explicar.
En este módulo empieza lo más distinto a Python: un programa que se ejecuta **una vez por cada petición**
del navegador y recibe los datos como texto.

## Orden recomendado

| # | Archivo | Tipo | Qué vas a entender |
|---|---|---|---|
| 1 | `01-resuelto-dividir-cuenta.php` | Resuelto y comentado | Un archivo que muestra y procesa su formulario; validar, conservar datos y escapar |
| 2 | `02-predice-datos-recibidos.php` | Predice la salida | Qué llega realmente en `$_GET` / `$_POST`: textos, casillas que no llegan, `empty('0')`, `[]` |
| 3 | `03-encuentra-error-inscripcion.php` | Encuentra el error | Cuatro errores típicos al procesar formularios |
| 4 | `04-recorrido-encuesta.php` | Sigue el recorrido | Las tres peticiones de un formulario con Post/Redirect/Get, vistas en F12 |

## Cómo trabajar cada tipo

- **Resuelto y comentado:** lee el enunciado y "Cómo se pensó". Ejecútalo, prueba datos buenos y malos,
  y haz la traza.
- **Predice la salida:** **no ejecutes el archivo** hasta haber escrito tu predicción de cada caso.
- **Encuentra el error:** ejecútalo, mira qué pruebas fallan y corrige en el mismo archivo. Al arreglar
  un error pueden aparecer otros que estaban escondidos: eso es normal.
- **Sigue el recorrido:** ábrelo con F12 → pestaña Red, llena la tabla del final y luego compara.

Cada archivo tiene una sección **Trampa de PHP** (la diferencia frente a Python que más errores causa)
y una sección **Para analizar** con preguntas tipo sustentación.

## Antes de empezar

Repasa `suma.html` + `suma.php` y `2.html` + `3.php` de esta carpeta. Después de estos resueltos
estás listo para `12-sesion` y para los formularios de `20-formularios-validacion`.
