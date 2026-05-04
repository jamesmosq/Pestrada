-- ============================================
-- Script de Configuración de Bases de Datos
-- Proyecto: Pestrada - Curso PHP
-- ============================================

-- Crear todas las bases de datos necesarias
CREATE DATABASE IF NOT EXISTS tareas_crud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS todo_list CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS login_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ============================================
-- BASE DE DATOS: tareas_crud
-- Para el proyecto CRUD de Tareas
-- ============================================

USE tareas_crud;

-- Tabla de tareas
CREATE TABLE IF NOT EXISTS tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    estado ENUM('pendiente', 'en_proceso', 'completada') DEFAULT 'pendiente',
    prioridad ENUM('baja', 'media', 'alta') DEFAULT 'media',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_estado (estado),
    INDEX idx_prioridad (prioridad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo para tareas_crud
INSERT INTO tareas (titulo, descripcion, estado, prioridad) VALUES
('Aprender PHP básico', 'Completar módulos 1-8 del curso', 'completada', 'alta'),
('Estudiar POO en PHP', 'Entender clases, objetos y herencia', 'en_proceso', 'alta'),
('Crear primer CRUD', 'Desarrollar aplicación CRUD completa', 'en_proceso', 'media'),
('Practicar MySQL', 'Realizar ejercicios de consultas SQL', 'pendiente', 'media'),
('Implementar sesiones', 'Crear sistema de login con sesiones', 'pendiente', 'alta');

-- ============================================
-- BASE DE DATOS: todo_list
-- Para el proyecto Todo List
-- ============================================

USE todo_list;

-- Tabla de todos
CREATE TABLE IF NOT EXISTS todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(255) NOT NULL,
    description TEXT,
    is_completed BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    INDEX idx_completed (is_completed)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo para todo_list
INSERT INTO todos (task, description, is_completed) VALUES
('Aprender patrón MVC', 'Estudiar arquitectura Modelo-Vista-Controlador', 0),
('Configurar entorno de desarrollo', 'Instalar WAMP/XAMPP y configurar PHP', 1),
('Crear primera clase en PHP', 'Implementar clase con propiedades y métodos', 1),
('Conectar PHP con MySQL', 'Establecer conexión usando PDO', 0);

-- ============================================
-- BASE DE DATOS: login_db
-- Para el sistema de autenticación
-- ============================================

USE login_db;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    activo BOOLEAN DEFAULT 1,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de sesiones (opcional, para gestión avanzada)
CREATE TABLE IF NOT EXISTS sesiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion TIMESTAMP,
    activa BOOLEAN DEFAULT 1,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_session_id (session_id),
    INDEX idx_usuario_id (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuario de ejemplo (password: 'admin123' hasheado con password_hash)
-- IMPORTANTE: Cambiar en producción
INSERT INTO usuarios (username, email, password, nombre_completo) VALUES
('admin', 'admin@pestrada.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador'),
('usuario1', 'usuario1@pestrada.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuario Demo');

-- ============================================
-- Verificación de las tablas creadas
-- ============================================

-- Ver todas las bases de datos creadas
SHOW DATABASES LIKE '%crud%' OR LIKE '%todo%' OR LIKE '%login%';

-- ============================================
-- Notas importantes:
-- ============================================
-- 1. El password por defecto para los usuarios de ejemplo es: admin123
-- 2. En producción, asegúrate de cambiar las contraseñas
-- 3. Usa siempre password_hash() para hashear contraseñas en PHP
-- 4. Este script es idempotente (puede ejecutarse múltiples veces)
-- ============================================
