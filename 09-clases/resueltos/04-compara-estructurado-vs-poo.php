<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 04 — El mismo carrito, dos estilos
 *  Tipo: compara dos soluciones
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  Un carrito de compras debe permitir agregar productos (si el producto ya
 *  está, se suman las cantidades), calcular el total y contar las unidades.
 *
 *  Abajo está resuelto DOS veces: con arrays y funciones (estructurado) y con
 *  una clase (POO). Las dos imprimen lo mismo. Compáralas y decide.
 */

// ════════════════════════════════════════════════════════════════════════════
//  SOLUCIÓN A — Estructurada: los datos (array) y las funciones van separados
// ════════════════════════════════════════════════════════════════════════════
function agregarAlCarrito(array $carrito, string $producto, float $precio, int $cantidad): array
{
    if (isset($carrito[$producto])) {
        $carrito[$producto]['cantidad'] += $cantidad;
    } else {
        $carrito[$producto] = ['precio' => $precio, 'cantidad' => $cantidad];
    }
    return $carrito;            // obligatorio: el array que llegó es una COPIA
}

function totalCarrito(array $carrito): float
{
    $total = 0;
    foreach ($carrito as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    return $total;
}

function unidadesCarrito(array $carrito): int
{
    return array_sum(array_column($carrito, 'cantidad'));
}

$carrito = [];
$carrito = agregarAlCarrito($carrito, 'Teclado', 45000, 1);
$carrito = agregarAlCarrito($carrito, 'Mouse', 18000, 2);
$carrito = agregarAlCarrito($carrito, 'Teclado', 45000, 1);

echo "A (estructurado): " . unidadesCarrito($carrito) . " unidades, total $ "
   . number_format(totalCarrito($carrito), 0, ',', '.') . "<br>";

// ════════════════════════════════════════════════════════════════════════════
//  SOLUCIÓN B — POO: los datos y lo que se hace con ellos viven juntos
// ════════════════════════════════════════════════════════════════════════════
class Carrito
{
    private array $items = [];   // nadie de afuera puede desordenarlo

    public function agregar(string $producto, float $precio, int $cantidad): static
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad debe ser positiva.");
        }
        if (isset($this->items[$producto])) {
            $this->items[$producto]['cantidad'] += $cantidad;
        } else {
            $this->items[$producto] = ['precio' => $precio, 'cantidad' => $cantidad];
        }
        return $this;            // permite encadenar
    }

    public function total(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    public function unidades(): int
    {
        return array_sum(array_column($this->items, 'cantidad'));
    }
}

$miCarrito = new Carrito();
$miCarrito->agregar('Teclado', 45000, 1)
          ->agregar('Mouse', 18000, 2)
          ->agregar('Teclado', 45000, 1);

echo "B (POO):          " . $miCarrito->unidades() . " unidades, total $ "
   . number_format($miCarrito->total(), 0, ',', '.') . "<br>";

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  COMPARACIÓN
 * ════════════════════════════════════════════════════════════════════════════
 *
 *                           | A (estructurado)                | B (POO)
 *  -------------------------+---------------------------------+------------------------------
 *  ¿Dónde están los datos?  | En un array suelto ($carrito)   | Dentro del objeto ($items)
 *  ¿Quién puede dañarlos?   | Cualquiera: $carrito['X'] =     | Solo los métodos de la clase
 *                           | 'basura' funciona               | (private)
 *  Validar cantidad > 0     | Hay que acordarse en cada       | Una vez, en agregar(), y no
 *                           | función que lo toque            | hay otra forma de entrar
 *  Para usarlo              | Pasar y devolver el array en    | $carrito->agregar(...)
 *                           | cada llamada                    |
 *  Dos carritos a la vez    | Dos arrays, mismas funciones    | Dos objetos: new Carrito()
 *  Para un script corto     | Más rápido de escribir          | Más código "de estructura"
 *
 *  ¿Cuál usar?
 *  - Para un cálculo corto, de una sola vez, A es suficiente.
 *  - Cuando los datos tienen REGLAS (cantidad positiva, stock, estados) y se
 *    usan desde muchos lugares, B protege mejor: las reglas viven en un solo sitio.
 *  - Por eso los proyectos grandes usan clases. En 21-sweetAlert2 cada parte es
 *    una clase (EstudianteModel, EstudianteController), y en Laravel cada tabla
 *    tiene una clase "modelo" que agrupa sus datos y sus reglas.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En la solución A, si olvidas "$carrito = " antes de agregarAlCarrito(...),
 *  el producto NO se agrega: la función modificó una copia y la descartaste.
 *  En Python no pasaría, porque la lista se comparte. En B no existe ese
 *  problema: el objeto sí se comparte, como en Python. Míralo:
 */

echo "<hr>";

$otro = [];
agregarAlCarrito($otro, 'Monitor', 650000, 1);          // sin "$otro = "
echo "Estructurado sin reasignar, unidades en el carrito: " . unidadesCarrito($otro) . "<br>";

$otroObjeto = new Carrito();
$otroObjeto->agregar('Monitor', 650000, 1);             // no hace falta reasignar
echo "POO sin reasignar, unidades en el carrito:          " . $otroObjeto->unidades() . "<br>";

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Agrega a las dos soluciones "quitar un producto". ¿En cuál tuviste que
 *     cambiar más cosas fuera de la función o del método?
 *  2. En A, escribe $carrito['Teclado']['cantidad'] = -50; antes del total.
 *     ¿Qué pasa? ¿Se puede hacer lo mismo en B?
 *  3. La solución A no valida que la cantidad sea positiva. ¿Dónde tendrías
 *     que agregar esa validación para que nadie se la salte?
 *  4. ¿Qué tienen en común Carrito y EstudianteModel de 21-sweetAlert2?
 */
