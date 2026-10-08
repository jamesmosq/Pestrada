# Sustentación modelo — Taller 2, ejercicio 1: Sistema de vehículos

Este documento muestra **cómo se sustenta** el código de esta carpeta. No es un guion para memorizar:
es un ejemplo del nivel de explicación que se espera. Tu sustentación debe hablar de **tu** código.

## 1. Qué hace, en 30 segundos

> "Hay una clase abstracta `Vehiculo` con lo que todos los vehículos comparten: marca, modelo, año, velocidad,
> y los métodos arrancar, acelerar, frenar y apagar. De ella heredan `Automovil`, `Motocicleta` y `Camion`.
> Cada una define su velocidad máxima y agrega lo suyo: el automóvil tiene maletero, la moto exige casco para
> arrancar y el camión se carga y va más despacio con más peso. En `index.php` recorro todos los vehículos con
> el mismo código y cada uno responde según su tipo: eso es polimorfismo."

## 2. Cómo está organizado

```
Vehiculo  (abstract)
│  protegidos: marca, modelo, anio, encendido, velocidad
│  abstractos: tipo(), velocidadMaxima()          ← cada hija DEBE escribirlos
│  comunes:    arrancar(), acelerar(), frenar(), apagar(), nombre()
│
├── Automovil     + puertas, maletero    abrirMaletero(), cerrarMaletero()   sobrescribe acelerar()
├── Motocicleta   + cilindraje, casco    ponerseCasco()                      sobrescribe arrancar()
└── Camion        + capacidad, carga     cargar()                            velocidadMaxima() depende de la carga
```

Una clase por archivo, y cada hija hace `require_once` del padre. Es la misma organización de Laravel:
un archivo por clase.

## 3. Los conceptos, señalados en el código

| Concepto | Dónde verlo | Qué decir |
|---|---|---|
| **Herencia** | `class Camion extends Vehiculo` | Camion recibe todo lo de Vehiculo sin copiarlo: arrancar, frenar, nombre... |
| **Clase abstracta** | `abstract class Vehiculo` | No tiene sentido un "vehículo" sin tipo. `new Vehiculo()` da error (se ve en la parte 3 de `index.php`). |
| **Método abstracto** | `abstract public function velocidadMaxima(): int;` | Obliga a cada hija a definirlo. Si `Camion` no lo tuviera, PHP no dejaría crear la clase. |
| **protected** | `protected int $velocidad` | Las hijas la usan (`$this->velocidad` en `Automovil`), pero desde afuera no: `$auto->velocidad = 500` da error. |
| **parent::__construct** | constructor de cada hija | La hija agrega sus datos (puertas, cilindraje, capacidad), pero la validación del año la hace el padre. |
| **Sobrescribir + parent::** | `Motocicleta::arrancar()` | Agrega una condición (el casco) y, si se cumple, hace lo mismo que el padre con `parent::arrancar()`. |
| **Polimorfismo** | el `foreach ($flota ...)` | Mismo código, `$vehiculo->acelerar(150)`, y el resultado depende del objeto: el auto llega a 150 y el camión a 100. |
| **Encapsulamiento** | `acelerar()` usa `min(...)` | Nadie puede poner una velocidad mayor que la máxima, porque la única forma de cambiarla es por los métodos. |

## 4. Preguntas que te pueden hacer, y una buena respuesta

**¿Por qué `Vehiculo` es abstracta?**
Porque en el sistema no existe un vehículo "genérico": siempre es de un tipo concreto. Además, `velocidadMaxima()`
no tiene una respuesta general; cada tipo da la suya.

**¿Qué diferencia hay entre `private` y `protected`?**
`private` solo lo ve la clase donde se declaró; `protected` lo ve esa clase y sus hijas. `velocidad` es
`protected` porque `Automovil` y `Camion` la consultan. `maleteroAbierto` es `private` porque solo le interesa
a `Automovil`.

**¿Qué pasa si quito `parent::__construct(...)` del `Automovil`?**
Nadie asigna marca, modelo ni año, y la validación del año no se ejecuta. Como las propiedades tienen tipo,
al usarlas PHP lanza *"must not be accessed before initialization"*.

**¿Por qué el camión no llega a 150 si le pides acelerar 150?**
Porque `acelerar()` usa `min(velocidad + kmh, velocidadMaxima())`, y la del camión es 100 vacío (y baja 5 km/h
por tonelada). Es el mismo método para todos, pero cada objeto responde con su propio límite.

**¿Dónde está el polimorfismo? Muéstramelo.**
En el `foreach` de la parte 1: el código no pregunta "si es moto haz esto, si es camión haz aquello". Llama
a los mismos métodos, y la moto contesta que le falta el casco mientras los otros arrancan.

**¿Cómo agregarías una `Bicicleta`?**
Una clase `Bicicleta extends Vehiculo` con `tipo()` y `velocidadMaxima()`. Pero una bicicleta no tiene motor:
`arrancar()` no tendría sentido. Eso muestra un límite del diseño. Una mejora sería separar
"vehículo con motor" en otra clase intermedia o en una interfaz.

**¿Qué mejorarías?**
- Una interfaz `Cargable` para todo lo que se puede cargar (camión, y quizás una camioneta).
- Guardar el historial de eventos (arrancó, aceleró...) en un array.
- Usar `enum` (PHP 8.1) para los tipos en lugar de textos.

## 5. Cómo demostrarlo en vivo

Ejecuta `index.php` y explica cada una de las tres partes:

| Parte | Qué demuestra |
|---|---|
| 1. La misma orden para todos | Polimorfismo: tres respuestas distintas al mismo código. La moto no arranca sin casco. |
| 2. Lo propio de cada tipo | Métodos que solo tiene cada hija; el camión más lento con carga; el auto que no acelera con el maletero abierto. |
| 3. Lo que el diseño impide | `new Vehiculo()` no se puede; un año imposible se rechaza; la velocidad no se puede tocar desde afuera. |

La parte 3 es la que más demuestra: un buen diseño no solo hace cosas, también **impide** las incorrectas.

## 6. Errores comunes que bajan la nota

- Clases hijas que **copian** los métodos del padre en lugar de heredarlos.
- Propiedades `public` que cualquiera puede cambiar (`$camion->velocidad = 999`).
- Olvidar `parent::__construct()` en el constructor de la hija.
- "Polimorfismo" hecho con `if ($v instanceof Camion) ... elseif ($v instanceof Moto) ...`: eso es justo lo
  contrario; cada clase debe responder por sí misma.
- No saber explicar qué hace `abstract` o `protected` en el propio código.
