<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 01 — Dividir la cuenta del restaurante
 *  Tipo: resuelto y comentado
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  ENUNCIADO
 *  Un formulario recibe el valor de la cuenta, el número de personas y el
 *  porcentaje de propina (0, 10 o 15). Mostrar cuánto paga cada persona.
 *  Si algo está mal, mostrar el error y conservar lo que el usuario escribió.
 *
 *  CÓMO SE PENSÓ (antes de escribir código)
 *  1. Un solo archivo hace dos cosas: MOSTRAR el formulario y PROCESARLO.
 *     ¿Cómo sabe cuál toca? Mirando $_SERVER['REQUEST_METHOD']:
 *       - GET  -> el usuario acaba de llegar: solo mostrar el formulario
 *       - POST -> el usuario envió el formulario: validar y calcular
 *  2. Todo lo que llega en $_POST es TEXTO (o no llega). Antes de calcular:
 *     convertir y validar cada campo.
 *  3. La propina solo puede ser 0, 10 o 15: lista blanca, no "cualquier número".
 *  4. Al mostrar de vuelta lo que escribió el usuario: htmlspecialchars().
 */

$propinasValidas = [0, 10, 15];
$errores = [];
$resultado = null;

// Valores del formulario: vacíos la primera vez, lo enviado si hubo POST
$cuenta   = '';
$personas = '';
$propina  = '10';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ?? '' evita el warning si un campo no llegó
    $cuenta   = trim($_POST['cuenta']   ?? '');
    $personas = trim($_POST['personas'] ?? '');
    $propina  = $_POST['propina'] ?? '';

    // filter_var convierte Y valida: devuelve el número o false
    $valorCuenta = filter_var($cuenta, FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 1]]);
    $numPersonas = filter_var($personas, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 50]]);

    if ($valorCuenta === false) {
        $errores[] = 'El valor de la cuenta debe ser un número mayor que cero.';
    }
    if ($numPersonas === false) {
        $errores[] = 'El número de personas debe ser un entero entre 1 y 50.';
    }
    // in_array con true (estricto) para que "10abc" no pase como 10
    if (!in_array((int) $propina, $propinasValidas, true) || !ctype_digit((string) $propina)) {
        $errores[] = 'Elige una propina válida.';
    }

    if (!$errores) {
        $total     = $valorCuenta * (1 + (int) $propina / 100);
        $porPersona = $total / $numPersonas;
        $resultado = [
            'total'      => $total,
            'porPersona' => ceil($porPersona / 100) * 100,   // redondear hacia arriba a la centena
        ];
    }
}

function pesos(float $valor): string
{
    return '$ ' . number_format($valor, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dividir la cuenta</title>
    <style>
        body  { font-family: Arial, sans-serif; max-width: 420px; margin: 30px auto; }
        input, select { width: 100%; padding: 6px; margin-bottom: 12px; box-sizing: border-box; }
        .error { color: #c0392b; }
        .ok    { color: #27ae60; font-size: 18px; }
    </style>
</head>
<body>
    <h2>Dividir la cuenta</h2>

    <?php foreach ($errores as $error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>

    <?php if ($resultado): ?>
        <p class="ok">
            Total con propina: <?= pesos($resultado['total']) ?><br>
            Cada persona paga: <strong><?= pesos($resultado['porPersona']) ?></strong>
        </p>
    <?php endif; ?>

    <!-- action vacío: el formulario se envía a este mismo archivo -->
    <form method="POST" action="">
        <label>Valor de la cuenta</label>
        <input type="text" name="cuenta" value="<?= htmlspecialchars($cuenta) ?>">

        <label>Personas</label>
        <input type="text" name="personas" value="<?= htmlspecialchars($personas) ?>">

        <label>Propina</label>
        <select name="propina">
            <?php foreach ($propinasValidas as $p): ?>
                <option value="<?= $p ?>" <?= (string) $p === (string) $propina ? 'selected' : '' ?>><?= $p ?> %</option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Calcular</button>
    </form>
</body>
</html>
<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAZA — dos visitas a la misma página
 * ════════════════════════════════════════════════════════════════════════════
 *
 *   visita                      | REQUEST_METHOD | $_POST                          | se muestra
 *   ----------------------------+----------------+---------------------------------+-----------------------
 *   1. el usuario entra         | GET            | [] (vacío)                      | formulario vacío
 *   2. envía 85000, 3 y 10 %    | POST           | ['cuenta' => '85000',           | resultado + formulario
 *                               |                |  'personas' => '3',             | con los mismos datos
 *                               |                |  'propina' => '10']             |
 *
 *   Con esos datos: 85000 * 1.10 = 93.500 de total; 93.500 / 3 = 31.166,67
 *   -> redondeado hacia arriba a la centena: 31.200 por persona.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python (con Flask o Django) normalmente pides un dato del formulario y,
 *  si no existe, obtienes un error claro o un valor por defecto. En PHP,
 *  $_POST['cuenta'] cuando el campo no llegó da un Warning y null, y el
 *  programa SIGUE con ese null. Por eso aquí siempre se escribe
 *  $_POST['cuenta'] ?? ''  (como el .get('cuenta', '') de un diccionario).
 *
 *  Y otra: "85000" + "3" en PHP da 85003 (convierte los textos a número);
 *  en Python "85000" + "3" da "850003". Igual, no confíes en eso: valida y
 *  convierte tú con filter_var o (int).
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Por qué los valores del formulario se guardan en $cuenta y $personas
 *     ANTES de validar, y no después?
 *  2. Con F12, cambia una opción del select a value="90" y envía. ¿Qué pasa?
 *     ¿Qué línea del código te protegió?
 *  3. ¿Qué pasaría si quitas htmlspecialchars() y escribes en la cuenta:
 *     "><script>alert('hola')</script>
 *  4. ¿Por qué se usa POST y no GET para este formulario? ¿Sería grave usar GET aquí?
 *
 *  PARA MODIFICAR
 *  - Agrega la opción de propina "otra" con un campo para escribir el porcentaje (0 a 30).
 *  - Muestra también cuánto paga cada persona SIN propina.
 */
