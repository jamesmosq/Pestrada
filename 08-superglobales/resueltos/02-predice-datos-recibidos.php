<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 02 — ¿Qué llega realmente al servidor?
 *  Tipo: predice la salida
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  Cuando un formulario se envía por GET, los datos viajan en la URL después
 *  del signo ?, por ejemplo:   buscar.php?nombre=Ana&edad=20
 *  PHP lee ese texto y arma el array $_GET. La función parse_str() hace
 *  exactamente lo mismo, así que aquí la usamos para "simular" peticiones sin
 *  necesidad de formularios.
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. NO lo ejecutes todavía.
 *  2. Para cada caso, escribe qué crees que imprime (fíjate en los TIPOS).
 *  3. Ejecútalo y compara. Lee la explicación de los que fallaste.
 */

function simular(string $consulta): array
{
    parse_str($consulta, $datos);   // lo mismo que hace PHP para armar $_GET
    return $datos;
}

function mostrar(string $caso, $valor): void
{
    echo "<strong>$caso:</strong> " . htmlspecialchars(var_export($valor, true)) . "<br>";
}

// ── CASO 1 ────────────────────────────────────────────────────────────────────
// URL: registro.php?edad=20
$get = simular('edad=20');
mostrar('Caso 1 — $get[\'edad\']', $get['edad']);
// Tu predicción (¿qué tipo es?): ____________

// ── CASO 2 ────────────────────────────────────────────────────────────────────
// Formulario con un campo "apellido" que el usuario dejó en blanco
$get = simular('nombre=Ana&apellido=');
mostrar('Caso 2 — isset($get[\'apellido\'])', isset($get['apellido']));
mostrar('Caso 2 — empty($get[\'apellido\'])', empty($get['apellido']));
// Tu predicción: ____________

// ── CASO 3 ────────────────────────────────────────────────────────────────────
// Formulario con una casilla <input type="checkbox" name="acepto"> SIN marcar
$get = simular('nombre=Ana');
mostrar('Caso 3 — isset($get[\'acepto\'])', isset($get['acepto']));
// Tu predicción: ____________

// ── CASO 4 ────────────────────────────────────────────────────────────────────
// El usuario escribió 0 en "hijos"
$get = simular('hijos=0');
mostrar('Caso 4 — empty($get[\'hijos\'])', empty($get['hijos']));
// Tu predicción: ____________

// ── CASO 5 ────────────────────────────────────────────────────────────────────
// Casillas con el mismo nombre: <input type="checkbox" name="colores" ...> (SIN corchetes)
$get = simular('colores=rojo&colores=azul');
mostrar('Caso 5 — $get[\'colores\']', $get['colores']);
// Tu predicción: ____________

// ── CASO 6 ────────────────────────────────────────────────────────────────────
// Lo mismo, pero con corchetes: name="colores[]"
$get = simular('colores[]=rojo&colores[]=azul');
mostrar('Caso 6 — $get[\'colores\']', $get['colores']);
// Tu predicción: ____________

// ── CASO 7 ────────────────────────────────────────────────────────────────────
// Un campo llamado "nombre completo" (con espacio) y un texto con espacios y signos
$get = simular('nombre+completo=Ana+L%C3%B3pez&nota=4.5');
mostrar('Caso 7 — claves recibidas', array_keys($get));
mostrar('Caso 7 — el nombre', $get['nombre_completo'] ?? 'no existe con ese nombre');
// Tu predicción: ____________

// ── CASO 8 ────────────────────────────────────────────────────────────────────
// Comparar lo que llegó con un número
$get = simular('cantidad=5');
mostrar('Caso 8 — $get[\'cantidad\'] == 5', $get['cantidad'] == 5);
mostrar('Caso 8 — $get[\'cantidad\'] === 5', $get['cantidad'] === 5);
// Tu predicción: ____________


/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESPUESTAS Y EXPLICACIÓN (lee esto DESPUÉS de ejecutar)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CASO 1 -> '20'  (un TEXTO, con comillas)
 *    TODO lo que llega por GET o POST es texto. Aunque el input sea
 *    type="number". Si necesitas un número, conviértelo: (int), filter_var.
 *
 *  CASO 2 -> isset: true | empty: true
 *    El campo SÍ llegó (existe la clave), pero vacío (''). isset pregunta
 *    "¿existe?" y empty pregunta "¿está vacío?". No son lo mismo.
 *
 *  CASO 3 -> false
 *    Una casilla SIN marcar NO se envía. No llega 'acepto' => false: no llega
 *    nada. Por eso con casillas se pregunta isset($_POST['acepto']).
 *
 *  CASO 4 -> true
 *    Para empty(), el texto '0' cuenta como vacío. Si alguien escribe 0 hijos,
 *    if (empty(...)) lo trataría como "no escribió nada". Para números,
 *    compara con === '' o usa filter_var.
 *
 *  CASO 5 -> 'azul'
 *    Sin corchetes, el segundo valor PISA al primero: solo queda el último.
 *
 *  CASO 6 -> array (0 => 'rojo', 1 => 'azul')
 *    Con [] en el name, PHP arma un array con todos los valores.
 *
 *  CASO 7 -> claves: array (0 => 'nombre_completo', 1 => 'nota') | 'Ana López'
 *    PHP cambia los espacios (y los puntos) de los NOMBRES de campo por _.
 *    En los VALORES, el + es un espacio y %C3%B3 es la "ó" codificada.
 *    Moraleja: nombra tus campos sin espacios ni puntos.
 *
 *  CASO 8 -> == 5: true | === 5: false
 *    == compara el valor ('5' vale lo mismo que 5); === compara también el
 *    tipo (texto contra número). Por eso conviene convertir antes de comparar.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, '5' == 5 es False (texto y número nunca son iguales). En PHP,
 *  '5' == 5 es true porque == convierte los tipos antes de comparar. Si vienes
 *  de Python, en PHP piensa en === como tu ==, y usa == solo si sabes por qué.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Escribe la URL que generaría un formulario con nombre "Luis", edad 30 y
 *     las casillas de colores rojo y verde (con corchetes).
 *  2. ¿Cómo validarías que "hijos" llegó, que es un número y que puede ser 0?
 *  3. Si todo llega como texto, ¿por qué funciona $_POST['a'] + $_POST['b']
 *     en 08-superglobales/suma.php? ¿Qué pasa si alguien escribe "abc"?
 */
