CREATE DATABASE IF NOT EXISTS bd_restauranteIndividual;

USE bd_restauranteIndividual;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS tbl_usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre_user VARCHAR(100),
    contrasena VARCHAR(100),
    rol ENUM('camarero', 'gerente', 'mantenimiento', 'administrador') DEFAULT 'camarero'
);

-- Tabla de salas
CREATE TABLE IF NOT EXISTS tbl_salas (
    id_sala INT PRIMARY KEY AUTO_INCREMENT,
    nombre_sala VARCHAR(100),
    tipo_sala VARCHAR(50),
    capacidad INT
);

-- Tabla de mesas
CREATE TABLE IF NOT EXISTS tbl_mesas (
    id_mesa INT PRIMARY KEY AUTO_INCREMENT,
    numero_mesa INT,
    id_sala INT,
    numero_sillas INT,
    estado ENUM('libre', 'ocupada') DEFAULT 'libre',
    FOREIGN KEY (id_sala) REFERENCES tbl_salas(id_sala)
);

-- Tabla de reservas
CREATE TABLE IF NOT EXISTS tbl_reservas (
    id_reserva INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_mesa INT,
    fecha DATE,
    hora_inicio TIME,
    hora_fin TIME,
    FOREIGN KEY (id_usuario) REFERENCES tbl_usuarios(id_usuario),
    FOREIGN KEY (id_mesa) REFERENCES tbl_mesas(id_mesa)
);

-- Insertar usuarios
INSERT INTO tbl_usuarios (nombre_user, contrasena, rol) VALUES
    ('Jorge', '$2y$10$wORRwXyRsJRc9ua8okkNuO6m/GbqBuZouNb4LZbwFPDG6HwNUhOVa', 'camarero'),
    ('Olga', '$2y$10$wORRwXyRsJRc9ua8okkNuO6m/GbqBuZouNb4LZbwFPDG6HwNUhOVa', 'gerente'),
    ('Miguel', '$2y$10$wORRwXyRsJRc9ua8okkNuO6m/GbqBuZouNb4LZbwFPDG6HwNUhOVa', 'administrador');

-- Insertar salas
INSERT INTO tbl_salas (nombre_sala, tipo_sala, capacidad) VALUES
    ('Terraza 1', 'Terraza', 20),
    ('Terraza 2', 'Terraza', 20),
    ('Terraza 3', 'Terraza', 20),
    ('Comedor 1', 'Comedor', 30),
    ('Comedor 2', 'Comedor', 25),
    ('Sala Privada 1', 'Privada', 10),
    ('Sala Privada 2', 'Privada', 8),
    ('Sala Privada 3', 'Privada', 12),
    ('Sala Privada 4', 'Privada', 15);

-- Insertar mesas
INSERT INTO tbl_mesas (numero_mesa, id_sala, numero_sillas, estado) VALUES
    (101, 1, 4, 'libre'),
    (102, 1, 6, 'libre'),
    (103, 1, 4, 'libre'),
    (104, 1, 9, 'libre'),
    (201, 2, 4, 'libre'),
    (202, 2, 6, 'libre'),
    (203, 2, 12, 'libre'),
    (204, 2, 4, 'libre'),
    (301, 3, 4, 'libre'),
    (302, 3, 4, 'libre'),
    (303, 3, 7, 'libre'),
    (304, 3, 2, 'libre'),
    (401, 4, 2, 'libre'),
    (402, 4, 9, 'libre'),
    (403, 4, 2, 'libre'),
    (404, 4, 7, 'libre'),
    (405, 4, 5, 'libre'),
    (406, 4, 6, 'libre'),
    (501, 5, 12, 'libre'),
    (502, 5, 9, 'libre'),
    (503, 5, 16, 'libre'),
    (504, 5, 2, 'libre'),
    (505, 5, 4, 'libre'),
    (506, 5, 4, 'libre'),
    (601, 6, 12, 'libre'),
    (701, 7, 12, 'libre'),
    (801, 8, 16, 'libre'),
    (901, 9, 18, 'libre');

-- Insertar reservas de ejemplo
INSERT INTO tbl_reservas (id_usuario, id_mesa, fecha, hora_inicio, hora_fin) VALUES
    (1, 1, '2024-11-15', '12:00:00', '14:00:00'),
    (2, 3, '2024-11-15', '18:00:00', '19:00:00'),
    (3, 5, '2024-11-15', '20:00:00', '22:00:00');

-- ALTER TABLE tbl_salas
--    ADD COLUMN tipo_sala_image VARCHAR(255) DEFAULT 'default.jpg',
--    ADD COLUMN nombre_sala_image VARCHAR(255) DEFAULT 'default.jpg';
ALTER TABLE tbl_reservas
ADD COLUMN nombre_persona VARCHAR(255) AFTER id_usuario;