-- Base de datos para FCStore
CREATE DATABASE IF NOT EXISTS fcstore;
USE fcstore;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    pais VARCHAR(50),
    direccion TEXT,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    clave VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de productos (si no existe)
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    categoria VARCHAR(100),
    precio DECIMAL(10,2) NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    disponibilidad INT DEFAULT 0,
    promocion BOOLEAN DEFAULT FALSE,
    descripcion TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de compras (si no existe)
CREATE TABLE IF NOT EXISTS compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente INT,
    codigo VARCHAR(20),
    nombre VARCHAR(200),
    precio DECIMAL(10,2),
    cantidad INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente) REFERENCES usuarios(id)
);

-- Insertar algunos usuarios de prueba
INSERT INTO usuarios (nombre, email, telefono, pais, direccion, usuario, clave) VALUES
('Juan Pérez', 'juan@email.com', '123456789', 'España', 'Calle Principal 123', 'juanperez', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('María García', 'maria@email.com', '987654321', 'México', 'Av. Reforma 456', 'mariagarcia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('Admin FCStore', 'admin@fcstore.com', '555123456', 'Colombia', 'Calle Admin 789', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password 