-- Cambios en la base de datos sena_mvc que piden los ejercicios de 21-sweetAlert2.
-- Ejecutar UNA sola vez en phpMyAdmin (pestaña SQL) sobre la base sena_mvc.
-- El proyecto original 21-sweetAlert2 sigue funcionando despues de esto
-- (las columnas nuevas aceptan NULL).

USE sena_mvc;

-- Ejercicio 5: telefono
ALTER TABLE estudiantes
    ADD telefono VARCHAR(10) NULL AFTER ficha;

-- Ejercicio 12: cursos y relacion estudiante -> curso
CREATE TABLE cursos (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cursos (nombre) VALUES
    ('ADSO'),
    ('Programacion de Software'),
    ('Gestion de Redes');

ALTER TABLE estudiantes
    ADD curso_id INT NULL,
    ADD CONSTRAINT fk_estudiantes_curso
        FOREIGN KEY (curso_id) REFERENCES cursos(id)
        ON DELETE SET NULL;   -- si se borra el curso, el estudiante queda "sin curso"
