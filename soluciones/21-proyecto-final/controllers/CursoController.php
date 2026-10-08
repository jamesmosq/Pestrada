<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/CursoModel.php';

class CursoController extends Controller
{
    protected string $modulo = 'cursos';

    private CursoModel $model;

    public function __construct()
    {
        $this->model = new CursoModel();
    }

    public function index(): void
    {
        $cursos = $this->model->obtenerTodos();
        $this->cargarVista('cursos/index', compact('cursos'));
    }

    public function guardar(): void
    {
        $this->exigirPost();

        $nombre = trim($_POST['nombre'] ?? '');

        if (mb_strlen($nombre) < 3) {
            $this->flash('error', 'El nombre del curso debe tener al menos 3 caracteres.');
        } elseif ($this->model->crear($nombre)) {
            $this->flash('creado', "Curso \"$nombre\" creado.");
        } else {
            $this->flash('error', "Ya existe un curso llamado \"$nombre\".");
        }
        $this->redirigir('index');
    }

    public function eliminar(): void
    {
        $this->exigirPost();

        $this->model->eliminar((int) ($_POST['id'] ?? 0));
        $this->flash('eliminado', 'Curso eliminado. Sus estudiantes quedaron sin curso.');
        $this->redirigir('index');
    }
}
