<?php
class Animal {
    protected $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function hacerSonido() {
        return "El animal hace un sonido.";
    }
}

class Perro extends Animal {
    public function hacerSonido() {
        return $this->nombre . " ladra: ¡Guau guau!";
    }
}

class Gato extends Animal {
    public function hacerSonido() {
        return $this->nombre . " maulla: ¡Miau miau!";
    }
}

// Crear objetos
$perro = new Perro("Bobby");
$gato = new Gato("Michi");

// Llamar a los métodos
echo $perro->hacerSonido() . "\n";
echo $gato->hacerSonido();
//Ejemplo 3: Clase con herencia
?>