-- Script de base de datos para FCStore
-- Ejecutar en Railway MySQL

CREATE DATABASE IF NOT EXISTS fcstore;
USE fcstore;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    categoria VARCHAR(100),
    stock INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de carrito
CREATE TABLE IF NOT EXISTS carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    producto_id INT,
    cantidad INT DEFAULT 1,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

-- Insertar usuario administrador por defecto
-- Password: admin123 (hasheado con password_hash)
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Administrador', 'admin@fcstore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE id=id;

-- Insertar algunos productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, imagen, categoria, stock) VALUES 
('Camiseta Barcelona 2024/25', 'Camiseta oficial del FC Barcelona temporada 2024/25', 89.99, 'FCB2425H.webp', 'Camisetas', 50),
('Camiseta Real Madrid 2024/25', 'Camiseta oficial del Real Madrid temporada 2024/25', 89.99, 'RMC2324H.webp', 'Camisetas', 45),
('Camiseta Manchester United', 'Camiseta oficial del Manchester United', 79.99, 'MUN2324H.webp', 'Camisetas', 30),
('Camiseta PSG 2024/25', 'Camiseta oficial del PSG temporada 2024/25', 84.99, 'PSG2324H.webp', 'Camisetas', 25),
('Camiseta Bayern Munich', 'Camiseta oficial del Bayern Munich', 79.99, 'BAY2324H.webp', 'Camisetas', 35)
ON DUPLICATE KEY UPDATE id=id; 