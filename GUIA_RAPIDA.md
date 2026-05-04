# Guía Rápida de Uso

Esta guía te ayudará a empezar rápidamente con el proyecto Pestrada.

## Instalación Rápida

### 1. Requisitos Previos
- WAMP, XAMPP o MAMP instalado
- PHP 7.4+ y MySQL 5.7+

### 2. Configurar Base de Datos

Opción A - Usando MySQL en línea de comandos:
```bash
mysql -u root -p < database/setup.sql
```

Opción B - Usando phpMyAdmin:
1. Abre phpMyAdmin (http://localhost/phpmyadmin)
2. Importa el archivo `database/setup.sql`
3. Verifica que se crearon 3 bases de datos:
   - `tareas_crud`
   - `todo_list`
   - `login_db`

### 3. Configurar el Proyecto

1. Copia el archivo de configuración:
   ```bash
   cp .env.example .env
   ```

2. Edita `config.php` si necesitas cambiar credenciales de BD:
   ```php
   define('DB_USER', 'root');
   define('DB_PASS', 'tu_password');
   ```

### 4. Probar la Instalación

Abre en tu navegador:
- http://localhost/Pestrada_/12-sesion/login.php

Credenciales de prueba:
- **Usuario:** admin
- **Contraseña:** admin123

## Estructura de Aprendizaje

### Nivel 1: Fundamentos (Semanas 1-2)
1. `01-introPHP/` - Sintaxis básica
2. `02-variables/` - Tipos de datos
3. `03-operadores/` - Operadores
4. `04-condicionales/` - if, else, switch

**Práctica:** Crea una calculadora simple

### Nivel 2: Estructuras de Datos (Semanas 3-4)
5. `05-arrays/` - Arrays
6. `06-ciclos/` - Loops
7. `07-funciones/` - Funciones
8. `08-superglobales/` - GET, POST

**Práctica:** Sistema de registro de estudiantes

### Nivel 3: POO (Semanas 5-6)
9. `09-clases/` - Clases y objetos
10. `10-ejemplos/` - Ejemplos prácticos

**Práctica:** Biblioteca de libros orientada a objetos

### Nivel 4: Aplicaciones Web (Semanas 7-8)
11. `11-miniproyecto/` - Login con BD
12. `12-sesion/` - Sesiones

**Práctica:** Sistema de autenticación completo

### Nivel 5: Proyectos Completos (Semanas 9-12)
13. `15-Proyectos/CRUD/` - CRUD básico
14. `15-Proyectos/todo-list-estruct/` - Arquitectura estructural
15. `15-Proyectos/todo-list-poo/` - Patrón MVC

**Práctica:** Desarrolla tu propio proyecto

## Proyectos Incluidos

### 1. CRUD de Tareas
**Ubicación:** `15-Proyectos/CRUD/`

**Características:**
- Listar tareas
- Crear nueva tarea
- Editar tarea
- Eliminar tarea
- Bootstrap para UI

**Cómo usar:**
```
http://localhost/Pestrada_/15-Proyectos/CRUD/
```

**Tecnologías:**
- MySQLi con prepared statements
- Bootstrap 5
- Separación de vistas con includes

### 2. Todo List Estructural
**Ubicación:** `15-Proyectos/todo-list-estruct/`

**Características:**
- Agregar tareas
- Marcar como completadas
- Eliminar tareas
- Arquitectura funcional

**Cómo usar:**
```
http://localhost/Pestrada_/15-Proyectos/todo-list-estruct/
```

**Tecnologías:**
- PDO con prepared statements
- Funciones reutilizables
- Separación de vistas

### 3. Todo List POO (Recomendado)
**Ubicación:** `15-Proyectos/todo-list-poo/`

**Características:**
- Patrón MVC completo
- Clases bien estructuradas
- Mejores prácticas de POO

**Cómo usar:**
```
http://localhost/Pestrada_/15-Proyectos/todo-list-poo/
```

**Arquitectura:**
```
config/
  Database.php       # Conexión a BD
models/
  TodoItem.php       # Modelo de datos
controllers/
  TodoController.php # Lógica de negocio
views/
  *.php             # Templates
```

**Tecnologías:**
- PDO con excepciones
- Patrón MVC
- Inyección de dependencias

## Funciones Helper Útiles

El proyecto incluye `helpers.php` con funciones útiles:

### Debug y Testing
```php
// Mostrar variable formateada
debug($miVariable);

// Mostrar y terminar ejecución
dd($miArray);
```

### Entrada Segura
```php
// Obtener valor de formulario
$nombre = input('nombre', 'Anónimo');

// Solo POST
$email = input('email', '', 'POST');

// Sanitizar
$texto = sanitize($entrada);
```

### URLs y Redirección
```php
// Generar URL
$url = url('admin/usuarios');

// Redirigir
redirectTo('login.php');

// Redirigir con delay
redirectTo('dashboard.php', 3);
```

### Fechas
```php
// Formatear fecha
echo formatDate($fecha, 'd/m/Y');

// Tiempo transcurrido
echo timeAgo($timestamp); // "hace 5 minutos"
```

### Validación
```php
// Validar email
if (validateEmail($email)) { }

// Validar teléfono
if (isValidPhone($phone)) { }

// Validar URL
if (isValidUrl($url)) { }
```

### Flash Messages
```php
// Crear mensaje
setFlash('success', '¡Registro exitoso!', 'success');

// Mostrar mensaje
displayFlash('success');
```

## Mejores Prácticas Implementadas

### Seguridad
```php
// SIEMPRE escapar salida HTML
echo escape($username);

// SIEMPRE usar prepared statements
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $id);

// SIEMPRE hashear contraseñas
$hash = password_hash($password, PASSWORD_DEFAULT);
if (password_verify($password, $hash)) { }

// SIEMPRE validar tokens CSRF
if (verifyCsrfToken($_POST['csrf_token'])) { }
```

### Conexión a Base de Datos
```php
// Usar función helper
require_once 'config.php';
$db = getDBConnection(DB_NAME_TAREAS);

// O crear instancia Database (POO)
$database = new Database();
$db = $database->getConnection();
```

### Sesiones
```php
// Verificar login
if (!isLoggedIn()) {
    redirectTo('login.php');
}

// O usar helper
requireAuth('login.php');

// Validar sesión (timeout, seguridad)
if (!validateSession()) {
    logoutUser();
}
```

## Solución de Problemas Comunes

### Error: "Class 'PDO' not found"
Activa la extensión PDO en `php.ini`:
```ini
extension=pdo_mysql
```

### Error: "Access denied for user 'root'"
Verifica las credenciales en `config.php`:
```php
define('DB_PASS', 'tu_password_real');
```

### Error: "Unknown database"
Ejecuta el script de base de datos:
```bash
mysql -u root -p < database/setup.sql
```

### Error: "Headers already sent"
Asegúrate de no tener espacios antes de `<?php` o después de `?>`

### Sesión no funciona
Verifica que `session_start()` sea lo primero en el archivo

## Recursos Adicionales

### Documentación PHP
- [PHP.net Manual](https://www.php.net/manual/es/)
- [PHP The Right Way](https://phptherightway.com/)
- [PSR-12 Coding Style](https://www.php-fig.org/psr/psr-12/)

### Tutoriales Recomendados
- W3Schools PHP Tutorial
- PHP MySQL Tutorial (Traversy Media)
- Laracasts PHP Path

### Herramientas Útiles
- phpMyAdmin para gestión de BD
- Visual Studio Code con extensión PHP Intelephense
- Postman para testing de APIs

## Siguientes Pasos

1. Completa todos los módulos en orden
2. Practica modificando los ejemplos existentes
3. Crea tus propios proyectos
4. Aprende sobre:
   - APIs RESTful con PHP
   - Frameworks (Laravel, Symfony)
   - Testing con PHPUnit
   - Composer para gestión de paquetes

## Contribuir

¿Encontraste un error o quieres agregar algo?
Lee `CONTRIBUTING.md` para saber cómo contribuir.

## Soporte

Si tienes preguntas:
1. Revisa esta guía y el README.md
2. Busca en los ejemplos existentes
3. Abre un issue en el repositorio

---

¡Feliz aprendizaje! 🚀
