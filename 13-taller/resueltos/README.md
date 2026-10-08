# Talleres resueltos — modelos de sustentación

En los talleres de esta carpeta, la **sustentación vale el 70 %** y la ejecución el 30 %. Por eso aquí no solo
hay código resuelto: cada ejercicio trae un `SUSTENTACION.md` que muestra **cómo se explica** ese código.

| Carpeta | Taller | Archivos |
|---|---|---|
| `01-calculadora/` | Taller 1 (GET/POST), ejercicio 1 | `formulario.html`, `calcular.php`, `SUSTENTACION.md` |
| `02-vehiculos/` | Taller 2 (herencia), ejercicio 1 | `Vehiculo.php`, `Automovil.php`, `Motocicleta.php`, `Camion.php`, `index.php`, `SUSTENTACION.md` |

Estos dos ejercicios quedan como **modelo**. Los demás ejercicios de cada taller los resuelves tú, siguiendo
el mismo método.

## Método para preparar cualquier sustentación

Antes de sustentar, asegúrate de poder responder estas seis preguntas **sin mirar el código**:

1. **¿Qué hace?** En 30 segundos: qué problema resuelve, cómo está organizado y qué casos difíciles cubre.
2. **¿Cómo está organizado?** Qué archivos o clases hay y qué hace cada uno.
3. **¿Cuál es el recorrido?** Qué pasa, en orden, desde que el usuario abre la página (o se ejecuta el script)
   hasta que ve el resultado.
4. **¿Por qué así?** Para cada decisión importante (`POST`, `match`, `protected`, `abstract`...), una razón.
   "Porque así lo vi" no es una razón.
5. **¿Qué pasa si algo sale mal?** Datos vacíos, texto donde va un número, división entre cero, abrir un
   archivo directamente, modificar el formulario con F12.
6. **¿Qué mejorarías?** Conocer los límites de tu código demuestra que lo entiendes.

Y prepara la **demostración**: una lista de casos para mostrar en vivo, incluidos los casos de error.

## Rúbrica sugerida para la sustentación (70 %)

| Criterio | Peso | Excelente | Aceptable | Insuficiente |
|---|---|---|---|---|
| **Explica qué hace y cómo está organizado** | 15 % | Resume en pocas frases y señala cada archivo o clase con su función | Lo explica, pero leyendo el código | No sabe describir la organización |
| **Recorrido de la ejecución** | 15 % | Cuenta el recorrido completo en orden, sin mirar | Lo cuenta con ayuda o con saltos | No sabe en qué orden se ejecuta |
| **Justifica las decisiones** | 20 % | Da una razón técnica para cada decisión importante | Justifica algunas | "Así lo encontré" / no sabe |
| **Manejo de errores y casos límite** | 10 % | Muestra y explica qué pasa con datos inválidos | Cubre algunos casos | No los consideró |
| **Responde preguntas sobre su código** | 10 % | Ubica rápido cualquier línea y explica qué hace | Responde con dificultad | No puede explicar líneas de su propio código |

**Total sustentación: 70 %.** La ejecución (30 %) se evalúa con la demostración en vivo.

## Cómo usar los modelos

1. Lee el enunciado del ejercicio en `../01-taller-get-post` o `../02-taller-herencia`.
2. **Antes** de abrir el código, intenta responder tú las seis preguntas del método: ¿cómo lo harías?
3. Lee el código y luego el `SUSTENTACION.md`. Compara su respuesta con la tuya.
4. Practica en voz alta: explícale el código a un compañero en 3 minutos, y que él te haga las preguntas
   de la sección 4 del `SUSTENTACION.md`.
5. Aplica el mismo método a tu propio ejercicio.
