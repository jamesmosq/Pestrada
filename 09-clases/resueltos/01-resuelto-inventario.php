<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 01 — Inventario de una tienda
 *  Tipo: resuelto y comentado
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  Una tienda necesita registrar productos (código, nombre, precio y unidades
 *  en bodega), vender y reponer unidades, y saber cuánto vale todo el
 *  inventario y qué productos están agotados. Las unidades NUNCA pueden
 *  quedar negativas y el precio no puede ser cero ni negativo.
 *
 *  CÓMO SE PENSÓ (antes de escribir código)
 *  1. Los SUSTANTIVOS del enunciado son candidatos a clases:
 *       producto -> clase Producto      inventario -> clase Inventario
 *  2. Los VERBOS son candidatos a métodos:
 *       vender, reponer           -> los hace un Producto
 *       registrar, buscar, valor total, agotados -> los hace el Inventario
 *  3. Las REGLAS ("nunca negativas", "precio > 0") se protegen haciendo los
 *     atributos PRIVADOS: nadie puede escribir $producto->unidades = -5;
 *     la única forma de cambiarlas es por un método que valida.
 *  4. Cada clase sabe hacer lo suyo: el Inventario NO resta unidades él mismo,
 *     le pide al Producto que se venda.
 */

class Producto
{
    // Promoción de propiedades (PHP 8): declara y asigna en el constructor
    public function __construct(
        private string $codigo,
        private string $nombre,
        private float  $precio,
        private int    $unidades = 0
    ) {
        // El constructor es la primera línea de defensa: un objeto nunca
        // debe nacer en un estado inválido.
        if ($precio <= 0) {
            throw new InvalidArgumentException("El precio de $nombre debe ser mayor que cero.");
        }
        if ($unidades < 0) {
            throw new InvalidArgumentException("Las unidades iniciales no pueden ser negativas.");
        }
    }

    public function vender(int $cantidad): void
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad a vender debe ser positiva.");
        }
        if ($cantidad > $this->unidades) {
            throw new RuntimeException("Solo quedan {$this->unidades} unidades de {$this->nombre}.");
        }
        $this->unidades -= $cantidad;
    }

    public function reponer(int $cantidad): void
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad a reponer debe ser positiva.");
        }
        $this->unidades += $cantidad;
    }

    // Getters: se puede LEER, pero no escribir directamente
    public function getCodigo(): string { return $this->codigo; }
    public function getNombre(): string { return $this->nombre; }
    public function getUnidades(): int  { return $this->unidades; }

    public function valorEnBodega(): float
    {
        return $this->precio * $this->unidades;
    }

    public function estaAgotado(): bool
    {
        return $this->unidades === 0;
    }
}

class Inventario
{
    /** @var Producto[] productos indexados por código */
    private array $productos = [];

    public function registrar(Producto $producto): void
    {
        $this->productos[$producto->getCodigo()] = $producto;
    }

    public function buscar(string $codigo): Producto
    {
        if (!isset($this->productos[$codigo])) {
            throw new RuntimeException("No existe el producto $codigo.");
        }
        return $this->productos[$codigo];
    }

    public function valorTotal(): float
    {
        $total = 0;
        foreach ($this->productos as $producto) {
            $total += $producto->valorEnBodega();   // le pregunta a cada producto
        }
        return $total;
    }

    /** @return string[] nombres de los productos agotados */
    public function agotados(): array
    {
        $nombres = [];
        foreach ($this->productos as $producto) {
            if ($producto->estaAgotado()) {
                $nombres[] = $producto->getNombre();
            }
        }
        return $nombres;
    }
}

// ── Uso ───────────────────────────────────────────────────────────────────────
function pesos(float $valor): string
{
    return '$ ' . number_format($valor, 0, ',', '.');
}

$inventario = new Inventario();
$inventario->registrar(new Producto('P01', 'Teclado', 45000, 10));
$inventario->registrar(new Producto('P02', 'Mouse', 18000, 3));
$inventario->registrar(new Producto('P03', 'Monitor', 650000, 2));

echo "Valor inicial: " . pesos($inventario->valorTotal()) . "<br>";

$operaciones = [
    ['vender',  'P02', 3],
    ['vender',  'P03', 5],      // no hay suficientes
    ['reponer', 'P01', 5],
    ['vender',  'P09', 1],      // no existe
    ['vender',  'P01', -2],     // cantidad inválida
];

foreach ($operaciones as [$accion, $codigo, $cantidad]) {
    try {
        $inventario->buscar($codigo)->$accion($cantidad);
        echo "OK: $accion $cantidad de $codigo<br>";
    } catch (InvalidArgumentException | RuntimeException $e) {
        echo "Rechazado: " . $e->getMessage() . "<br>";
    }
}

echo "Valor final: " . pesos($inventario->valorTotal()) . "<br>";
echo "Agotados: " . (implode(', ', $inventario->agotados()) ?: 'ninguno') . "<br>";

try {
    new Producto('P04', 'Cable', 0, 5);
} catch (InvalidArgumentException $e) {
    echo "No se creó: " . $e->getMessage() . "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAZA — estado del inventario después de cada operación
 * ════════════════════════════════════════════════════════════════════════════
 *
 *   operación            | Teclado | Mouse | Monitor | valor total
 *   ---------------------+---------+-------+---------+-------------
 *   inicio               |   10    |   3   |    2    | 450.000 + 54.000 + 1.300.000 = 1.804.000
 *   vender 3 de P02      |   10    |   0   |    2    | 1.750.000
 *   vender 5 de P03      |   (rechazado: solo hay 2)  | 1.750.000
 *   reponer 5 de P01     |   15    |   0   |    2    | 1.975.000
 *   vender 1 de P09      |   (rechazado: no existe)   | 1.975.000
 *   vender -2 de P01     |   (rechazado: cantidad)    | 1.975.000
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  - En Python se escribe self.unidades y self es el primer parámetro de cada
 *    método. En PHP se escribe $this->unidades y $this NO se declara: existe solo.
 *  - En Python "privado" es una convención (_unidades): se puede acceder igual.
 *    En PHP private es REAL: tocarlo desde afuera es un error. Míralo abajo.
 *  - En Python se usa el punto (producto.vender). En PHP es la flecha
 *    ($producto->vender), porque el punto ya se usa para unir textos.
 */

echo "<hr>";

try {
    $teclado = $inventario->buscar('P01');
    $teclado->unidades = -100;     // intento de saltarse las reglas
} catch (Error $e) {
    echo "PHP no lo permite: " . $e->getMessage() . "<br>";
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Por qué Inventario::valorTotal() no calcula precio * unidades por su
 *     cuenta, sino que le pregunta a cada producto con valorEnBodega()?
 *  2. ¿Por qué no hay un setUnidades()? ¿Qué problema traería?
 *  3. ¿Qué devuelve buscar() y por qué se puede escribir
 *     $inventario->buscar('P01')->vender(2) en una sola línea?
 *  4. ¿Qué diferencia hay entre InvalidArgumentException y RuntimeException
 *     en este código? (pista: ¿de quién es la "culpa" en cada caso?)
 *
 *  PARA MODIFICAR
 *  - Agrega a Producto un método aplicarDescuento(float $porcentaje) que no
 *    permita dejar el precio en cero o negativo.
 *  - Agrega a Inventario un método masValioso() que devuelva el Producto con
 *    mayor valor en bodega.
 */
