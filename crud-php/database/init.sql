CREATE DATABASE IF NOT EXISTS tienda;
USE tienda;

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    producto_id INT NOT NULL,
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

INSERT INTO clientes (nombre, email) VALUES
('Alejandro Jiménez', 'alejandro@example.com'),
('Ángel Callejo', 'angel@example.com'),
('José García', 'jose@example.com');

INSERT INTO productos (nombre, precio) VALUES
('Teclado Mecánico', 45.99),
('Ratón Inalámbrico', 25.50),
('Monitor 24"', 150.00);

INSERT INTO compras (cliente_id, producto_id) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 1),
(3, 3);
