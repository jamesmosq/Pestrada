<?php
// ── Interfaces ────────────────────────────────────────────────────────────────
// Una interfaz define QUÉ métodos debe tener una clase, sin decir CÓMO hacerlos.
// Es un "contrato": cualquier clase que implemente la interfaz DEBE tener esos métodos.

interface Figura {
    public function area(): float;
    public function perimetro(): float;
    public function descripcion(): string;
}

// Cualquier clase que diga "implements Figura" DEBE implementar los 3 métodos

class Circulo implements Figura {
    public function __construct(private float $radio) {}

    public function area(): float {
        return M_PI * $this->radio ** 2;
    }

    public function perimetro(): float {
        return 2 * M_PI * $this->radio;
    }

    public function descripcion(): string {
        return "Círculo (radio: {$this->radio})";
    }
}

class Rectangulo implements Figura {
    public function __construct(
        private float $ancho,
        private float $alto
    ) {}

    public function area(): float {
        return $this->ancho * $this->alto;
    }

    public function perimetro(): float {
        return 2 * ($this->ancho + $this->alto);
    }

    public function descripcion(): string {
        return "Rectángulo ({$this->ancho} x {$this->alto})";
    }
}

class Triangulo implements Figura {
    public function __construct(
        private float $base,
        private float $altura,
        private float $lado1,
        private float $lado2
    ) {}

    public function area(): float {
        return ($this->base * $this->altura) / 2;
    }

    public function perimetro(): float {
        return $this->base + $this->lado1 + $this->lado2;
    }

    public function descripcion(): string {
        return "Triángulo (base: {$this->base}, altura: {$this->altura})";
    }
}

// ── Polimorfismo: tratar objetos diferentes de la misma forma ─────────────────
// Gracias a la interfaz, podemos recorrer cualquier Figura de igual manera

$figuras = [
    new Circulo(5),
    new Rectangulo(4, 6),
    new Triangulo(8, 5, 6, 7),
];

foreach ($figuras as $figura) {
    echo "<strong>" . $figura->descripcion() . "</strong><br>";
    echo "Área: "      . round($figura->area(), 2)       . "<br>";
    echo "Perímetro: " . round($figura->perimetro(), 2)  . "<br>";
    echo "<hr>";
}
