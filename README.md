# Pestrada - Curso PHP

Repositorio educativo para aprender PHP desde cero hasta nivel intermedio-avanzado, incluyendo programación orientada a objetos y desarrollo de aplicaciones web completas.

## Estructura del Proyecto

```
Pestrada_/
├── 01-introPHP/           # Introducción básica a PHP
├── 02-variables/          # Variables y tipos de datos
├── 03-operadores/         # Operadores aritméticos y lógicos
├── 04-condicionales/      # Estructuras condicionales (if, else, switch)
├── 05-arrays/             # Arrays indexados y asociativos
├── 06-ciclos/             # Bucles (for, while, foreach)
├── 07-funciones/          # Definición y uso de funciones
├── 08-superglobales/      # Variables superglobales ($_GET, $_POST, etc.)
├── 09-clases/             # Programación Orientada a Objetos
├── 10-ejemplos/           # Ejemplos prácticos
├── 11-miniproyecto/       # Miniproyecto con base de datos
├── 12-sesion/             # Gestión de sesiones PHP
├── 13-taller/             # Ejercicios y talleres
├── 14-Investigacion/      # Documentos de investigación
├── 15-Proyectos/          # Proyectos completos finales
├── config.php             # Configuración centralizada
└── README.md              # Este archivo
```

## Requisitos del Sistema

- PHP 7.4 o superior (recomendado PHP 8.x)
- MySQL 5.7 o superior / MariaDB 10.x
- Servidor web (Apache/Nginx) o WAMP/XAMPP/MAMP
- Extensiones PHP necesarias:
  - PDO
  - MySQLi
  - Session

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/Pestrada_.git
cd Pestrada_
```

### 2. Configurar la base de datos

Importa los archivos SQL ubicados en la carpeta `database/`:

```bash
mysql -u root -p < database/setup.sql
```

O importa manualmente cada base de datos según el proyecto:
- `tareas_crud.sql` - Para el CRUD de tareas
- `todo_list.sql` - Para la lista de tareas
- `login_db.sql` - Para el sistema de login

### 3. Configurar conexión a base de datos

Edita el archivo `config.php` y ajusta las credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'tu_password');
```

### 4. Iniciar el servidor

Si usas el servidor PHP integrado:

```bash
php -S localhost:8000
```

O configura tu servidor WAMP/XAMPP apuntando a esta carpeta.

## Contenido del Curso

### Nivel Básico (01-08)

**Módulo 1-2: Introducción**
- Sintaxis básica de PHP
- Variables y tipos de datos
- Echo y print

**Módulo 3-4: Operadores y Condicionales**
- Operadores aritméticos, lógicos y de comparación
- Estructuras if/else/elseif
- Switch case

**Módulo 5-6: Arrays y Ciclos**
- Arrays indexados y asociativos
- Bucles for, while, foreach
- Break y continue

**Módulo 7-8: Funciones y Formularios**
- Definición de funciones
- Parámetros y valores de retorno
- Manejo de formularios GET/POST

### Nivel Intermedio (09-13)

**Módulo 9: POO - Clases y Objetos**
- Definición de clases
- Propiedades y métodos
- Encapsulación (public, private, protected)
- Constructores y destructores
- Herencia

**Módulo 10-13: Aplicaciones Prácticas**
- Calculadora web
- Sistemas de login
- Gestión de sesiones
- CRUD básico con bases de datos

### Nivel Avanzado (15-Proyectos)

#### Proyecto 1: CRUD de Tareas
Aplicación completa para gestionar tareas con:
- Listado de tareas
- Crear, editar y eliminar tareas
- Bootstrap para interfaz
- MySQLi con prepared statements

**Archivos principales:**
- `15-Proyectos/CRUD/index.php`
- `15-Proyectos/CRUD/db.php`

#### Proyecto 2: Todo List Estructural
Lista de tareas con arquitectura estructural:
- Separación de funciones y vistas
- PDO para base de datos
- Funciones reutilizables

**Archivos principales:**
- `15-Proyectos/todo-list-estruct/index.php`
- `15-Proyectos/todo-list-estruct/includes/`

#### Proyecto 3: Todo List POO (Patrón MVC)
Lista de tareas con arquitectura orientada a objetos:
- Patrón MVC (Model-View-Controller)
- Clase Database para conexión
- Modelo TodoItem con operaciones CRUD
- Controller para lógica de negocio

**Archivos principales:**
- `15-Proyectos/todo-list-poo/config/Database.php`
- `15-Proyectos/todo-list-poo/models/TodoItem.php`
- `15-Proyectos/todo-list-poo/controllers/TodoController.php`
- `15-Proyectos/todo-list-poo/views/`

## Mejores Prácticas Implementadas

### Seguridad
- Prepared statements para prevenir inyección SQL
- Escapado de HTML con `htmlspecialchars()`
- Validación de entrada de usuarios
- Sesiones seguras con httponly cookies
- Manejo de errores con try-catch

### Arquitectura
- Separación de responsabilidades
- Patrón MVC en proyectos avanzados
- Reutilización de código
- Configuración centralizada

### Base de Datos
- PDO con prepared statements
- MySQLi con bind_param
- Manejo de excepciones
- Conexiones seguras

## Uso de los Proyectos

### CRUD de Tareas

```bash
# Navega a:
http://localhost/Pestrada_/15-Proyectos/CRUD/
```

**Funcionalidades:**
- Ver lista de tareas
- Agregar nueva tarea
- Editar tarea existente
- Eliminar tarea

### Todo List POO

```bash
# Navega a:
http://localhost/Pestrada_/15-Proyectos/todo-list-poo/
```

**Funcionalidades:**
- Agregar tareas
- Marcar como completadas
- Eliminar tareas
- Arquitectura MVC completa

## Estructura de Base de Datos

### tareas_crud

```sql
CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### todo_list

```sql
CREATE TABLE todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(255) NOT NULL,
    is_completed BOOLEAN DEFAULT 0
);
```

## Recursos de Aprendizaje

- [PHP.net - Documentación oficial](https://www.php.net/manual/es/)
- [W3Schools PHP Tutorial](https://www.w3schools.com/php/)
- [PHP The Right Way](https://phptherightway.com/)

## Contribuir

Este es un proyecto educativo. Si encuentras errores o quieres mejorar algo:

1. Fork el proyecto
2. Crea una rama (`git checkout -b feature/mejora`)
3. Commit tus cambios (`git commit -m 'Agregar mejora'`)
4. Push a la rama (`git push origin feature/mejora`)
5. Abre un Pull Request

## Licencia

Este proyecto es de código abierto y está disponible para propósitos educativos.

## Contacto

Para preguntas o sugerencias, abre un issue en el repositorio.

---

**Última actualización:** 2025-10-27
**Versión:** 1.0.0
