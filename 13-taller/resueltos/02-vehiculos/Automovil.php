<?php

require_once __DIR__ . '/Vehiculo.php';

class Automovil extends Vehiculo
{
    private bool $maleteroAbierto = false;

    public function __construct(string $marca, string $modelo, int $anio, private int $puertas = 4)
    {
        parent::__construct($marca, $modelo, $anio);   // primero, lo que hace el padre
        if (!in_array($puertas, [2, 3, 4, 5], true)) {
            throw new InvalidArgumentException("Un automóvil tiene entre 2 y 5 puertas.");
        }
    }

    public function tipo(): string
    {
        return 'Automóvil';
    }

    public function velocidadMaxima(): int
    {
        return 180;
    }

    // Método propio: solo los automóviles tienen maletero
    public function abrirMaletero(): string
    {
        if ($this->velocidad > 0) {
            return "{$this->nombre()}: no se puede abrir el maletero en movimiento.";
        }
        $this->maleteroAbierto = true;
        return "{$this->nombre()}: maletero abierto.";
    }

    // Se SOBRESCRIBE acelerar: un auto no arranca con el maletero abierto
    public function acelerar(int $kmh): string
    {
        if ($this->maleteroAbierto) {
            return "{$this->nombre()}: cierra el maletero antes de acelerar.";
        }
        return parent::acelerar($kmh);   // si todo está bien, hace lo mismo que cualquier vehículo
    }

    public function cerrarMaletero(): string
    {
        $this->maleteroAbierto = false;
        return "{$this->nombre()}: maletero cerrado.";
    }
}
