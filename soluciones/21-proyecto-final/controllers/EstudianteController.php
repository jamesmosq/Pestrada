<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/EstudianteModel.php';
require_once __DIR__ . '/../models/CursoModel.php';

class EstudianteController extends Controller
{
    private const POR_PAGINA = 5;

    protected string $modulo = 'estudiantes';

    private EstudianteModel $model;
    private CursoModel $cursos;

    public function __construct()
    {
        $this->model  = new EstudianteModel();
        $this->cursos = new CursoModel();
    }

    // ── Listar: busqueda + orden + paginacion ────────────────────────────────
    public function index(): void
    {
        $buscar = trim($_GET['q'] ?? '');

        $orden = $_GET['orden'] ?? 'id';
        if (!array_key_exists($orden, EstudianteModel::ORDENABLES)) {
            $orden = 'id';
        }
        $dir = ($_GET['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $total   = $this->model->contar($buscar);
        $paginas = max(1, (int) ceil($total / self::POR_PAGINA));
        $pagina  = min(max(1, (int) ($_GET['pagina'] ?? 1)), $paginas);
        $offset  = ($pagina - 1) * self::POR_PAGINA;

        $estudiantes = $this->model->listar($buscar, $orden, $dir, self::POR_PAGINA, $offset);

        $this->cargarVista('estudiantes/index',
            compact('estudiantes', 'buscar', 'orden', 'dir', 'total', 'pagina', 'paginas'));
    }

    // ── Ver detalle ──────────────────────────────────────────────────────────
    public function ver(): void
    {
        $estudiante = $this->buscarOFallar((int) ($_GET['id'] ?? 0));
        $this->cargarVista('estudiantes/ver', compact('estudiante'));
    }

    public function crear(): void
    {
        $cursos = $this->cursos->obtenerTodos();
        $this->cargarVista('estudiantes/crear', compact('cursos'));
    }

    public function guardar(): void
    {
        $this->exigirPost('crear');

        $datos = $this->datosDelFormulario();
        if ($error = $this->validar($datos)) {
            $this->flash('error', $error);
            $this->redirigir('crear');
        }

        if ($this->model->crear($datos)) {
            $this->flash('creado', "El estudiante \"{$datos['nombre']}\" fue registrado correctamente.");
            $this->redirigir('index');
        }
        $this->flash('error', 'No se pudo guardar. El email ya puede estar en uso.');
        $this->redirigir('crear');
    }

    public function editar(): void
    {
        $estudiante = $this->buscarOFallar((int) ($_GET['id'] ?? 0));
        $cursos     = $this->cursos->obtenerTodos();
        $this->cargarVista('estudiantes/editar', compact('estudiante', 'cursos'));
    }

    public function actualizar(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->exigirPost('editar', $id);

        $datos = $this->datosDelFormulario();
        if ($error = $this->validar($datos)) {
            $this->flash('error', $error);
            $this->redirigir('editar', $id);
        }

        if ($this->model->actualizar($id, $datos)) {
            $this->flash('editado', "Los datos de \"{$datos['nombre']}\" fueron actualizados.");
            $this->redirigir('index');
        }
        $this->flash('error', 'No se pudo actualizar. El email puede estar en uso.');
        $this->redirigir('editar', $id);
    }

    public function eliminar(): void
    {
        $this->exigirPost();

        $est = $this->buscarOFallar((int) ($_POST['id'] ?? 0));

        if ($this->model->eliminar($est['id'])) {
            $this->flash('eliminado', "\"{$est['nombre']}\" fue eliminado del sistema.");
        } else {
            $this->flash('error', 'No se pudo eliminar el registro.');
        }
        $this->redirigir('index');
    }

    // ════════════════════════════════════════════════════════════════════════
    // METODOS PRIVADOS AUXILIARES
    // ════════════════════════════════════════════════════════════════════════

    // Busca el estudiante o vuelve al listado con un error
    private function buscarOFallar(int $id): array
    {
        $estudiante = $id > 0 ? $this->model->obtenerPorId($id) : false;
        if (!$estudiante) {
            $this->flash('error', 'Estudiante no encontrado.');
            $this->redirigir('index');
        }
        return $estudiante;
    }

    private function datosDelFormulario(): array
    {
        $cursoId = (int) ($_POST['curso_id'] ?? 0);

        return [
            'nombre'   => trim($_POST['nombre'] ?? ''),
            'email'    => trim($_POST['email']  ?? ''),
            'ficha'    => trim($_POST['ficha']  ?? ''),
            'telefono' => str_replace([' ', '-'], '', trim($_POST['telefono'] ?? '')),
            'curso_id' => $cursoId > 0 ? $cursoId : null,   // sin curso -> NULL
        ];
    }

    // Devuelve el primer error encontrado, o null si todo esta bien
    private function validar(array $d): ?string
    {
        if ($d['nombre'] === '' || $d['email'] === '') {
            return 'El nombre y el email son obligatorios.';
        }
        if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            return 'El formato del email no es valido.';
        }
        if ($d['ficha'] !== '' && !preg_match('/^\d{7}$/', $d['ficha'])) {
            return 'La ficha debe tener exactamente 7 digitos.';
        }
        if ($d['telefono'] !== '' && !preg_match('/^3\d{9}$/', $d['telefono'])) {
            return 'El telefono debe ser un celular de 10 digitos que empiece por 3.';
        }
        if ($d['curso_id'] !== null && !$this->cursos->existe($d['curso_id'])) {
            return 'El curso seleccionado no existe.';
        }
        return null;
    }
}
