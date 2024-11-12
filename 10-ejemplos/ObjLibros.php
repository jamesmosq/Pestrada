<?php
// Incluir la definición de la clase
include 'Libro.php';

// Crear un objeto de la clase Libro
$miLibro = new Libro("Cien años de soledad", "Gabriel García Márquez", 1967);
$miLibro1 = new Libro("El gato Azul", "Juan Salvador Gaviota", 1957);
$miLibro2 = new Libro("el gato feliz","el perro salta",1789);

// Usar el método getInfo
echo $miLibro->getInfo()."<br>";
echo $miLibro1->getInfo()."<br>";
echo $miLibro2->getInfo()."<br>";
?>