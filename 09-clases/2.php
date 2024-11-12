<?php
class Persona {
    // Propiedades
    public $nombre;
    public $edad;

    // Método
    public function saludar() {
        return "Hola, mi nombre es " . $this->nombre . " y tengo " . $this->edad . " años.";
    }
}

// Crear un objeto de la clase Persona
$persona1 = new Persona();
$persona1->nombre = "Juan";
$persona1->edad = 25;

$persona2 = new persona();
$persona2 ->nombre = "James";
$persona2->edad = 12;

$persona3 = new Persona();
$persona3 ->nombre = "Pedro";
$persona3 ->edad = 56;

// Llamar al método
echo $persona2->saludar() ."<br>";
echo $persona3->saludar() . "<br>";
echo $persona1->saludar();
//Ejemplo 1: Clase básica
?>