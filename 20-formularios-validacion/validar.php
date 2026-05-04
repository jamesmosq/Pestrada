<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de Validación</title>
    <style>
        body   { font-family: Arial, sans-serif; max-width: 550px; margin: 40px auto; padding: 0 20px; }
        .error { color: #c0392b; background: #fdecea; padding: 8px 12px;
                 border-left: 4px solid #c0392b; margin: 5px 0; border-radius: 3px; }
        .ok    { color: #27ae60; background: #eafaf1; padding: 8px 12px;
                 border-left: 4px solid #27ae60; margin: 5px 0; border-radius: 3px; }
        .field { font-weight: bold; }
        a      { display: inline-block; margin-top: 20px; color: #555; }
    </style>
</head>
<body>

<?php
// Solo procesar si vino del formulario por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.html");
    exit;
}

// ── 1. Recoger y limpiar entradas ────────────────────────────────────────────
$nombre   = trim($_POST['nombre']   ?? '');
$email    = trim($_POST['email']    ?? '');
$edad     = trim($_POST['edad']     ?? '');
$password = $_POST['password']  ?? '';
$password2= $_POST['password2'] ?? '';
$rol      = trim($_POST['rol']      ?? '');

$errores = [];

// ── 2. Validaciones ──────────────────────────────────────────────────────────

// Nombre
if (empty($nombre)) {
    $errores['nombre'] = "El nombre es obligatorio.";
} elseif (strlen($nombre) < 3) {
    $errores['nombre'] = "El nombre debe tener al menos 3 caracteres.";
} elseif (strlen($nombre) > 60) {
    $errores['nombre'] = "El nombre no puede superar los 60 caracteres.";
} elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
    $errores['nombre'] = "El nombre solo puede contener letras y espacios.";
}

// Email — filter_var es la forma correcta de validar emails en PHP
if (empty($email)) {
    $errores['email'] = "El email es obligatorio.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores['email'] = "El formato del email no es válido.";
}

// Edad — filter_var con FILTER_VALIDATE_INT valida que sea un entero
if (empty($edad)) {
    $errores['edad'] = "La edad es obligatoria.";
} elseif (!filter_var($edad, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 120]])) {
    $errores['edad'] = "La edad debe ser un número entero entre 1 y 120.";
}

// Contraseña
if (empty($password)) {
    $errores['password'] = "La contraseña es obligatoria.";
} elseif (strlen($password) < 6) {
    $errores['password'] = "La contraseña debe tener al menos 6 caracteres.";
}

// Confirmar contraseña
if (empty($password2)) {
    $errores['password2'] = "Debes confirmar la contraseña.";
} elseif ($password !== $password2) {
    $errores['password2'] = "Las contraseñas no coinciden.";
}

// Rol
$roles_validos = ['estudiante', 'docente', 'admin'];
if (empty($rol)) {
    $errores['rol'] = "Debes seleccionar un rol.";
} elseif (!in_array($rol, $roles_validos)) {
    $errores['rol'] = "El rol seleccionado no es válido.";
}

// ── 3. Mostrar resultado ─────────────────────────────────────────────────────
if (!empty($errores)) {
    echo "<h2>Se encontraron errores:</h2>";
    foreach ($errores as $campo => $mensaje) {
        echo "<div class='error'><span class='field'>$campo:</span> $mensaje</div>";
    }
    echo "<a href='index.html'>← Volver al formulario</a>";
} else {
    // Sin errores — aquí normalmente guardarías en BD
    echo "<h2>✓ Formulario válido</h2>";
    echo "<div class='ok'>Nombre: "  . htmlspecialchars($nombre) . "</div>";
    echo "<div class='ok'>Email: "   . htmlspecialchars($email)  . "</div>";
    echo "<div class='ok'>Edad: "    . (int)$edad                . " años</div>";
    echo "<div class='ok'>Rol: "     . htmlspecialchars($rol)    . "</div>";
    echo "<div class='ok'>Contraseña: " . str_repeat("*", strlen($password)) . "</div>";
    echo "<p><strong>Siguiente paso:</strong> guardar en base de datos con password_hash().</p>";
    echo "<a href='index.html'>← Registrar otro usuario</a>";
}
?>

</body>
</html>
