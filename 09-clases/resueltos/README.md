# Ejercicios resueltos para analizar — 09 Clases

Estos archivos **no son para copiar**: son para leer, ejecutar, romper y explicar.
Cada uno muestra cómo se piensa un problema con objetos, no solo cómo se escribe una clase.

## Orden recomendado

| # | Archivo | Tipo | Qué vas a entender |
|---|---|---|---|
| 1 | `01-resuelto-inventario.php` | Resuelto y comentado | De un enunciado a clases y métodos; por qué los atributos son `private`; validar en el constructor |
| 2 | `02-predice-objetos.php` | Predice la salida | Cuándo dos variables son el mismo objeto, `clone`, `static`, encadenar métodos, `==` frente a `===` |
| 3 | `03-encuentra-error-herencia.php` | Encuentra el error | `parent::__construct`, `private` frente a `protected`, `$this->`, `parent::` |
| 4 | `04-compara-estructurado-vs-poo.php` | Compara dos soluciones | El mismo carrito con arrays y funciones, y con una clase: qué gana cada uno |

## Cómo trabajar cada tipo

- **Resuelto y comentado:** lee primero el enunciado y la parte "Cómo se pensó". Antes de leer el código,
  intenta escribir en tu cuaderno qué clases y qué métodos crearías. Después compara.
- **Predice la salida:** **no ejecutes el archivo** hasta haber escrito tu predicción de cada caso.
- **Encuentra el error:** ejecútalo, mira qué pruebas fallan y corrige el código en el mismo archivo.
  La versión corregida está al final; mírala solo cuando todas las pruebas digan OK.
- **Compara dos soluciones:** las dos funcionan. Decide cuál prefieres y defiende por qué.

Cada archivo tiene una sección **Trampa de PHP**, con la diferencia frente a Python que más errores causa
en ese tema, y una sección **Para analizar**, con preguntas como las de una sustentación.

## Antes de empezar

Repasa los ejemplos `1.php` a `7.php` de esta carpeta y haz primero los resueltos de `07-funciones/resueltos/`:
el resuelto 02 de aquí se compara con lo que viste allá sobre copias y referencias.

## Relación con lo que viene

Lo que practicas aquí es exactamente lo que usa `21-sweetAlert2`: una clase por responsabilidad
(`EstudianteModel`, `EstudianteController`), atributos privados, constructores y métodos que se llaman entre sí.
Laravel está construido de la misma forma.
