<?php
// ── Traits ────────────────────────────────────────────────────────────────────
// Un Trait es un bloque de métodos reutilizables que se pueden "inyectar" en cualquier clase.
// Soluciona la limitación de PHP de no tener herencia múltiple.
// Diferencia clave: Herencia = "es un". Trait = "tiene la capacidad de".

trait Timestamps {
    private string $creadoEn  = '';
    private string $modificadoEn = '';

    public function registrarCreacion(): void {
        $this->creadoEn = date("Y-m-d H:i:s");
    }

    public function registrarModificacion(): void {
        $this->modificadoEn = date("Y-m-d H:i:s");
    }

    public function getCreadoEn(): string {
        return $this->creadoEn ?: "No registrado";
    }

    public function getModificadoEn(): string {
        return $this->modificadoEn ?: "No modificado";
    }
}

trait Auditable {
    private string $creadoPor = '';

    public function setCreadoPor(string $usuario): void {
        $this->creadoPor = $usuario;
    }

    public function getCreadoPor(): string {
        return $this->creadoPor ?: "Sistema";
    }
}

trait Exportable {
    public function toArray(): array {
        return get_object_vars($this);
    }

    public function toJson(): string {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}

// ── Clases usando los traits ──────────────────────────────────────────────────

class Producto {
    use Timestamps, Auditable, Exportable; // puede usar múltiples traits

    public function __construct(
        public string $nombre,
        public float  $precio
    ) {
        $this->registrarCreacion();
    }
}

class Articulo {
    use Timestamps; // solo necesita timestamps, no auditoría

    public function __construct(public string $titulo) {
        $this->registrarCreacion();
    }
}

// ── Uso ───────────────────────────────────────────────────────────────────────

$producto = new Producto("Laptop", 1500.00);
$producto->setCreadoPor("admin");

echo "Producto: {$producto->nombre}<br>";
echo "Precio: \${$producto->precio}<br>";
echo "Creado por: "  . $producto->getCreadoPor()     . "<br>";
echo "Creado en: "   . $producto->getCreadoEn()      . "<br>";
echo "Modificado en: " . $producto->getModificadoEn() . "<br>";

echo "<hr>";

$producto->registrarModificacion();
echo "Después de modificar: " . $producto->getModificadoEn() . "<br>";

echo "<hr>";

$articulo = new Articulo("Introducción a PHP");
echo "Artículo: {$articulo->titulo}<br>";
echo "Creado en: " . $articulo->getCreadoEn() . "<br>";
