<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 02 — ¿Qué devuelve PDO?
 *  Tipo: predice la salida
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  Los proyectos de esta carpeta usan PDO para hablar con MySQL. Muchos errores
 *  vienen de NO saber qué devuelve cada método cuando la consulta no encuentra
 *  nada, o cuando no cambia nada.
 *
 *  Este archivo crea una tabla TEMPORAL (desaparece sola al terminar el script),
 *  así que no toca tus datos. Necesita la base tareas_crud (database/setup.sql)
 *  y config.php configurado.
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. NO lo ejecutes todavía.
 *  2. Escribe tu predicción de cada caso (fíjate en el TIPO del resultado).
 *  3. Ejecútalo y compara. Lee la explicación de los que fallaste.
 */

require_once __DIR__ . '/../../config.php';

$db = getDBConnection(DB_NAME_TAREAS);
$ver = fn($valor) => htmlspecialchars(var_export($valor, true));

$db->exec("CREATE TEMPORARY TABLE tareas_demo (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    hecha  TINYINT NOT NULL DEFAULT 0
)");
$db->exec("INSERT INTO tareas_demo (titulo) VALUES ('Estudiar PDO'), ('Hacer el taller'), ('Repasar sesiones')");

// ── CASO 1 ────────────────────────────────────────────────────────────────────
$stmt = $db->prepare("SELECT * FROM tareas_demo WHERE id = ?");
$stmt->execute([2]);
echo "Caso 1: " . $ver($stmt->fetch()) . "<br>";
// Tu predicción: ____________

// ── CASO 2 ────────────────────────────────────────────────────────────────────
$stmt->execute([999]);
echo "Caso 2: " . $ver($stmt->fetch()) . "<br>";
// Tu predicción (¿error? ¿null? ¿array vacío?): ____________

// ── CASO 3 ────────────────────────────────────────────────────────────────────
$stmt = $db->prepare("SELECT * FROM tareas_demo WHERE titulo LIKE ?");
$stmt->execute(['%taller%']);
echo "Caso 3a: " . count($stmt->fetchAll()) . " resultado(s)<br>";
$stmt->execute(['taller']);
echo "Caso 3b: " . count($stmt->fetchAll()) . " resultado(s)<br>";
// Tu predicción: ____________

// ── CASO 4 ────────────────────────────────────────────────────────────────────
$stmt = $db->prepare("INSERT INTO tareas_demo (titulo) VALUES (?)");
$stmt->execute(['Nueva tarea']);
echo "Caso 4: lastInsertId() = " . $ver($db->lastInsertId()) . "<br>";
// Tu predicción (fíjate en el tipo): ____________

// ── CASO 5 ────────────────────────────────────────────────────────────────────
$stmt = $db->prepare("DELETE FROM tareas_demo WHERE id = ?");
$stmt->execute([999]);
echo "Caso 5: filas borradas = " . $stmt->rowCount() . "<br>";
// Tu predicción (¿da error borrar algo que no existe?): ____________

// ── CASO 6 ────────────────────────────────────────────────────────────────────
$stmt = $db->prepare("UPDATE tareas_demo SET hecha = 1 WHERE id = ?");
$stmt->execute([1]);
echo "Caso 6a: filas cambiadas = " . $stmt->rowCount() . "<br>";
$stmt->execute([1]);   // la misma actualización otra vez
echo "Caso 6b: filas cambiadas = " . $stmt->rowCount() . "<br>";
// Tu predicción: ____________

// ── CASO 7 ────────────────────────────────────────────────────────────────────
$total = $db->query("SELECT COUNT(*) FROM tareas_demo")->fetchColumn();
echo "Caso 7: " . $ver($total) . "<br>";
// Tu predicción: ____________

// ── CASO 8 ────────────────────────────────────────────────────────────────────
try {
    $stmt = $db->prepare("SELECT * FROM tareas_demo WHERE id = :id AND hecha = :hecha");
    $stmt->execute([':id' => 1]);           // falta :hecha
    echo "Caso 8: se ejecutó sin problema<br>";
} catch (PDOException $e) {
    echo "Caso 8: PDOException — " . htmlspecialchars($e->getMessage()) . "<br>";
}
// Tu predicción: ____________


/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESPUESTAS Y EXPLICACIÓN (lee esto DESPUÉS de ejecutar)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CASO 1 -> array ('id' => 2, 'titulo' => 'Hacer el taller', 'hecha' => 0)
 *    fetch() devuelve UNA fila como array asociativo (por FETCH_ASSOC en config.php).
 *
 *  CASO 2 -> false
 *    Si no hay fila, fetch() devuelve false: no es un error ni null. Por eso
 *    el código siempre hace if (!$fila) { ... no encontrado ... }.
 *    (Compáralo con EstudianteModel::obtenerPorId(): devuelve array|false.)
 *
 *  CASO 3 -> 3a: 1 resultado | 3b: 0 resultados
 *    LIKE sin % busca el texto EXACTO. Los % hay que ponerlos en el VALOR que
 *    se envía, no en el SQL: así sigue siendo una consulta preparada segura.
 *
 *  CASO 4 -> '4'  (un TEXTO)
 *    lastInsertId() siempre devuelve un string. Si lo necesitas como número,
 *    usa (int) $db->lastInsertId().
 *
 *  CASO 5 -> filas borradas = 0
 *    Borrar algo que no existe NO es un error: simplemente no afecta ninguna
 *    fila. Si quieres avisar "no existía", revisa rowCount() o búscalo antes.
 *
 *  CASO 6 -> 6a: 1 | 6b: 0
 *    MySQL cuenta las filas que CAMBIARON. La segunda vez hecha ya valía 1, así
 *    que no cambió nada. Cuidado: rowCount() === 0 en un UPDATE no siempre
 *    significa "no existe", puede significar "ya tenía esos valores".
 *
 *  CASO 7 -> 4
 *    fetchColumn() devuelve la primera columna de la primera fila: ideal para
 *    COUNT(*). (Con la configuración de config.php llega como número; con
 *    otras configuraciones de PDO puede llegar como texto '4'.)
 *
 *  CASO 8 -> PDOException — SQLSTATE[HY093]: Invalid parameter number ...
 *    Si falta un parámetro, PDO lanza una excepción. Es bueno que avise: un
 *    error visible es mucho mejor que una consulta que hace otra cosa.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, cursor.fetchone() devuelve None cuando no hay fila. En PHP,
 *  fetch() devuelve false. Si escribes if ($fila === null) nunca será
 *  verdadero. Usa if (!$fila) o if ($fila === false).
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. En 15-Proyectos/CRUD/eliminar_tarea.php, ¿cómo sabrías si la tarea que se
 *     quiso borrar existía? Escribe el cambio.
 *  2. Si un UPDATE devuelve rowCount() = 0, ¿cómo distingues "no existe ese id"
 *     de "existe pero no cambió nada"?
 *  3. ¿Por qué el % se pone en el valor ('%taller%') y no en el SQL
 *     ("LIKE '%?%'")? Prueba la segunda forma: ¿qué pasa?
 */
