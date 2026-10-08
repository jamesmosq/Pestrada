<?php
// Si alguien abre esta pagina directo (sin enviar el formulario), no hay datos en $_POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Primero llena el formulario: <a href='index.html'>ir al formulario</a>";
    exit;
}

// ?? '' evita el warning "Undefined array key" si un campo no llego
$sexo   = $_POST['sexo']   ?? '';
$nombre = $_POST['nombre'] ?? '';
$edad   = (int) ($_POST['edad'] ?? 0);

// htmlspecialchars: lo que escribe el usuario nunca se imprime tal cual
$nombre = htmlspecialchars($nombre);
$sexo   = htmlspecialchars($sexo);

if ($edad >= 18) {
    echo "Eres mayor de edad y tienes $edad años, tu nombre es: $nombre y de sexo $sexo";
} else {
    echo "Eres menor de edad";
}
