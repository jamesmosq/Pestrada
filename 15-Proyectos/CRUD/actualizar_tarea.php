<?php
include("db.php");

if (isset($_GET['id'])) {
    $id   = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tareas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row  = $stmt->fetch();

    if ($row) {
        $titulo      = $row['titulo'];
        $descripcion = $row['descripcion'];
    } else {
        header("Location: index.php");
        exit;
    }
}

if (isset($_POST['actualizar'])) {
    $id          = $_GET['id'];
    $titulo      = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conn->prepare("UPDATE tareas SET titulo = :titulo, descripcion = :descripcion WHERE id = :id");
    $stmt->execute([':titulo' => $titulo, ':descripcion' => $descripcion, ':id' => $id]);

    $_SESSION['message']      = 'Tarea actualizada';
    $_SESSION['message_type'] = 'warning';
    header("Location: index.php");
    exit;
}
?>

<?php include("includes/header.php") ?>

<div class="container p-4">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <div class="card card-body">
                <form action="actualizar_tarea.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="POST">
                    <div class="form-group">
                        <input type="text" name="titulo"
                               value="<?php echo htmlspecialchars($titulo); ?>"
                               class="form-control" placeholder="Actualiza el titulo">
                    </div>
                    <div class="form-group">
                        <textarea name="descripcion" rows="2" class="form-control" placeholder="Actualiza la descripción"><?php echo htmlspecialchars($descripcion); ?></textarea>
                    </div>
                    <button class="btn btn-success" name="actualizar">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php") ?>
