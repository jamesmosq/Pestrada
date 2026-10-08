<?php include("db.php") ?>

<?php include("includes/header.php") ?>

<div class="container p-4">

    <div class="row">
        
        <div class="col-md-4">

            <?php if(isset($_SESSION['message'])) {?>
                <div class="alert alert-<?= htmlspecialchars($_SESSION['message_type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['message']) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            <?php
                // Borrar SOLO el mensaje; session_unset() borraria toda la sesion
                unset($_SESSION['message'], $_SESSION['message_type']);
            } ?>

            <div class="card card-body">
                <form action="guardar_tarea.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="titulo" class="form-control" placeholder="Titulo tarea" autofocus>
                    </div>
                    <div class="form-group">
                        <textarea name="descripcion" rows="2" class="form-control" placeholder="Descripción tarea"></textarea>
                    </div>
                    <input type="submit" class="btn btn-success btn-block" name="guardar_tarea" value="Guardar Tarea">
                </form>
            </div>

        </div>

        <div class="col-md-8">
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>Descripción</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $conn->query("SELECT * FROM tareas ORDER BY fecha_creacion DESC");
                        while($row = $stmt->fetch()) { ?>

                            <tr>
                                <!-- htmlspecialchars(): todo dato que escribio un usuario se escapa al mostrarlo -->
                                <td><?php echo htmlspecialchars($row['titulo']) ?></td>
                                <td><?php echo htmlspecialchars($row['descripcion'] ?? '') ?></td>
                                <td><?php echo $row['fecha_creacion'] ?></td>
                                <td>
                                    <a href="actualizar_tarea.php?id=<?php echo (int) $row['id'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Eliminar se hace por POST: un enlace (GET) no debe borrar datos -->
                                    <form action="eliminar_tarea.php" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar esta tarea?')">
                                        <input type="hidden" name="id" value="<?php echo (int) $row['id'] ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                       <?php } ?>
                    </tbody>
                </table>
        </div>
    </div>

</div>




<?php include("includes/footer.php") ?>
