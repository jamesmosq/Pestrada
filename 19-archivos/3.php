<?php
// ── Carpetas y listado de archivos ───────────────────────────────────────────

// Crear una carpeta (si no existe)
if (!is_dir("carpeta_prueba")) {
    mkdir("carpeta_prueba");
    echo "Carpeta 'carpeta_prueba' creada.<br>";
} else {
    echo "La carpeta ya existe.<br>";
}

// Crear archivos dentro de ella
file_put_contents("carpeta_prueba/nota1.txt", "Contenido de la nota 1");
file_put_contents("carpeta_prueba/nota2.txt", "Contenido de la nota 2");
file_put_contents("carpeta_prueba/datos.csv", "nombre,edad\nAna,25\nLuis,30");

echo "<hr>";

// scandir — listar contenido de una carpeta
$archivos = scandir("carpeta_prueba");
echo "<strong>Archivos en carpeta_prueba:</strong><ul>";
foreach ($archivos as $item) {
    if ($item === "." || $item === "..") continue; // saltar entradas del sistema
    $ruta = "carpeta_prueba/$item";
    $tipo = is_dir($ruta) ? "📁 [carpeta]" : "📄 [archivo]";
    echo "<li>$tipo $item — " . filesize($ruta) . " bytes</li>";
}
echo "</ul>";

echo "<hr>";

// Renombrar y eliminar (descomentarlos para probar)

// rename("carpeta_prueba/nota1.txt", "carpeta_prueba/nota_renombrada.txt");
// echo "Archivo renombrado.<br>";

// unlink("carpeta_prueba/nota2.txt");        // eliminar archivo
// echo "Archivo eliminado.<br>";

// rmdir solo funciona con carpetas VACÍAS
// Para borrar una carpeta con contenido hay que borrar primero cada archivo

echo "Para probar rename() y unlink() descomenta las líneas en el código.<br>";
