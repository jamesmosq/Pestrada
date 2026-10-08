<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 03 — El buscador inseguro
 *  Tipo: encuentra el error
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  SITUACIÓN
 *  Se quiere agregar al CRUD un buscador de tareas por título. Un compañero
 *  escribió estas dos funciones:
 *    - buscarTareas(): devuelve TODAS las tareas cuyo título CONTIENE el texto
 *    - filaHtml():     arma la fila <tr> de la tabla para una tarea
 *
 *  Funcionan con búsquedas "normales"... pero tienen CUATRO errores, y dos de
 *  ellos son fallas de seguridad graves.
 *
 *  Este archivo usa una tabla TEMPORAL (se borra sola al terminar), así que
 *  puedes "atacarla" sin miedo. Necesita la base tareas_crud y config.php.
 *
 *  Pistas (solo si llevas 15 minutos sin avanzar):
 *    - Pista 1: ¿qué pasa si el texto que busca el usuario tiene una comilla '?
 *    - Pista 2: ¿qué devuelve fetch() y qué devuelve fetchAll()?
 *    - Pista 3: "contiene" ¿se escribe LIKE 'texto' o LIKE '%texto%'?
 *    - Pista 4: ¿qué pasa si el título de una tarea tiene etiquetas HTML?
 */

require_once __DIR__ . '/../../config.php';

function buscarTareas(PDO $db, string $texto): array
{
    $sql = "SELECT * FROM tareas_demo WHERE titulo LIKE '$texto'";
    $resultado = $db->query($sql)->fetch();
    return $resultado ?: [];
}

function filaHtml(array $tarea): string
{
    return "<tr><td>{$tarea['id']}</td><td>{$tarea['titulo']}</td></tr>";
}

// ── Datos de prueba (tabla temporal) ──────────────────────────────────────────
$db = getDBConnection(DB_NAME_TAREAS);
$db->exec("CREATE TEMPORARY TABLE tareas_demo (id INT AUTO_INCREMENT PRIMARY KEY, titulo VARCHAR(100))");
$db->exec("INSERT INTO tareas_demo (titulo) VALUES
    ('Estudiar PDO'), ('Estudiar sesiones'), ('Hacer el taller'),
    ('Tarea secreta del instructor'), ('Revisar <b>urgente</b>')");

// ── Pruebas automáticas: NO las modifiques, corrige las funciones ─────────────
function probar(string $descripcion, callable $prueba, $esperado): void
{
    try {
        $obtenido = $prueba();
    } catch (Throwable $e) {
        $obtenido = 'EXCEPCIÓN: ' . get_class($e);
    }
    $ok = $obtenido === $esperado;
    echo ($ok ? "OK    " : "FALLA ") . " $descripcion: esperado " . htmlspecialchars(var_export($esperado, true))
       . ", obtenido " . htmlspecialchars(var_export($obtenido, true)) . "<br>";
}

probar('buscar "Estudiar" encuentra 2 tareas',
    fn() => count(buscarTareas($db, 'Estudiar')), 2);

probar('buscar "taller" (parte del título) encuentra 1',
    fn() => count(buscarTareas($db, 'taller')), 1);

probar('buscar "nada que ver" encuentra 0',
    fn() => count(buscarTareas($db, 'nada que ver')), 0);

probar('ataque: buscar \' OR \'1\'=\'1 NO debe devolver todas',
    fn() => count(buscarTareas($db, "' OR '1'='1")), 0);

probar('buscar "o\'clock" (con comilla) no debe romper la consulta',
    fn() => count(buscarTareas($db, "o'clock")), 0);

probar('la fila escapa el HTML del título',
    fn() => filaHtml(['id' => 5, 'titulo' => 'Revisar <b>urgente</b>']),
    '<tr><td>5</td><td>Revisar &lt;b&gt;urgente&lt;/b&gt;</td></tr>');

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python es tentador escribir f"SELECT ... WHERE titulo LIKE '{texto}'" y
 *  en PHP "... LIKE '$texto'". Las dos son igual de peligrosas: el texto del
 *  usuario se convierte en PARTE del SQL. La solución es la misma en los dos
 *  lenguajes: consultas preparadas, donde el SQL y los datos viajan separados.
 *  (En Python: cursor.execute("... LIKE %s", (texto,)); en PHP: prepare + execute.)
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Explica con tus palabras qué SQL se ejecuta realmente cuando alguien
 *     busca  ' OR '1'='1  con la versión con errores. Escríbelo completo.
 *  2. La tabla tiene 5 tareas, pero la prueba del ataque dice "obtenido 2". ¿De
 *     dónde sale ese 2? (pista: error 2. ¿Qué cuenta count() si le das UNA sola
 *     fila, como ['id' => 1, 'titulo' => '...']?)
 *     ¿Qué pasa con el ataque si arreglas SOLO el error 2?
 *  3. Si un atacante puede hacer esto con un buscador, ¿qué podría hacer con un
 *     formulario de login escrito igual? (investiga: "SQL injection login bypass")
 *  4. ¿Por qué escapar con htmlspecialchars() se hace al MOSTRAR y no al GUARDAR?
 *
 *
 *
 *
 *
 *
 *
 *
 *  (sigue bajando solo cuando termines)
 *
 *
 *
 *
 *
 *
 *
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  VERSIÓN CORREGIDA
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  function buscarTareas(PDO $db, string $texto): array
 *  {
 *      $stmt = $db->prepare("SELECT * FROM tareas_demo WHERE titulo LIKE ?");
 *                                                        // ERROR 1: inyección SQL. El texto iba
 *                                                        // DENTRO del SQL; ahora va aparte (?)
 *      $stmt->execute(['%' . $texto . '%']);             // ERROR 3: sin % solo encontraba títulos
 *                                                        // idénticos; "contiene" es %texto%
 *      return $stmt->fetchAll();                         // ERROR 2: fetch() trae UNA fila (o false);
 *                                                        // fetchAll() trae todas (o [])
 *  }
 *
 *  function filaHtml(array $tarea): string
 *  {
 *      return '<tr><td>' . (int) $tarea['id'] . '</td><td>'
 *           . htmlspecialchars($tarea['titulo']) . '</td></tr>';
 *                                                        // ERROR 4: XSS. Sin escapar, un título
 *                                                        // con <script> se ejecutaría en el navegador
 *  }
 *
 *  Respuesta a la pregunta 1: se ejecuta
 *      SELECT * FROM tareas_demo WHERE titulo LIKE '' OR '1'='1'
 *  y como '1'='1' siempre es verdadero, la condición se cumple para todas las filas.
 *
 *  Respuesta a la pregunta 2: fetch() trajo solo la PRIMERA de las 5 filas, y
 *  count() de una fila cuenta sus columnas (id y titulo) = 2. Si arreglas solo
 *  el error 2 (fetchAll), el ataque devuelve las 5 tareas, incluida la "secreta".
 */
