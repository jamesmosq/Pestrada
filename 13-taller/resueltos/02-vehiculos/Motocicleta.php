<?php

require_once __DIR__ . '/Vehiculo.php';

class Motocicleta extends Vehiculo
{
    private bool $cascoPuesto = false;

    public function __construct(string $marca, string $modelo, int $anio, private int $cilindraje)
    {
        parent::__construct($marca, $modelo, $anio);
        if ($cilindraje < 50) {
            throw new InvalidArgumentException("El cilindraje mínimo es 50 cc.");
        }
    }

    public function tipo(): string
    {
        return 'Motocicleta';
    }

    // La velocidad máxima depende de un dato propio de la moto
    public function velocidadMaxima(): int
    {
        return $this->cilindraje <= 150 ? 100 : 160;
    }

    public function ponerseCasco(): string
    {
        $this->cascoPuesto = true;
        return "{$this->nombre()}: casco puesto.";
    }

    // Se SOBRESCRIBE arrancar: sin casco no se arranca
    public function arrancar(): string
    {
        if (!$this->cascoPuesto) {
            return "{$this->nombre()}: no arranca, primero ponte el casco.";
        }
        return parent::arrancar();
    }
}
