proyecto/
├── config/
│   └── Database.php
├── models/
│   └── Usuario.php
├── css/
│   └── style.css
├── index.php
├── crear.php
├── editar.php
└── eliminar.php


// config/Database.php
<?php
class Database {
    private $host = "localhost";
    private $db_name = "mi_base_de_datos";
    private $username = "usuario";
    private $password = "contraseña";
    private $conn = null;

    public function getConnection() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        return $this->conn;
    }
}

// models/Usuario.php
<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $edad;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (nombre, email, edad) VALUES (:nombre, :email, :edad)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":edad", $this->edad);

            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function leer() {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function leerUno() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function actualizar() {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET nombre = :nombre, email = :email, edad = :edad 
                     WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":edad", $this->edad);
            $stmt->bindParam(":id", $this->id);

            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function eliminar() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

// index.php
<?php
require_once 'config/Database.php';
require_once 'models/Usuario.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);
$stmt = $usuario->leer();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD POO PHP</title>
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
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['edad']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $row['id'] ?>">Editar</a>
                        <a href="eliminar.php?id=<?= $row['id'] ?>" 
                           onclick="return confirm('¿Está seguro?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

// crear.php
<?php
require_once 'config/Database.php';
require_once 'models/Usuario.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario->nombre = $_POST['nombre'] ?? '';
    $usuario->email = $_POST['email'] ?? '';
    $usuario->edad = $_POST['edad'] ?? '';
    
    if ($usuario->crear()) {
        header('Location: index.php');
        exit;
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
require_once 'config/Database.php';
require_once 'models/Usuario.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

if (isset($_GET['id'])) {
    $usuario->id = $_GET['id'];
    $stmt = $usuario->leerUno();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        header('Location: index.php');
        exit;
    }
    
    $usuario->nombre = $row['nombre'];
    $usuario->email = $row['email'];
    $usuario->edad = $row['edad'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario->id = $_POST['id'];
    $usuario->nombre = $_POST['nombre'];
    $usuario->email = $_POST['email'];
    $usuario->edad = $_POST['edad'];
    
    if ($usuario->actualizar()) {
        header('Location: index.php');
        exit;
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
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario->id) ?>">
            <div>
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario->nombre) ?>" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($usuario->email) ?>" required>
            </div>
            <div>
                <label>Edad:</label>
                <input type="number" name="edad" value="<?= htmlspecialchars($usuario->edad) ?>" required>
            </div>
            <button type="submit">Actualizar</button>
            <a href="index.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>

// eliminar.php
<?php
require_once 'config/Database.php';
require_once 'models/Usuario.php';

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $usuario = new Usuario($db);
    $usuario->id = $_GET['id'];
    $usuario->eliminar();
}

header('Location: index.php');
exit;
