<?php
// ── Control manual con fopen / fwrite / fread / fclose ──────────────────────
// Útil cuando necesitas leer/escribir de forma incremental (archivos grandes)

// Modos de apertura:
// "r"  = solo lectura, puntero al inicio
// "w"  = solo escritura, BORRA el contenido existente, crea si no existe
// "a"  = solo escritura, AÑADE al final, crea si no existe
// "r+" = lectura y escritura
// "w+" = lectura y escritura, borra contenido existente

// ── Escribir un registro (log) ───────────────────────────────────────────────
$log = fopen("visitas.log", "a");
if ($log) {
    $entrada = date("Y-m-d H:i:s") . " | IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'CLI') . "\n";
    fwrite($log, $entrada);
    fclose($log);
    echo "Registro guardado en visitas.log<br>";
}

echo "<hr>";

// ── Leer línea por línea (eficiente para archivos grandes) ───────────────────
if (file_exists("visitas.log")) {
    $archivo = fopen("visitas.log", "r");
    if ($archivo) {
        echo "<strong>Contenido de visitas.log:</strong><br>";
        while (!feof($archivo)) {          // feof = fin de archivo
            $linea = fgets($archivo);      // lee UNA línea
            if ($linea) {
                echo htmlspecialchars($linea) . "<br>";
            }
        }
        fclose($archivo);
    }
}

echo "<hr>";

// ── Información del archivo ──────────────────────────────────────────────────
if (file_exists("visitas.log")) {
    echo "Tamaño: "      . filesize("visitas.log") . " bytes<br>";
    echo "Modificado: "  . date("d/m/Y H:i:s", filemtime("visitas.log")) . "<br>";
    echo "Creado: "      . date("d/m/Y H:i:s", filectime("visitas.log")) . "<br>";
}
