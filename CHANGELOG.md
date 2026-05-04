# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/lang/es/).

## [1.0.0] - 2025-10-27

### Agregado

#### Configuración y Estructura
- Archivo `.gitignore` para excluir archivos innecesarios del control de versiones
- Archivo `config.php` centralizado con todas las configuraciones del proyecto
- Archivo `helpers.php` con más de 30 funciones utilitarias
- Archivo `composer.json` para gestión de dependencias
- Archivo `.env.example` como plantilla de configuración
- Documentación completa en `README.md`
- Guía de contribución en `CONTRIBUTING.md`

#### Base de Datos
- Carpeta `database/` con scripts SQL organizados
- Script `setup.sql` para crear todas las bases de datos
- Estructura mejorada para las tablas:
  - `tareas` con campos de estado y prioridad
  - `todos` con timestamps y descripción extendida
  - `usuarios` con sistema de autenticación completo
  - `sesiones` para gestión avanzada de sesiones

#### Sistema de Autenticación
- Archivo `12-sesion/functions.php` con funciones de seguridad mejoradas:
  - Verificación de credenciales con prepared statements
  - Hash de contraseñas con `password_hash()` y `password_verify()`
  - Protección contra CSRF con tokens
  - Validación de sesiones con timeout
  - Registro de usuarios con validaciones
- Archivos de sesión actualizados:
  - `login.php` con protección CSRF y mejor UX
  - `index.php` (dashboard) con información de sesión
  - `logout.php` con cierre seguro de sesión

#### Conexiones de Base de Datos
- Estandarización de todas las conexiones a BD:
  - `15-Proyectos/CRUD/db.php` usando MySQLi con config centralizado
  - `11-miniproyecto/db.php` usando PDO con config centralizado
  - `15-Proyectos/todo-list-poo/config/Database.php` mejorado con manejo de errores

### Mejorado

#### Seguridad
- Implementación de prepared statements en todas las consultas
- Escapado de HTML con `htmlspecialchars()` en todas las salidas
- Validación de entrada de usuarios
- Sesiones seguras con httponly cookies
- Logging de errores sin exponer información sensible
- Protección contra inyección SQL
- Tokens CSRF en formularios

#### Arquitectura
- Separación de configuración en archivo centralizado
- Funciones helper reutilizables en todo el proyecto
- Mejor manejo de errores con try-catch
- Logging estructurado de errores
- Mejores prácticas de código PSR-12

#### Documentación
- README completo con:
  - Estructura del proyecto
  - Guía de instalación
  - Descripción de módulos
  - Ejemplos de uso
  - Mejores prácticas implementadas
- Comentarios PHPDoc en todas las funciones
- Documentación de base de datos

### Funciones Helper Agregadas

**Utilidades Generales:**
- `debug()` - Debug mejorado solo en desarrollo
- `dd()` - Dump and die
- `input()` - Obtener valores de GET/POST de forma segura
- `sanitize()` - Sanitización de entrada
- `escape()` - Escapado de HTML

**URLs y Rutas:**
- `url()` - Generar URLs relativas
- `path()` - Rutas absolutas de archivos
- `currentPage()` - Nombre de página actual
- `isPage()` - Verificar página actual
- `redirectTo()` - Redirección mejorada

**Fechas y Tiempo:**
- `formatDate()` - Formateo de fechas
- `timeAgo()` - Tiempo transcurrido legible

**Validación:**
- `isJson()` - Validar JSON
- `isValidUrl()` - Validar URLs
- `isValidPhone()` - Validar teléfonos
- `validateEmail()` - Validar emails

**Strings:**
- `strLimit()` - Limitar longitud de cadenas
- `slug()` - Generar slugs
- `generatePassword()` - Generar contraseñas aleatorias

**Sistema:**
- `getClientIp()` - Obtener IP del cliente
- `formatBytes()` - Formatear tamaños de archivo
- `toJson()` - Convertir array a JSON

**Flash Messages:**
- `setFlash()` - Crear mensaje flash
- `getFlash()` - Obtener mensaje flash
- `displayFlash()` - Mostrar mensaje flash con HTML

**Utilidades:**
- `paginate()` - Paginación simple

### Configuraciones Agregadas

**Base de Datos:**
- `DB_HOST`, `DB_USER`, `DB_PASS`
- `DB_NAME_TAREAS`, `DB_NAME_TODO`, `DB_NAME_LOGIN`
- `DB_CHARSET`

**Aplicación:**
- `APP_NAME`, `APP_VERSION`
- `BASE_URL`
- Configuración de zona horaria

**Sesiones:**
- `SESSION_LIFETIME`
- `SESSION_NAME`
- Configuración segura de cookies

**Desarrollo:**
- Configuración de errores para desarrollo/producción
- Logging de errores
- Autoload de clases

## [0.1.0] - Versión Original

### Contenido Inicial

**Módulos Educativos:**
- 01-introPHP: Introducción básica
- 02-variables: Variables y tipos
- 03-operadores: Operadores
- 04-condicionales: Estructuras condicionales
- 05-arrays: Arrays
- 06-ciclos: Bucles
- 07-funciones: Funciones
- 08-superglobales: Variables superglobales
- 09-clases: POO básico
- 10-ejemplos: Ejemplos prácticos
- 11-miniproyecto: Mini proyecto con BD
- 12-sesion: Gestión de sesiones
- 13-taller: Ejercicios
- 14-Investigacion: Documentos
- 15-Proyectos: Proyectos completos

**Proyectos:**
- CRUD de tareas con MySQLi
- Todo List estructural con PDO
- Todo List POO con patrón MVC

---

[1.0.0]: https://github.com/usuario/Pestrada_/releases/tag/v1.0.0
[0.1.0]: https://github.com/usuario/Pestrada_/releases/tag/v0.1.0
