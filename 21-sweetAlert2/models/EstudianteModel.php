<?php

require_once __DIR__ . '/../config/Database.php';

class EstudianteModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ── Obtener todos los estudiantes ────────────────────────────────────────
    public function obtenerTodos(): array
    {
        $stmt = $this->db->query("SELECT * FROM estudiantes ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // ── Obtener un estudiante por su ID ──────────────────────────────────────
    public function obtenerPorId(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM estudiantes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ── Insertar nuevo estudiante ────────────────────────────────────────────
    public function crear(array $datos): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO estudiantes (nombre, email, ficha)
             VALUES (:nombre, :email, :ficha)"
        );
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':email'  => $datos['email'],
            ':ficha'  => $datos['ficha'],
        ]);
    }

    // ── Actualizar estudiante existente ──────────────────────────────────────
    public function actualizar(int $id, array $datos): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE estudiantes
             SET nombre = :nombre,
                 email  = :email,
                 ficha  = :ficha
             WHERE id = :id"
        );
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':email'  => $datos['email'],
            ':ficha'  => $datos['ficha'],
            ':id'     => $id,
        ]);
    }

    // ── Eliminar estudiante ──────────────────────────────────────────────────
    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM estudiantes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}