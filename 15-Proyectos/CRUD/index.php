<?php include("db.php") ?>

<?php include("includes/header.php") ?>

<div class="container p-4">

    <div class="row">
        
        <div class="col-md 4">
            
            <?php if(isset($_SESSION['message'])) {?>
                <div class="alert alert-<?= $_SESSION['message_type']?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            <?php session_unset(); } ?>

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
                        
                        $query = "SELECT * FROM tareas";
                        $result_tareas = mysqli_query($conn, $query);

                        while($row = mysqli_fetch_array($result_tareas)) { ?>

                            <tr>
                                <td><?php echo $row['titulo'] ?></td>
                                <td><?php echo $row['descripcion'] ?></td>
                                <td><?php echo $row['created'] ?></td>
                                <td>
                                    <a href="actualizar_tarea.php?id=<?php echo $row['id'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="eliminar_tarea.php?id=<?php echo $row['id'] ?>" class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>

                       <?php } ?>
                    </tbody>
                </table>
        </div>
    </div>

</div>




<?php include("includes/footer.php") ?>
