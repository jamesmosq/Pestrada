# Configuración de Base de Datos

Esta carpeta contiene `setup.sql`, el script que crea **todas** las bases de datos del curso con sus tablas
y datos de ejemplo.

## Instalación

### Opción 1: phpMyAdmin (recomendada)

1. Abre phpMyAdmin: `http://localhost/phpmyadmin`
2. Ve a la pestaña **Importar**
3. Selecciona el archivo `database/setup.sql`
4. Haz clic en **Continuar**

### Opción 2: consola de MySQL

```bash
mysql -u root -p < database/setup.sql
```

Después, revisa que `config.php` (en la raíz) tenga la contraseña correcta de tu MySQL.
Si no existe, copia `config.example.php` como `config.php`.

## Bases de datos que se crean

| Base de datos | Tablas | La usan | Constante en `config.php` |
|---|---|---|---|
| `tareas_crud` | `tareas` | `15-Proyectos/CRUD` | `DB_NAME_TAREAS` |
| `todo_list` | `todos` | `15-Proyectos/todo-list-estruct` y `todo-list-poo` | `DB_NAME_TODO` |
| `login_db` | `usuarios`, `sesiones` | `11-miniproyecto` y `12-sesion` | `DB_NAME_LOGIN` |
| `sena_mvc` | `estudiantes` | `21-sweetAlert2` | `DB_NAME_MVC` |

Uso desde PHP:

```php
require_once __DIR__ . '/../config.php';   // ajusta la ruta según la carpeta
$db = getDBConnection(DB_NAME_TAREAS);
```

### Otros scripts SQL del curso

| Archivo | Para qué |
|---|---|
| `11-miniproyecto/bd` | Crea solo `login_db` con el usuario admin (la misma estructura que `setup.sql`) |
| `soluciones/21-proyecto-final/migracion.sql` | Agrega `telefono`, la tabla `cursos` y `curso_id` a `sena_mvc` para el proyecto final del módulo 21 |

## Usuarios de prueba (`login_db`)

| Usuario | Email | Contraseña |
|---|---|---|
| admin | admin@pestrada.com | admin123 |
| usuario1 | usuario1@pestrada.com | admin123 |

Las contraseñas están guardadas con `password_hash()`. Para el login se comparan con `password_verify()`.

> No uses la función `PASSWORD()` de MySQL para crear contraseñas: ya no existe en MySQL 8 y su resultado
> no es compatible con `password_verify()` de PHP.

## Cambiar una contraseña

Genera el hash en PHP:

```php
<?php
echo password_hash('tu_nueva_contraseña', PASSWORD_DEFAULT);
```

Y actualiza la base de datos:

```sql
UPDATE usuarios SET password = 'hash_generado' WHERE username = 'admin';
```

## Estructura de las tablas

### `tareas` (tareas_crud)

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | ID autoincremental |
| titulo | VARCHAR(255) | Título de la tarea |
| descripcion | TEXT | Descripción detallada |
| estado | ENUM | pendiente, en_proceso, completada |
| prioridad | ENUM | baja, media, alta |
| fecha_creacion | TIMESTAMP | Fecha de creación |
| fecha_actualizacion | TIMESTAMP | Última actualización |

### `todos` (todo_list)

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | ID autoincremental |
| task | VARCHAR(255) | Texto de la tarea |
| description | TEXT | Descripción extendida |
| is_completed | BOOLEAN | Completada (0/1) |
| created_at | TIMESTAMP | Fecha de creación |
| completed_at | TIMESTAMP | Fecha de completado |

### `usuarios` (login_db)

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | ID autoincremental |
| username | VARCHAR(50) | Nombre de usuario único |
| email | VARCHAR(100) | Email único |
| password | VARCHAR(255) | Contraseña hasheada |
| nombre_completo | VARCHAR(100) | Nombre completo |
| fecha_registro | TIMESTAMP | Fecha de registro |
| ultimo_acceso | TIMESTAMP | Último inicio de sesión |
| activo | BOOLEAN | Estado de la cuenta |

### `estudiantes` (sena_mvc)

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | ID autoincremental |
| nombre | VARCHAR(100) | Nombre completo |
| email | VARCHAR(100) | Email único |
| ficha | VARCHAR(20) | Número de ficha |
| created_at | TIMESTAMP | Fecha de registro |

## Ejecutar el script más de una vez

Las bases de datos y las tablas se crean con `IF NOT EXISTS`, y los usuarios y estudiantes de ejemplo con
`INSERT IGNORE`, así que no fallan ni se duplican. Las **tareas** y los **todos** de ejemplo sí se vuelven a
insertar cada vez: si ejecutas el script dos veces, aparecerán repetidos.

## Respaldo y restauración

```bash
# Respaldar todas las bases de datos del curso
mysqldump -u root -p --databases tareas_crud todo_list login_db sena_mvc > backup.sql

# Restaurar
mysql -u root -p < backup.sql
```

## Solución de problemas

| Error | Solución |
|---|---|
| `Access denied for user 'root'@'localhost'` | La contraseña en `config.php` no coincide con la de tu MySQL. |
| `Unknown database` | Falta importar `setup.sql`. |
| `Table ... doesn't exist` | Falta importar `setup.sql` (o, en el proyecto final del 21, `migracion.sql`). |
