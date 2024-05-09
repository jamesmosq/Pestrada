<?php
class MiClase {
    // Definir propiedades
    public $propiedad1;
    private $propiedad2;

    // Constructor
    public function __construct($valor1, $valor2) {
        $this->propiedad1 = $valor1;
        $this->propiedad2 = $valor2;
    }

    // Definir métodos
    public function metodoPublico() {
        echo "Este es un método público.";
    }

    private function metodoPrivado() {
        echo "Este es un método privado.";
    }

    // Getter para propiedad privada
    public function getPropiedad2() {
        return $this->propiedad2;
    }

    // Setter para propiedad privada
    public function setPropiedad2($valor) {
        $this->propiedad2 = $valor;
    }
}
?>
