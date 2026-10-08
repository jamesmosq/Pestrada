# Resuelto 04 — El recorrido completo de un login

**Tipo:** sigue el recorrido

En este ejercicio no hay código nuevo: vas a seguir el recorrido del login **real** de esta carpeta
(`login.php`, `index.php`, `logout.php` y `functions.php`) de principio a fin, como haría un desarrollador
que llega a un proyecto que no escribió. Eso es lo que harás en Laravel todo el tiempo.

## Preparación

1. Importa `database/setup.sql` si no lo has hecho (crea `login_db` con el usuario **admin / admin123**).
2. Abre `12-sesion/login.php` en el navegador con **F12 → Red** abierto y "Conservar registro" marcado.
3. Ten abiertos en el editor los cuatro archivos.

## Parte 1: llena la tabla tú

Haz el recorrido: entra a `login.php`, inicia sesión con admin / admin123, mira el panel y cierra sesión.
Para cada petición que veas en la pestaña Red, completa una fila:

| # | Archivo pedido | Método | Funciones de `functions.php` que se ejecutan | Qué cambia en `$_SESSION` | Respuesta (200 / 302 → a dónde) |
|---|---|---|---|---|---|
| 1 | | | | | |
| 2 | | | | | |
| 3 | | | | | |
| 4 | | | | | |
| 5 | | | | | |

Cuando la termines, compara con la respuesta de la Parte 3.

## Parte 2: preguntas de sustentación

1. `login.php`, `index.php` y `logout.php` empiezan con `session_start()`. ¿Qué pasaría si `index.php` no la tuviera?
2. En la petición del formulario, ¿qué se revisa **primero**: el token CSRF o la contraseña? ¿Por qué ese orden?
3. `verifyCredentials()` busca con `WHERE username = :username AND activo = 1`. ¿Qué pasa si el usuario
   existe pero `activo` vale 0? ¿Qué ve el usuario en pantalla?
4. `loginUser()` llama a `session_regenerate_id(true)` **antes** de guardar los datos. ¿Qué ataque evita?
5. Si un usuario que **ya** inició sesión abre `login.php`, ¿qué pasa? ¿En qué línea se decide?
6. Abre `index.php` en una ventana de incógnito (sin iniciar sesión). ¿Qué función te saca y hacia dónde?
7. `logout.php` redirige a `login.php?mensaje=sesion_cerrada`. Busca en `login.php` dónde se muestra ese
   mensaje. ¿Lo encuentras? ¿Qué cambiarías?
8. Si cambias `SESSION_LIFETIME` en `config.php` a 30 segundos, inicias sesión y esperas un minuto antes de
   recargar el panel, ¿qué pasa? ¿Qué función lo decide?

## Parte 3: respuesta de la tabla

Léela solo cuando hayas llenado la tuya.

| # | Archivo pedido | Método | Funciones que se ejecutan | Qué cambia en `$_SESSION` | Respuesta |
|---|---|---|---|---|---|
| 1 | `login.php` | GET | `isLoggedIn()` (falso) → `displayLoginForm()` | Se crea `csrf_token` (si no existía) | 200, muestra el formulario con el token oculto |
| 2 | `login.php` | POST | `verifyCsrfToken()` → `sanitizeInput()` → `verifyCredentials()` (consulta la BD, `password_verify`, `updateLastAccess()`) → `loginUser()` | **Cambia el id de sesión** y se guardan `user_id`, `username`, `nombre_completo`, `email`, `login_time`, `ip_address`, `user_agent` | 302 → `index.php` |
| 3 | `index.php` | GET | `validateSession()` (revisa `isLoggedIn()` y el tiempo de sesión) → `requireAuth()` | Nada, solo se lee | 200, muestra el panel |
| 4 | `logout.php` | GET | `logoutUser()`: `$_SESSION = []`, borra la cookie, `session_destroy()` | Todo se borra | 302 → `login.php?mensaje=sesion_cerrada` |
| 5 | `login.php?mensaje=sesion_cerrada` | GET | `isLoggedIn()` (falso) → `displayLoginForm()` | Sesión nueva con un `csrf_token` nuevo | 200, formulario vacío |

### Respuestas de la Parte 2

1. En `index.php` `$_SESSION` no tendría datos: `validateSession()` diría que no hay sesión y redirigiría
   al login aunque el usuario sí hubiera entrado.
2. Primero el token CSRF. Si la petición no viene de nuestro formulario, ni siquiera se consulta la base de
   datos: es más seguro y más barato.
3. La consulta no encuentra nada, así que `verifyCredentials()` devuelve `false` y se ve el mismo mensaje
   que con una clave equivocada. Es intencional: no se le dice a nadie qué cuentas existen o están desactivadas.
4. La **fijación de sesión**: si un atacante logró que la víctima usara un id de sesión que él conoce, ese id
   deja de servir en el momento en que la víctima inicia sesión.
5. Se redirige a `index.php`. Lo decide el `if (isLoggedIn())` del inicio de `login.php`.
6. `validateSession()` devuelve `false` y `index.php` redirige a `login.php`.
7. No se muestra en ningún lado: `login.php` nunca lee `$_GET['mensaje']`. Una mejora sería mostrar
   "Sesión cerrada correctamente" cuando llegue ese parámetro (o, mejor, usar un mensaje flash en la sesión).
8. Al recargar, `validateSession()` ve que pasó más tiempo que `SESSION_LIFETIME`, llama a `logoutUser()`
   y te envía al login.

## Trampa de PHP (viniendo de Python)

En Django o Flask, el framework revisa la sesión **antes** de llegar a tu código (con decoradores como
`@login_required`). En PHP puro cada archivo protegido tiene que revisarla por su cuenta: si un día creas
`reportes.php` y olvidas llamar a `requireAuth()`, esa página queda abierta para cualquiera.
Laravel resuelve esto con *middleware*: proteges un grupo de rutas una sola vez.

## Para ir más allá

- Dibuja el recorrido como un diagrama de flechas: navegador → archivo → funciones → base de datos → respuesta.
- Agrega la página `perfil.php`, protegida igual que `index.php`, y comprueba que sin sesión te redirige.
