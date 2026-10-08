<?php

/**
 * Clase base de todos los vehículos.
 *
 * Es "abstract": no se puede hacer new Vehiculo(), porque un vehículo genérico
 * no existe; siempre es un automóvil, una moto o un camión. Lo que TODOS tienen
 * en común vive aquí; lo que cambia, lo define cada hija.
 */
abstract class Vehiculo
{
    // protected: las clases hijas pueden usarlas; desde afuera no
    protected bool $encendido = false;
    protected int  $velocidad = 0;

    public function __construct(
        protected string $marca,
        protected string $modelo,
        protected int    $anio
    ) {
        $anioActual = (int) date('Y');
        if ($anio < 1900 || $anio > $anioActual + 1) {
            throw new InvalidArgumentException("El año $anio no es válido.");
        }
    }

    // ── Lo que cada hija DEBE definir (abstract = "obligatorio para las hijas") ──
    abstract public function tipo(): string;
    abstract public function velocidadMaxima(): int;

    // ── Lo que todas comparten ─────────────────────────────────────────────────
    public function arrancar(): string
    {
        if ($this->encendido) {
            return "{$this->nombre()} ya estaba encendido.";
        }
        $this->encendido = true;
        return "{$this->nombre()} arrancó.";
    }

    public function acelerar(int $kmh): string
    {
        if (!$this->encendido) {
            return "{$this->nombre()} no puede acelerar: está apagado.";
        }
        // min(): nunca supera la velocidad máxima de SU tipo (la decide cada hija)
        $this->velocidad = min($this->velocidad + $kmh, $this->velocidadMaxima());
        return "{$this->nombre()} va a {$this->velocidad} km/h.";
    }

    public function frenar(int $kmh): string
    {
        $this->velocidad = max($this->velocidad - $kmh, 0);   // nunca negativa
        return "{$this->nombre()} frenó: va a {$this->velocidad} km/h.";
    }

    public function apagar(): string
    {
        if ($this->velocidad > 0) {
            return "{$this->nombre()} no se puede apagar en movimiento.";
        }
        $this->encendido = false;
        return "{$this->nombre()} se apagó.";
    }

    public function nombre(): string
    {
        return "{$this->tipo()} {$this->marca} {$this->modelo} ({$this->anio})";
    }

    public function getVelocidad(): int
    {
        return $this->velocidad;
    }
}
