<?php

require_once __DIR__ . '/../config/Database.php';

class EstudianteModel
{
    // Columnas por las que se permite ordenar: nombre en la URL => columna SQL.
    // ORDER BY no acepta parametros (?), por eso se usa una lista blanca.
    public const ORDENABLES = [
        'id'     => 'e.id',
        'nombre' => 'e.nombre',
        'email'  => 'e.email',
        'ficha'  => 'e.ficha',
        'curso'  => 'c.nombre',
    ];

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ── Listar con busqueda, orden y paginacion ─────────────────────────────
    public function listar(string $buscar, string $orden, string $dir, int $limite, int $offset): array
    {
        $columna   = self::ORDENABLES[$orden] ?? 'e.id';
        $direccion = $dir === 'asc' ? 'ASC' : 'DESC';

        $stmt = $this->db->prepare(
            "SELECT e.*, c.nombre AS curso
             FROM estudiantes e
             LEFT JOIN cursos c ON c.id = e.curso_id
             WHERE e.nombre LIKE :buscar1 OR e.email LIKE :buscar2
             ORDER BY {$columna} {$direccion}
             LIMIT :limite OFFSET :offset"
        );
        $stmt->bindValue(':buscar1', "%{$buscar}%");
        $stmt->bindValue(':buscar2', "%{$buscar}%");
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);   // LIMIT exige un entero
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // ── Contar (para el total y la paginacion) ──────────────────────────────
    public function contar(string $buscar = ''): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM estudiantes WHERE nombre LIKE :buscar1 OR email LIKE :buscar2"
        );
        $stmt->execute([':buscar1' => "%{$buscar}%", ':buscar2' => "%{$buscar}%"]);
        return (int) $stmt->fetchColumn();
    }

    // ── Obtener un estudiante por su ID (con el nombre del curso) ───────────
    public function obtenerPorId(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT e.*, c.nombre AS curso
             FROM estudiantes e
             LEFT JOIN cursos c ON c.id = e.curso_id
             WHERE e.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ── Insertar nuevo estudiante ────────────────────────────────────────────
    public function crear(array $datos): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO estudiantes (nombre, email, ficha, telefono, curso_id)
             VALUES (:nombre, :email, :ficha, :telefono, :curso_id)"
        );
        try {
            return $stmt->execute($this->parametros($datos));
        } catch (PDOException $e) {
            return $this->manejarDuplicado($e);
        }
    }

    // ── Actualizar estudiante existente ──────────────────────────────────────
    public function actualizar(int $id, array $datos): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE estudiantes
             SET nombre   = :nombre,
                 email    = :email,
                 ficha    = :ficha,
                 telefono = :telefono,
                 curso_id = :curso_id
             WHERE id = :id"
        );
        try {
            return $stmt->execute($this->parametros($datos) + [':id' => $id]);
        } catch (PDOException $e) {
            return $this->manejarDuplicado($e);
        }
    }

    // ── Eliminar estudiante ──────────────────────────────────────────────────
    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM estudiantes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function parametros(array $datos): array
    {
        return [
            ':nombre'   => $datos['nombre'],
            ':email'    => $datos['email'],
            ':ficha'    => $datos['ficha'],
            ':telefono' => $datos['telefono'] ?: null,   // vacio -> NULL en la BD
            ':curso_id' => $datos['curso_id'],
        ];
    }

    private function manejarDuplicado(PDOException $e): bool
    {
        if ($e->getCode() === '23000') {
            return false;
        }
        throw $e;
    }
}
