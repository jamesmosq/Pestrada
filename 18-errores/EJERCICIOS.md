# Ejercicios — 18 Manejo de errores

Antes de empezar, repasa `1.php` (try / catch / finally), `2.php` (excepciones propias)
y `3.php` (jerarquía y varios catch).

**Cómo trabajar:** carpeta `ejercicios/`, un archivo por ejercicio.

| Nivel | Ejercicios |
|---|---|
| Básico | 1 – 4 |
| Intermedio | 5 – 9 |
| Reto | 10 – 12 |

> **Idea central:** un error sin capturar detiene **todo** el script y el usuario ve un mensaje feo
> (o, peor, información interna como rutas y contraseñas). Capturarlo nos deja decidir qué mostrar.

---

## 1. División segura

En PHP 8, `10 / 0` lanza un `DivisionByZeroError`. Recorre este array y muestra el resultado de cada división.
Si una falla, muestra un mensaje amigable y **sigue** con las demás.

```php
$operaciones = [[10, 2], [7, 0], [9, 3], [5, 0]];
```

**Salida esperada:**

```text
10 / 2 = 5
7 / 0 → No se puede dividir entre cero
9 / 3 = 3
5 / 0 → No se puede dividir entre cero
```

**Pista:** el `try` va **dentro** del `foreach`. ¿Qué pasa si lo pones afuera?

---

## 2. Validar edades

Crea `validarEdad(int $edad): int` que lance `InvalidArgumentException` si la edad es menor que 0 o mayor
que 120. Pruébala con `[25, -3, 130, 0, 80]` y cuenta cuántas fueron válidas.

**Salida esperada (final):** `Edades válidas: 3 de 5`

---

## 3. Convertir texto a número

Lo que llega de un formulario siempre es texto. Crea `aEntero(string $valor): int` que:

- Lance `InvalidArgumentException("'abc' no es un número entero")` si el texto no es un entero.
- Devuelva el entero si sí lo es.

Prueba con `"42"`, `"  7 "`, `"3.5"`, `"abc"`, `""`.

**Pista:** `filter_var(trim($valor), FILTER_VALIDATE_INT)` devuelve `false` si no es entero.
Ojo: `"0"` es válido y `0` es "falso" en PHP; compara con `=== false`.

---

## 4. ¿Cuándo se ejecuta finally?

Sin ejecutarlo primero, **escribe en un comentario** qué crees que imprime este código. Luego ejecútalo y explica
cualquier diferencia.

```php
function probar(int $n): string {
    try {
        echo "Inicio $n | ";
        if ($n === 2) {
            throw new RuntimeException("falló");
        }
        return "retorno normal";
    } catch (RuntimeException $e) {
        return "retorno desde catch";
    } finally {
        echo "finally $n | ";
    }
}

echo probar(1) . "<br>";
echo probar(2) . "<br>";
```

---

## 5. Producto agotado

Crea la excepción `ProductoAgotadoException` que reciba el **nombre del producto** y guarde además
ese nombre en una propiedad con su *getter* `getProducto()`.

Luego escribe `vender(array &$inventario, string $producto, int $cantidad)` que reste del inventario o lance la excepción.

```php
$inventario = ['teclado' => 5, 'mouse' => 0];
vender($inventario, 'teclado', 2); // OK, quedan 3
vender($inventario, 'mouse', 1);   // ProductoAgotadoException
```

**Salida esperada:** `No hay unidades suficientes de "mouse". Producto: mouse`

---

## 6. Familia de excepciones de validación

Crea una pequeña jerarquía:

```text
ValidacionException            (extends Exception)
 ├── CampoVacioException
 └── EmailInvalidoException
```

Escribe `validarUsuario(array $datos)` que lance la que corresponda. Al usarla, **un solo** `catch (ValidacionException $e)`
debe atrapar ambas. Muestra también qué clase de excepción fue con `get_class($e)`.

---

## 7. Reunir todos los errores

En un formulario no basta con avisar del **primer** error: hay que mostrarlos todos.
Usa las funciones de los ejercicios 2 y 3 para validar este registro y llenar un array `$errores`:

```php
$registro = ['edad' => '150', 'hijos' => 'dos', 'año' => '2026'];
```

**Salida esperada:**

```text
Se encontraron 2 errores:
- edad: La edad 150 no es válida
- hijos: 'dos' no es un número entero
```

**Pista:** un `try/catch` por campo, y en el `catch` haces `$errores[$campo] = $e->getMessage();`.

---

## 8. Cuenta bancaria

Crea la clase `Cuenta` con `saldo` privado y estos métodos:

| Método | Lanza excepción si… |
|---|---|
| `consignar(float $monto)` | el monto es ≤ 0 |
| `retirar(float $monto)` | el monto es ≤ 0, si supera el saldo o si en el día ya se retiraron más de $ 2.000.000 |
| `getSaldo(): float` | — |

Haz una simulación con varios movimientos (algunos inválidos) y muestra el saldo final.

---

## 9. Re-lanzar con más contexto

Escribe `leerConfiguracion(string $archivo): array` que lea un JSON. Si el archivo no existe o el JSON está mal,
lanza `RuntimeException("No se pudo cargar la configuración")` **pasando la excepción original como `previous`**.

Al capturarla, muestra el mensaje general y también el detalle técnico con `$e->getPrevious()->getMessage()`.

**Pista:** `json_decode($texto, true, 512, JSON_THROW_ON_ERROR)` lanza `JsonException` si el JSON es inválido.

---

## 10. Conexión a la base de datos sin sustos

Intenta conectarte con PDO usando una contraseña **incorrecta**.

1. Sin `try/catch`: ¿qué ve el usuario? ¿Se ve la contraseña o la ruta de tus archivos?
2. Con `try/catch (PDOException $e)`: muestra solo *"El servicio no está disponible, intenta más tarde"*
   y guarda el detalle técnico con `error_log()` en un archivo `errores.log`.

> Esto es exactamente lo que hacen `config.php` y los `Database.php` del curso.

---

## 11. Manejador global

Usa `set_exception_handler()` para que **cualquier** excepción no capturada en el script:

- muestre una página amigable con un código de referencia (por ejemplo `ERR-5F3A2C`),
- y escriba en `errores.log` la fecha, ese código, la clase, el mensaje, el archivo y la línea.

Pruébalo lanzando una excepción sin `try` al final del script.

> **Puente a Laravel:** Laravel hace esto en `bootstrap/app.php` → `withExceptions()`. Ese código de referencia es lo que le das a soporte.

---

## 12. Convertir *warnings* en excepciones

`file_get_contents('no_existe.txt')` **no** lanza una excepción: muestra un *warning* y devuelve `false`.
Por eso un `try/catch` no lo atrapa.

1. Compruébalo.
2. Usa `set_error_handler()` para convertir los warnings en `ErrorException`.
3. Vuelve a probar: ahora el `try/catch` sí debe atraparlo.
4. Escribe en un comentario por qué crees que los frameworks hacen esto.
