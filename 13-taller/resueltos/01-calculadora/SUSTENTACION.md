# Sustentación modelo — Taller 1, ejercicio 1: Calculadora básica

Este documento muestra **cómo se sustenta** el código de esta carpeta. No es un guion para memorizar:
es un ejemplo del nivel de explicación que se espera. Tu sustentación debe hablar de **tu** código.

## 1. Qué hace, en 30 segundos

> "Es una calculadora de dos archivos. `formulario.html` muestra dos campos numéricos y un selector de
> operación, y envía los datos por POST a `calcular.php`. Ese archivo valida que los dos valores sean números
> y que la operación sea una de las cuatro permitidas, controla la división entre cero, calcula con un `match`
> y muestra el resultado o el error, con un enlace para volver."

Fíjate en lo que tiene ese resumen: **qué hace**, **cómo está organizado** y **qué casos difíciles cubre**.

## 2. El recorrido de una ejecución

| Paso | Qué pasa | Dónde |
|---|---|---|
| 1 | El navegador pide `formulario.html` (GET) y lo muestra | `formulario.html` |
| 2 | El usuario llena el formulario y presiona Calcular | — |
| 3 | El navegador envía `num1`, `operacion` y `num2` por **POST** a `calcular.php` | atributos `action` y `method` del `<form>` |
| 4 | PHP revisa que la petición sea POST; si no, redirige al formulario | bloque 1 |
| 5 | Lee los datos de `$_POST` como texto | bloque 2 |
| 6 | Valida y convierte: números, operación permitida, división entre cero | bloque 3 |
| 7 | Calcula con `match` | bloque 4 |
| 8 | Muestra el resultado o el error en HTML | bloque 5 |

Poder contar este recorrido **sin mirar el código** es la mejor señal de que lo entiendes.

## 3. Las decisiones, y por qué se tomaron

| Decisión | Por qué |
|---|---|
| `if ($_SERVER['REQUEST_METHOD'] !== 'POST')` al inicio | Si alguien abre `calcular.php` directamente, no hay datos: en vez de mostrar errores, se le devuelve al formulario. |
| `$_POST['num1'] ?? ''` | Si un campo no llega, `$_POST['num1']` daría un *Warning*. Con `??` se usa un texto vacío. |
| `filter_var(..., FILTER_VALIDATE_FLOAT)` | Todo lo que llega es texto. `filter_var` convierte y valida a la vez: devuelve el número o `false`. |
| `str_replace(',', '.', ...)` | En Colombia escribimos `2,5`; PHP necesita `2.5`. |
| Comparar con `=== false` | Si el usuario escribe `0`, `filter_var` devuelve `0.0`, y `0.0` es "falso". Con `=== false` solo se rechaza lo que de verdad no es un número. |
| Lista blanca `$simbolos` | El `<select>` se puede modificar con F12. Solo se aceptan las cuatro operaciones que conocemos. |
| Controlar la división entre cero **antes** de calcular | En PHP 8, `5 / 0` lanza `DivisionByZeroError` y detiene la página. |
| `match` para calcular | Cada operación devuelve un valor; `match` es más corto que cuatro `if` y compara con `===`. |
| `round($resultado, 10)` | `0.1 + 0.2` da `0.30000000000000004` en la computadora; redondeando se muestra `0.3`. |
| `htmlspecialchars($error)` | Buena costumbre: todo texto que se imprime en HTML se escapa. |
| El símbolo sale de `$simbolos[$operacion]` | Nunca se imprime lo que mandó el usuario, sino un valor de nuestra lista. |

## 4. Preguntas que te pueden hacer, y una buena respuesta

**¿Por qué POST y no GET?**
Con GET los números quedarían en la URL. Para una calculadora no es grave, pero el enunciado pide POST, y
POST es lo correcto cuando se *envían* datos para procesarlos. GET es para *pedir* información (como un buscador).

**Si el input es `type="number"`, ¿para qué validas en PHP?**
Porque la validación del navegador se puede saltar: con F12 se cambia el tipo del input, o se envía la
petición sin usar el formulario. La validación que cuenta es la del servidor.

**¿Qué pasa si divido entre cero?**
Muestra "No se puede dividir entre cero" sin intentar la división. Si no se controlara, PHP 8 lanzaría un
`DivisionByZeroError` y el usuario vería un *Fatal error*.

**¿Qué pasa si abro `calcular.php` directamente en el navegador?**
Llega una petición GET, entonces el primer `if` redirige a `formulario.html`.

**¿Por qué `match` y no `switch`?**
`match` devuelve el valor directamente, no necesita `break` y compara con `===`. Un `switch` también
funcionaría, pero es más largo y es fácil olvidar un `break`.

**Muéstrame dónde se valida que la operación sea correcta. ¿Qué pasa si envío "potencia"?**
En `array_key_exists($operacion, $simbolos)`. "potencia" no es una clave de la lista, así que se muestra
"La operación no es válida".

**¿Qué mejorarías?**
Una buena respuesta muestra que conoces los límites de tu código:
- Mostrar el resultado en el mismo archivo del formulario, conservando los números escritos.
- Formatear el resultado con separador de miles (`number_format`).
- Agregar operaciones (potencia, porcentaje) solo con una línea en `$simbolos` y otra en el `match`.

## 5. Cómo demostrarlo en vivo (30 % de ejecución)

Prepara estos casos **antes** de la sustentación y muéstralos en este orden:

| Caso | Datos | Debe mostrar |
|---|---|---|
| Normal | 8 + 2 | `8 + 2 = 10` |
| Decimales con coma | 2,5 x 4 | `2.5 x 4 = 10` |
| División | 7 / 2 | `7 / 2 = 3.5` |
| Precisión | 0.1 + 0.2 | `0.1 + 0.2 = 0.3` |
| División entre cero | 5 / 0 | "No se puede dividir entre cero." |
| Acceso directo | abrir `calcular.php` en la barra de direcciones | vuelve al formulario |
| Manipulación | con F12 cambiar una opción a `value="potencia"` | "La operación no es válida." |

Los tres últimos son los que más demuestran: enseñan que pensaste en lo que **puede salir mal**.

## 6. Errores comunes que bajan la nota

- Leer `$_POST['num1']` sin validar y sumar directamente (`"abc" + 2` lanza un error en PHP 8).
- No controlar la división entre cero.
- Usar `if ($num1)` para validar: rechaza el 0.
- Imprimir `$_POST['operacion']` directamente en la página.
- No saber explicar una línea del propio código. Si no puedes explicarla, no la entregues.
