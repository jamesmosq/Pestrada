<?php
class Libro {
    public $titulo;
    public $autor;
    public $anioPublicacion;

    public function __construct($titulo, $autor, $anioPublicacion) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->anioPublicacion = $anioPublicacion;
    }

    public function getInfo() {
        return "'{$this->titulo}' por {$this->autor}, publicado en {$this->anioPublicacion}";
    }
}
?>