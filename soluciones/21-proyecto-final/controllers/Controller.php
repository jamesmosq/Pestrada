<?php

// Clase padre de todos los controladores: aqui va lo que TODOS necesitan.
// Laravel tiene exactamente esto: app/Http/Controllers/Controller.php
abstract class Controller
{
    // Valor de ?c= en la URL. Cada hijo lo cambia (estudiantes, cursos, ...)
    protected string $modulo = 'estudiantes';

    // Carga la vista e inyecta $csrf y $flash en todas
    protected function cargarVista(string $vista, array $datos = []): void
    {
        $datos['csrf']  = $this->csrfToken();
        $datos['flash'] = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);           // el mensaje se muestra una sola vez

        extract($datos);
        require __DIR__ . "/../views/{$vista}.php";
    }

    protected function redirigir(string $action = 'index', int $id = 0): void
    {
        $url = "index.php?c={$this->modulo}&action={$action}";
        if ($id > 0) {
            $url .= "&id={$id}";
        }
        header("Location: {$url}");
        exit;
    }

    // Guarda un mensaje para mostrarlo despues de la redireccion
    protected function flash(string $tipo, string $mensaje): void
    {
        $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    }

    // Toda accion que modifica datos empieza llamando a este metodo
    protected function exigirPost(string $siFalla = 'index', int $id = 0): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('index');
        }
        $token = $_POST['csrf_token'] ?? '';
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $this->flash('error', 'La sesion expiro. Recarga la pagina e intenta de nuevo.');
            $this->redirigir($siFalla, $id);
        }
    }

    private function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
