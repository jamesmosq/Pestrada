<?php
class Coche {
    // Propiedades
    private $marca;
    private $modelo;
    private $año;

    // Constructor
    public function __construct($marca, $modelo, $año) {
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->año = $año;
    }

    // Método
    public function getInfo() {
        return "Coche: " . $this->marca . " " . $this->modelo . " (" . $this->año . ")";
    }
}

// Crear un objeto de la clase Coche
$miCoche = new Coche("Toyota", "Corolla", 2022);

// Llamar al método
echo $miCoche->getInfo();
//Ejemplo 2: Clase con constructor
?>