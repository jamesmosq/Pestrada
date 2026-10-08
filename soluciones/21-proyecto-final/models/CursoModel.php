<?php

require_once __DIR__ . '/../config/Database.php';

class CursoModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Cada curso con la cantidad de estudiantes inscritos
    public function obtenerTodos(): array
    {
        return $this->db->query(
            "SELECT c.*, COUNT(e.id) AS total_estudiantes
             FROM cursos c
             LEFT JOIN estudiantes e ON e.curso_id = c.id
             GROUP BY c.id
             ORDER BY c.nombre"
        )->fetchAll();
    }

    public function existe(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cursos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function crear(string $nombre): bool
    {
        $stmt = $this->db->prepare("INSERT INTO cursos (nombre) VALUES (?)");
        try {
            return $stmt->execute([$nombre]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return false;   // nombre repetido
            }
            throw $e;
        }
    }

    public function eliminar(int $id): bool
    {
        // ON DELETE SET NULL: los estudiantes del curso quedan "sin curso"
        $stmt = $this->db->prepare("DELETE FROM cursos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
