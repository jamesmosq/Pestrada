# Ejercicios resueltos para analizar — 12 Sesión

Estos archivos **no son para copiar**: son para leer, ejecutar, romper y explicar.
La sesión es lo que permite que un sitio "recuerde" quién eres entre una petición y otra;
aquí vas a ver cómo funciona por dentro y dónde suelen estar los errores de seguridad.

## Orden recomendado

| # | Archivo | Tipo | Qué vas a entender |
|---|---|---|---|
| 1 | `01-resuelto-intentos-login.php` | Resuelto y comentado | Qué guardar en la sesión, `password_verify`, `session_regenerate_id`, bloqueo por intentos |
| 2 | `02-predice-sesion.php` | Predice la salida | Cuándo existe `$_SESSION`, mensajes flash, `regenerate_id`, la sorpresa de `session_destroy()` |
| 3 | `03-encuentra-error-autenticar.php` | Encuentra el error | Cuatro errores que dejan entrar a quien no debe |
| 4 | `04-recorrido-login.md` | Sigue el recorrido | El login real de esta carpeta, petición por petición |

Usuarios de prueba de los archivos 1 y 3: **admin / admin123** y **ana / clave123**.
El recorrido 4 usa la base de datos `login_db` (importa `database/setup.sql`).

## Cómo trabajar cada tipo

- **Resuelto y comentado:** lee el enunciado y "Cómo se pensó". Pruébalo equivocándote tres veces a propósito
  y sigue la traza.
- **Predice la salida:** **no ejecutes el archivo** hasta haber escrito tu predicción de cada caso.
- **Encuentra el error:** ejecútalo, mira qué pruebas fallan y corrige en el mismo archivo. Al arreglar
  un error pueden aparecer otros que estaban escondidos.
- **Sigue el recorrido:** llena la tabla con F12 → Red abierto y luego compara con la respuesta.

Cada archivo tiene una sección **Trampa de PHP** y una sección **Para analizar** con preguntas tipo sustentación.

## Antes de empezar

Haz primero los resueltos de `08-superglobales/resueltos/`, sobre todo el 04 (Post/Redirect/Get):
aquí se da por sabido.
