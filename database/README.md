# Configuración de Base de Datos

Esta carpeta contiene los scripts SQL necesarios para configurar las bases de datos del proyecto.

## Archivos

- `setup.sql` - Script principal que crea todas las bases de datos y tablas
- `tareas_crud.sql` - Solo la base de datos del CRUD de tareas
- `todo_list.sql` - Solo la base de datos de la lista de tareas
- `login_db.sql` - Solo la base de datos del sistema de login

## Instalación Rápida

### Opción 1: Importar todo de una vez

```bash
mysql -u root -p < setup.sql
```

### Opción 2: Importar por separado

```bash
mysql -u root -p < tareas_crud.sql
mysql -u root -p < todo_list.sql
mysql -u root -p < login_db.sql
```

### Opción 3: Usando phpMyAdmin

1. Abre phpMyAdmin en tu navegador
2. Ve a la pestaña "Importar"
3. Selecciona el archivo `setup.sql`
4. Haz clic en "Continuar"

## Bases de Datos Creadas

### 1. tareas_crud
Base de datos para el proyecto CRUD de tareas.

**Tablas:**
- `tareas` - Almacena las tareas con título, descripción, estado y prioridad

**Uso:**
```php
require_once 'config.php';
$db = getDBConnection(DB_NAME_TAREAS);
```

### 2. todo_list
Base de datos para el proyecto Todo List (POO y Estructural).

**Tablas:**
- `todos` - Almacena las tareas con descripción y estado de completado

**Uso:**
```php
require_once 'config.php';
$db = getDBConnection(DB_NAME_TODO);
```

### 3. login_db
Base de datos para el sistema de autenticación.

**Tablas:**
- `usuarios` - Información de usuarios registrados
- `sesiones` - Gestión avanzada de sesiones (opcional)

**Uso:**
```php
require_once 'config.php';
$db = getDBConnection(DB_NAME_LOGIN);
```

## Usuarios de Prueba

### Usuario Administrador
- **Username:** admin
- **Email:** admin@pestrada.com
- **Password:** admin123

### Usuario Demo
- **Username:** usuario1
- **Email:** usuario1@pestrada.com
- **Password:** admin123

## Cambiar Contraseñas

Para crear un hash de contraseña en PHP:

```php
<?php
$password = 'tu_nueva_contraseña';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
?>
```

Luego actualiza la base de datos:

```sql
UPDATE usuarios SET password = 'hash_generado' WHERE username = 'admin';
```

## Estructura de Tablas

### Tabla: tareas

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | ID autoincremental |
| titulo | VARCHAR(255) | Título de la tarea |
| descripcion | TEXT | Descripción detallada |
| estado | ENUM | pendiente, en_proceso, completada |
| prioridad | ENUM | baja, media, alta |
| fecha_creacion | TIMESTAMP | Fecha de creación |
| fecha_actualizacion | TIMESTAMP | Última actualización |

### Tabla: todos

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | ID autoincremental |
| task | VARCHAR(255) | Descripción de la tarea |
| description | TEXT | Descripción extendida |
| is_completed | BOOLEAN | Estado completado (0/1) |
| created_at | TIMESTAMP | Fecha de creación |
| completed_at | TIMESTAMP | Fecha de completado |

### Tabla: usuarios

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

## Respaldo y Restauración

### Crear respaldo

```bash
# Respaldar todas las bases de datos
mysqldump -u root -p --databases tareas_crud todo_list login_db > backup.sql

# Respaldar una sola base de datos
mysqldump -u root -p tareas_crud > backup_tareas.sql
```

### Restaurar desde respaldo

```bash
mysql -u root -p < backup.sql
```

## Solución de Problemas

### Error: "Access denied for user 'root'@'localhost'"
Verifica tu contraseña de MySQL en el archivo `config.php`

### Error: "Unknown database"
Asegúrate de haber ejecutado el script `setup.sql` primero

### Error: "Table already exists"
El script es idempotente, usa `IF NOT EXISTS` para evitar errores

## Seguridad

- Nunca compartas las contraseñas en el repositorio
- Usa variables de entorno para credenciales en producción
- Cambia las contraseñas por defecto
- Usa HTTPS en producción
- Mantén PHP y MySQL actualizados
