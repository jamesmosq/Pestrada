<?php
$estudiantes = [
    "Carlos" => 85,
    "María" => 92,
    "Juan" => 78,
    "Ana" => 89
];

foreach ($estudiantes as $nombre => $calificacion) {
    echo "Nombre: " . $nombre . ", Calificación: " . $calificacion . "\n";
}
?>
