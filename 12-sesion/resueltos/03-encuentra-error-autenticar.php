<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 03 — El login que deja entrar a quien no debe
 *  Tipo: encuentra el error
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  SITUACIÓN
 *  La función autenticar() recibe la lista de usuarios (como llegaría de la
 *  base de datos), el usuario y la clave escritos en el formulario. Debe:
 *    - devolver los datos del usuario si el usuario existe y la clave es correcta
 *    - devolver false en cualquier otro caso
 *    - NUNCA devolver el hash de la contraseña (esos datos van a la sesión,
 *      y el hash no tiene nada que hacer ahí)
 *
 *  Usuarios de prueba:  admin / admin123    ana / clave123
 *
 *  Hay CUATRO errores. Ejecuta, corrige aquí mismo hasta que todo diga OK y
 *  compara al final. Al arreglar uno pueden aparecer otros que estaban escondidos.
 *
 *  Pistas (solo si llevas 15 minutos sin avanzar):
 *    - Pista 1: dentro del if, ¿se está COMPARANDO o ASIGNANDO?
 *    - Pista 2: en la base de datos está el hash, no la clave. ¿Cómo se compara?
 *    - Pista 3: si el primer usuario no coincide, ¿se revisan los demás?
 *    - Pista 4: ¿qué datos se devuelven exactamente?
 */

function autenticar(array $usuarios, string $usuario, string $clave): array|false
{
    foreach ($usuarios as $u) {
        if ($u['usuario'] = $usuario) {
            if ($u['password'] == $clave) {
                return $u;
            }
        } else {
            return false;
        }
    }
    return false;
}

// ── Datos y pruebas automáticas: NO los modifiques, corrige la función ────────
$usuarios = [
    ['id' => 1, 'usuario' => 'admin', 'nombre' => 'Administrador',
     'password' => '$2y$10$TNSJ0cdliQafrmAkDpV4/u02oya.0d7KXC6EJCsgNqLytzxxjEPke'],
    ['id' => 2, 'usuario' => 'ana', 'nombre' => 'Ana Gómez',
     'password' => '$2y$10$e1fySYgETXk.k/PT6bpSs.IPtEy/qqrg4z4PgYHW8vsO.hC2NGtqW'],
];

$pruebas = [
    ['admin con su clave',      'admin', 'admin123', ['id' => 1, 'usuario' => 'admin', 'nombre' => 'Administrador']],
    ['ana con su clave',        'ana',   'clave123', ['id' => 2, 'usuario' => 'ana', 'nombre' => 'Ana Gómez']],
    ['admin con clave de ana',  'admin', 'clave123', false],
    ['usuario que no existe',   'pepe',  'admin123', false],
    ['clave vacía',             'ana',   '',         false],
];

foreach ($pruebas as [$descripcion, $usuario, $clave, $esperado]) {
    $obtenido = autenticar($usuarios, $usuario, $clave);
    $ok = $obtenido === $esperado;

    echo ($ok ? "OK    " : "FALLA ") . " $descripcion<br>";
    if (!$ok) {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;esperado: " . htmlspecialchars(json_encode($esperado, JSON_UNESCAPED_UNICODE))
           . "<br>&nbsp;&nbsp;&nbsp;&nbsp;obtenido: " . htmlspecialchars(json_encode($obtenido, JSON_UNESCAPED_UNICODE)) . "<br>";
    }
}

/*
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python, if x = 5: es un error de sintaxis: el lenguaje no te deja
 *  asignar dentro de un if por accidente. En PHP sí se puede: if ($a = $b)
 *  ASIGNA $b a $a y luego pregunta si el resultado es "verdadero". No hay
 *  ningún aviso. Por eso en PHP se recomienda comparar siempre con ===.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. Con el error 1 sin corregir, ¿con qué usuario se compara SIEMPRE la clave?
 *     ¿Qué podría hacer un atacante con eso si el error 2 no existiera?
 *  2. ¿Por qué no basta con guardar en la base de datos md5($clave)?
 *     (investiga: "md5 rainbow tables")
 *  3. Compara tu versión corregida con verifyCredentials() en
 *     12-sesion/functions.php. ¿En qué se parecen? ¿Por qué esa versión no
 *     necesita un foreach?
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
 *  function autenticar(array $usuarios, string $usuario, string $clave): array|false
 *  {
 *      foreach ($usuarios as $u) {
 *          if ($u['usuario'] === $usuario) {               // ERROR 1: era "=" (asignación), no comparación
 *              if (password_verify($clave, $u['password'])) {
 *                                                         // ERROR 2: se comparaba la clave con el HASH
 *                  unset($u['password']);                 // ERROR 4: no devolver el hash
 *                  return $u;
 *              }
 *              return false;                              // el usuario existía pero la clave no
 *          }
 *          // ERROR 3: el "else { return false; }" cortaba el ciclo en el primer
 *          // usuario que no coincidía: ana nunca podía entrar. Se quita.
 *      }
 *      return false;                                      // recorrió todos y no lo encontró
 *  }
 *
 *  Respuesta a la pregunta 1: la asignación convierte el usuario de la lista
 *  en el que se escribió, así que la condición siempre es verdadera y se compara
 *  con la clave del PRIMER usuario (admin). Sin el error 2, entrar como "pepe"
 *  con la clave del admin funcionaría y devolvería los datos del admin con el
 *  nombre "pepe".
 */
