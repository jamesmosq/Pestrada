<?php

require_once __DIR__ . '/Vehiculo.php';

class Camion extends Vehiculo
{
    private float $cargaActual = 0;   // toneladas

    public function __construct(string $marca, string $modelo, int $anio, private float $capacidad)
    {
        parent::__construct($marca, $modelo, $anio);
        if ($capacidad <= 0) {
            throw new InvalidArgumentException("La capacidad debe ser mayor que cero.");
        }
    }

    public function tipo(): string
    {
        return 'Camión';
    }

    // Entre más carga, menos velocidad: 100 km/h vacío, 5 km/h menos por tonelada (mínimo 60)
    public function velocidadMaxima(): int
    {
        return (int) max(100 - $this->cargaActual * 5, 60);
    }

    public function cargar(float $toneladas): string
    {
        if ($this->velocidad > 0) {
            return "{$this->nombre()}: no se puede cargar en movimiento.";
        }
        if ($toneladas <= 0) {
            return "{$this->nombre()}: la carga debe ser positiva.";
        }
        if ($this->cargaActual + $toneladas > $this->capacidad) {
            $libre = $this->capacidad - $this->cargaActual;
            return "{$this->nombre()}: solo caben $libre toneladas más.";
        }
        $this->cargaActual += $toneladas;
        return "{$this->nombre()}: cargado con {$this->cargaActual} t (máx. {$this->velocidadMaxima()} km/h).";
    }
}
