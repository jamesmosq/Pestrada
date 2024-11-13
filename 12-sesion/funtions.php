<?php
function isLoggedIn()
{
    return isset($_SESSION['username']);
}

function verifyCredentials($username, $password)
{
    // Aquí deberías comprobar las credenciales contra una base de datos o un archivo
    return $username == 'usuario' && $password == 'contraseña';
}

function displayLoginForm()
{
    echo "<form action='login.php' method='post'>";
    echo "Nombre de usuario: <input type='text' name='username'><br>";
    echo "Contraseña: <input type='password' name='password'><br>";
    echo "<input type='submit' value='Iniciar sesión'>";
    echo "</form>";
}
?>