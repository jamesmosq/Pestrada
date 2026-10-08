<?php
// ─────────────────────────────────────────────────────────────────────────────
//  calcular.php — recibe los datos de formulario.html, valida, calcula y muestra
// ─────────────────────────────────────────────────────────────────────────────

// 1. Este archivo solo tiene sentido si llega un formulario por POST.
//    Si alguien lo abre directo en el navegador (GET), lo devolvemos al formulario.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: formulario.html');
    exit;
}

// 2. Leer los datos. Todo llega como TEXTO (o no llega): ?? '' evita el warning.
$texto1    = trim($_POST['num1'] ?? '');
$texto2    = trim($_POST['num2'] ?? '');
$operacion = $_POST['operacion'] ?? '';

// 3. Validar y convertir. filter_var devuelve el número, o false si no lo es.
//    Se acepta la coma decimal (3,5) porque así escribimos los decimales en Colombia.
$num1 = filter_var(str_replace(',', '.', $texto1), FILTER_VALIDATE_FLOAT);
$num2 = filter_var(str_replace(',', '.', $texto2), FILTER_VALIDATE_FLOAT);

// Lista blanca: operación permitida => símbolo que se muestra
$simbolos = ['sumar' => '+', 'restar' => '-', 'multiplicar' => 'x', 'dividir' => '/'];

$error = '';
if ($num1 === false || $num2 === false) {
    $error = 'Los dos valores deben ser números.';
} elseif (!array_key_exists($operacion, $simbolos)) {
    $error = 'La operación no es válida.';
} elseif ($operacion === 'dividir' && $num2 == 0) {
    $error = 'No se puede dividir entre cero.';
}

// 4. Calcular (solo si no hubo error)
$resultado = null;
if ($error === '') {
    $resultado = match ($operacion) {
        'sumar'       => $num1 + $num2,
        'restar'      => $num1 - $num2,
        'multiplicar' => $num1 * $num2,
        'dividir'     => $num1 / $num2,
    };
    // round evita mostrar cosas como 0.30000000000000004 (0.1 + 0.2)
    $resultado = round($resultado, 10);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
    <style>
        body   { font-family: Arial, sans-serif; max-width: 380px; margin: 40px auto; }
        .ok    { font-size: 22px; color: #27ae60; }
        .error { color: #c0392b; }
    </style>
</head>
<body>
    <h2>Resultado</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php else: ?>
        <!-- 5. Mostrar: los números ya están validados, el símbolo sale de NUESTRA lista -->
        <p class="ok"><?= $num1 ?> <?= $simbolos[$operacion] ?> <?= $num2 ?> = <strong><?= $resultado ?></strong></p>
    <?php endif; ?>

    <a href="formulario.html">Hacer otro cálculo</a>
</body>
</html>
