CREATE DATABASE bd_restaurante;

USE bd_restaurante;

-- Tabla de usuarios para los camareros
CREATE TABLE tbl_usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre_user VARCHAR(100),
    contrasena VARCHAR(100)
);

-- Tabla de salas para diferenciar mesas
CREATE TABLE tbl_salas (
    id_sala INT PRIMARY KEY AUTO_INCREMENT,
    nombre_sala VARCHAR(100),
    tipo_sala VARCHAR(50)       -- Tipo de sala (Terraza, Comedor, Sala Privada...)
);

-- Tabla de mesas
CREATE TABLE tbl_mesas (
    id_mesa INT PRIMARY KEY AUTO_INCREMENT,
    numero_mesa INT,
    id_sala INT,
    numero_sillas INT,
    estado ENUM('libre','ocupada') DEFAULT 'libre'
);

-- Tabla para los registros de ocupación de las mesas
CREATE TABLE tbl_ocupaciones (
    id_ocupacion INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_mesa INT,
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,    -- Fecha y hora del inicio de la ocupación
    fecha_fin DATETIME                                  -- Fecha y hora del final de la ocupación
);

-- Definición de las FOREIGN KEYs
ALTER TABLE tbl_mesas
ADD CONSTRAINT fk_mesas_salas FOREIGN KEY (id_sala) REFERENCES tbl_salas(id_sala);

ALTER TABLE tbl_ocupaciones
ADD CONSTRAINT fk_ocupaciones_usuarios FOREIGN KEY (id_usuario) REFERENCES tbl_usuarios(id_usuario),
ADD CONSTRAINT fk_ocupaciones_mesas FOREIGN KEY (id_mesa) REFERENCES tbl_mesas(id_mesa);




-- Insertar usuarios (camareros)
INSERT INTO tbl_usuarios (id_usuario, nombre_user, contrasena) VALUES
    (1, 'Jorge', '$2y$10$wORRwXyRsJRc9ua8okkNuO6m/GbqBuZouNb4LZbwFPDG6HwNUhOVa'),   -- asdASD123
    (2, 'Olga', '$2y$10$wORRwXyRsJRc9ua8okkNuO6m/GbqBuZouNb4LZbwFPDG6HwNUhOVa'),    -- asdASD123
    (3, 'Miguel', '$2y$10$wORRwXyRsJRc9ua8okkNuO6m/GbqBuZouNb4LZbwFPDG6HwNUhOVa');  -- asdASD123

-- Insertar salas
INSERT INTO tbl_salas (id_sala, nombre_sala, tipo_sala, capacidad) VALUES
    (1, 'Terraza 1', 'Terraza', 20),
    (2, 'Terraza 2', 'Terraza', 20),
    (3, 'Terraza 3', 'Terraza', 20),
    (4, 'Comedor 1', 'Comedor', 30),
    (5, 'Comedor 2', 'Comedor', 25),
    (6, 'Sala Privada 1', 'Privada', 10),
    (7, 'Sala Privada 2', 'Privada', 8),
    (8, 'Sala Privada 3', 'Privada', 12),
    (9, 'Sala Privada 4', 'Privada', 15);

INSERT INTO tbl_mesas (id_mesa, numero_mesa, id_sala, numero_sillas, estado) VALUES
-- Mesas Terraza 1
    (1, 101, 1, 4, 'libre'),
    (2, 102, 1, 6, 'libre'),
    (3, 103, 1, 4, 'libre'),
    (4, 104, 1, 9, 'libre'),
-- Mesas Terraza 2
    (5, 201, 2, 4, 'libre'),
    (6, 202, 2, 6, 'libre'),
    (7, 203, 2, 12, 'libre'),
    (8, 204, 2, 4, 'libre'),
-- Mesas Terraza 3
    (9, 301, 3, 4, 'libre'),
    (10, 302, 3, 4, 'libre'),
    (11, 303, 3, 7, 'libre'),
    (12, 304, 3, 2, 'libre');

-- Insertar mesas en los comedores (10 mesas en cada comedor)
INSERT INTO tbl_mesas (id_mesa, numero_mesa, id_sala,  numero_sillas, estado) VALUES
    -- Mesas para el Comedor 1
    (13, 401, 4, 2, 'libre'),
    (14, 402, 4, 9, 'libre'),
    (15, 403, 4, 2, 'libre'),
    (16, 404, 4, 7, 'libre'),
    (17, 405, 4, 5, 'libre'),
    (18, 406, 4, 6, 'libre'),
    -- Mesas para el Comedor 2
    (19, 501, 5, 12, 'libre'),
    (20, 502, 5, 9, 'libre'),
    (21, 503, 5, 16, 'libre'),
    (22, 504, 5, 2, 'libre'),
    (23, 505, 5, 4, 'libre'),
    (24, 506, 5, 4, 'libre');

    -- Insertar mesas en las salas privadas (1 mesa por sala)
INSERT INTO tbl_mesas (id_mesa, numero_mesa, id_sala,  numero_sillas, estado) VALUES
    (25, 601, 6, 12, 'libre'),
    (26, 701, 7, 12, 'libre'),
    (27, 801, 8, 16, 'libre'),
    (28, 901, 9, 18, 'libre');

-- Insertar ocupaciones (registros de ocupación de mesas)
INSERT INTO tbl_ocupaciones (id_ocupacion, id_usuario, id_mesa, fecha_inicio, fecha_fin) VALUES
    (1, 1, 1, '2024-11-15 12:30:00', '2024-11-15 14:30:00'),
    (2, 2, 3, '2024-11-15 18:00:00', '2024-11-15 19:30:00'),
    (3, 3, 5, '2024-11-15 20:00:00', '2024-11-15 22:00:00');