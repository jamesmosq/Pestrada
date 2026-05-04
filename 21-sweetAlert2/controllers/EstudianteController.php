<?php

require_once __DIR__ . '/../models/EstudianteModel.php';

class EstudianteController
{
    private EstudianteModel $model;

    public function __construct()
    {
        $this->model = new EstudianteModel();
    }

    // ── Listar estudiantes ───────────────────────────────────────────────────
    public function index(): void
    {
        $status      = $_GET['status']  ?? '';
        $mensaje     = $_GET['mensaje'] ?? '';
        $estudiantes = $this->model->obtenerTodos();

        $this->cargarVista('estudiantes/index', compact('estudiantes', 'status', 'mensaje'));
    }

    // ── Mostrar formulario de creacion ───────────────────────────────────────
    public function crear(): void
    {
        $status  = $_GET['status']  ?? '';
        $mensaje = $_GET['mensaje'] ?? '';

        $this->cargarVista('estudiantes/crear', compact('status', 'mensaje'));
    }

    // ── Guardar nuevo estudiante (POST) ──────────────────────────────────────
    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('index');
            return;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email  = trim($_POST['email']  ?? '');
        $ficha  = trim($_POST['ficha']  ?? '');

        if (empty($nombre) || empty($email)) {
            $this->redirigir('crear', 'error',
                urlencode('El nombre y el email son obligatorios.'));
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirigir('crear', 'error',
                urlencode('El formato del email no es valido.'));
            return;
        }

        $ok = $this->model->crear([
            'nombre' => $nombre,
            'email'  => $email,
            'ficha'  => $ficha,
        ]);

        if ($ok) {
            $this->redirigir('index', 'creado',
                urlencode("El estudiante \"$nombre\" fue registrado correctamente."));
        } else {
            $this->redirigir('crear', 'error',
                urlencode('No se pudo guardar. El email ya puede estar en uso.'));
        }
    }

    // ── Mostrar formulario de edicion ────────────────────────────────────────
    public function editar(): void
    {
        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->redirigir('index', 'error', urlencode('ID no valido.'));
            return;
        }

        $estudiante = $this->model->obtenerPorId($id);

        if (!$estudiante) {
            $this->redirigir('index', 'error', urlencode('Estudiante no encontrado.'));
            return;
        }

        $status  = $_GET['status']  ?? '';
        $mensaje = $_GET['mensaje'] ?? '';

        $this->cargarVista('estudiantes/editar',
            compact('estudiante', 'status', 'mensaje'));
    }

    // ── Actualizar estudiante (POST) ─────────────────────────────────────────
    public function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('index');
            return;
        }

        $id     = intval($_POST['id']     ?? 0);
        $nombre = trim($_POST['nombre']   ?? '');
        $email  = trim($_POST['email']    ?? '');
        $ficha  = trim($_POST['ficha']    ?? '');

        if ($id <= 0 || empty($nombre) || empty($email)) {
            $this->redirigir('editar', 'error',
                urlencode('Datos incompletos.'), $id);
            return;
        }

        $ok = $this->model->actualizar($id, [
            'nombre' => $nombre,
            'email'  => $email,
            'ficha'  => $ficha,
        ]);

        if ($ok) {
            $this->redirigir('index', 'editado',
                urlencode("Los datos de \"$nombre\" fueron actualizados."));
        } else {
            $this->redirigir('editar', 'error',
                urlencode('No se pudo actualizar. El email puede estar en uso.'), $id);
        }
    }

    // ── Eliminar estudiante ──────────────────────────────────────────────────
    public function eliminar(): void
    {
        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->redirigir('index', 'error', urlencode('ID no valido.'));
            return;
        }

        $est = $this->model->obtenerPorId($id);

        if (!$est) {
            $this->redirigir('index', 'error', urlencode('Estudiante no encontrado.'));
            return;
        }

        $ok = $this->model->eliminar($id);

        if ($ok) {
            $this->redirigir('index', 'eliminado',
                urlencode("\"$est[nombre]\" fue eliminado del sistema."));
        } else {
            $this->redirigir('index', 'error',
                urlencode('No se pudo eliminar el registro.'));
        }
    }
    // ════════════════════════════════════════════════════════════════════════
    // METODOS PRIVADOS AUXILIARES
    // ════════════════════════════════════════════════════════════════════════

    // Carga el archivo de vista y le inyecta variables mediante extract()
    private function cargarVista(string $vista, array $datos = []): void
    {
        extract($datos);
        require __DIR__ . "/../views/{$vista}.php";
    }

    // Construye y ejecuta una redireccion hacia el router
    private function redirigir(
        string $action,
        string $status  = '',
        string $mensaje = '',
        int    $id      = 0
    ): void {
        $url = "index.php?action={$action}";
        if ($status)  $url .= "&status={$status}";
        if ($mensaje) $url .= "&mensaje={$mensaje}";
        if ($id > 0)  $url .= "&id={$id}";
        header("Location: {$url}");
        exit;
    }
}
