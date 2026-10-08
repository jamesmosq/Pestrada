arbol de archivos 

mi_crud/
├── config/
│   └── database.php
├── includes/
│   └── funciones.php
├── css/
│   └── style.css
├── index.php
├── crear.php
├── editar.php
└── eliminar.php

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS la base de datos ;
USE la base de datos ;

-- Crear la tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    edad INT NOT NULL
);



// config/database.php
<?php
$servername = "localhost";
$username = "usuario";
$password = "contraseña";
$dbname = "la base de datos ";

function conectarDB() {
    global $servername, $username, $password, $dbname;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        return null;
    }
}

// includes/funciones.php
<?php
require_once __DIR__ . '/../config/database.php';

// CREATE - Insertar un nuevo registro
function crearUsuario($nombre, $email, $edad) {
    try {
        $conn = conectarDB();
        $sql = "INSERT INTO usuarios (nombre, email, edad) VALUES (:nombre, :email, :edad)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':edad', $edad);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        echo "Error al crear: " . $e->getMessage();
        return false;
    }
}

// READ - Obtener todos los registros
function obtenerUsuarios() {
    try {
        $conn = conectarDB();
        $sql = "SELECT * FROM usuarios";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error al leer: " . $e->getMessage();
        return [];
    }
}

// READ - Obtener un registro específico
function obtenerUsuario($id) {
    try {
        $conn = conectarDB();
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error al leer: " . $e->getMessage();
        return null;
    }
}

// UPDATE - Actualizar un registro
function actualizarUsuario($id, $nombre, $email, $edad) {
    try {
        $conn = conectarDB();
        $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, edad = :edad WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':edad', $edad);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        echo "Error al actualizar: " . $e->getMessage();
        return false;
    }
}

// DELETE - Eliminar un registro
function eliminarUsuario($id) {
    try {
        $conn = conectarDB();
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        echo "Error al eliminar: " . $e->getMessage();
        return false;
    }
}

// css/style.css
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f4f4f4;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin: 10px 0;
}

form div {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

button {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

// index.php
<?php
require_once 'includes/funciones.php';

// Obtener todos los usuarios
$usuarios = obtenerUsuarios();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Usuarios</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Usuarios</h1>
        <a href="crear.php" class="btn">Nuevo Usuario</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Edad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['edad']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $usuario['id']; ?>">Editar</a>
                        <a href="eliminar.php?id=<?php echo $usuario['id']; ?>" 
                           onclick="return confirm('¿Está seguro?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

// crear.php
<?php
require_once 'includes/funciones.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $edad = $_POST['edad'] ?? '';
    
    if (crearUsuario($nombre, $email, $edad)) {
        header('Location: index.php');
        exit;
    } else {
        $mensaje = 'Error al crear el usuario';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Crear Usuario</h1>
        <?php if ($mensaje): ?>
            <p style="color: red;"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
        <form method="POST">
            <div>
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label>Edad:</label>
                <input type="number" name="edad" required>
            </div>
            <button type="submit">Guardar</button>
            <a href="index.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>

// editar.php
<?php
require_once 'includes/funciones.php';

$mensaje = '';
$usuario = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario = obtenerUsuario($id);
    
    if (!$usuario) {
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $edad = $_POST['edad'] ?? '';
    
    if (actualizarUsuario($id, $nombre, $email, $edad)) {
        header('Location: index.php');
        exit;
    } else {
        $mensaje = 'Error al actualizar el usuario';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Usuario</h1>
        <?php if ($mensaje): ?>
            <p style="color: red;"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
        <?php if ($usuario): ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id']); ?>">
            <div>
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div>
                <label>Edad:</label>
                <input type="number" name="edad" value="<?php echo htmlspecialchars($usuario['edad']); ?>" required>
            </div>
            <button type="submit">Actualizar</button>
            <a href="index.php" class="btn">Cancelar</a>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>

// eliminar.php
<?php
require_once 'includes/funciones.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    eliminarUsuario($id);
}

header('Location: index.php');
exit;
