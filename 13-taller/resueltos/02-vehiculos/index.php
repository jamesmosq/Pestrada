<?php
// ─────────────────────────────────────────────────────────────────────────────
//  index.php — usa las clases. Cada clase vive en su propio archivo.
// ─────────────────────────────────────────────────────────────────────────────
require_once __DIR__ . '/Automovil.php';
require_once __DIR__ . '/Motocicleta.php';
require_once __DIR__ . '/Camion.php';

function linea(string $texto): void
{
    echo htmlspecialchars($texto) . "<br>";
}

// ── 1. Polimorfismo: tratar a todos igual, cada uno responde a su manera ──────
$flota = [
    new Automovil('Mazda', '3', 2022),
    new Motocicleta('Yamaha', 'NMAX', 2023, 155),
    new Camion('Chevrolet', 'NHR', 2019, 4.5),
];

echo "<h3>1. La misma orden para todos: arrancar y acelerar 150 km/h</h3>";
foreach ($flota as $vehiculo) {
    linea($vehiculo->arrancar());
    linea($vehiculo->acelerar(150));
}

// ── 2. Lo propio de cada tipo ─────────────────────────────────────────────────
echo "<h3>2. Lo que solo tiene cada tipo</h3>";

$moto = $flota[1];
linea($moto->ponerseCasco());
linea($moto->arrancar());
linea($moto->acelerar(200));          // más de 150 cc: su tope es 160 km/h

$camion = $flota[2];
linea($camion->frenar(200));          // estaba a 100: queda en 0
linea($camion->cargar(3));
linea($camion->cargar(2));            // supera la capacidad
linea($camion->acelerar(150));        // con carga va más despacio

$auto = $flota[0];
linea($auto->frenar(200));
linea($auto->abrirMaletero());
linea($auto->acelerar(50));           // no deja: maletero abierto
linea($auto->cerrarMaletero());
linea($auto->acelerar(50));

// ── 3. Lo que NO se puede hacer ───────────────────────────────────────────────
echo "<h3>3. Lo que el diseño impide</h3>";

linea($auto->apagar());               // va en movimiento

try {
    $v = new Vehiculo('X', 'Y', 2020);
} catch (Error $e) {
    linea("new Vehiculo(): " . $e->getMessage());
}

try {
    $viejo = new Automovil('Ford', 'T', 1850);
} catch (InvalidArgumentException $e) {
    linea("new Automovil(... 1850): " . $e->getMessage());
}

try {
    $auto->velocidad = 500;
} catch (Error $e) {
    linea("\$auto->velocidad = 500: " . $e->getMessage());
}
