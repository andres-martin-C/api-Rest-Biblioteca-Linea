-- Crear tabla libro
CREATE TABLE IF NOT EXISTS libro (
  `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(255) NOT NULL,
  `escritor` VARCHAR(255) NOT NULL,
  `isbn` VARCHAR(20) NOT NULL,  -- ISBN generalmente es una cadena
  `idioma` VARCHAR(50) NOT NULL,
  `descripcion` TEXT,
  `genero` VARCHAR(100) NOT NULL,
  `tipo` ENUM('fisico', 'digital') NOT NULL,  -- Ejemplo de valores de tipo
  `cantidad_Disponible` INT NOT NULL,  -- Cambié numeric por INT
  `precio` DECIMAL(10, 2) NOT NULL,  -- Añadí precisión y escala al DECIMAL
  `fecha_ingreso` DATETIME NOT NULL
);

-- Crear la tabla user (usuario)
CREATE TABLE IF NOT EXISTS `user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(255) NOT NULL,
  `apellido_Paterno` VARCHAR(255) NOT NULL,  -- Cambié bigint por varchar
  `apellido_Materno` VARCHAR(255) NOT NULL,  -- Cambié bigint por varchar
  `correo` VARCHAR(255) NOT NULL UNIQUE,  -- Añadí UNIQUE para evitar duplicados
  `password` VARCHAR(255) NOT NULL,
  `rol` ENUM('admin', 'user', 'moderator') NOT NULL  -- Definí los valores del enum
);

-- Crear tabla libro_vendido
CREATE TABLE IF NOT EXISTS libro_vendido (
  `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_libro` BIGINT NOT NULL,
  `id_user` BIGINT NOT NULL,
  `cantidad` INT NOT NULL,  -- Cambié NUMERIC a INT, asumiendo que la cantidad es un número entero
  `precio_venta` DECIMAL(10, 2) NOT NULL,  -- El precio se mantiene como DECIMAL con 2 decimales
  `fecha_vendida` DATETIME NOT NULL,
  `folio` VARCHAR(500) NOT NULL,
  -- Claves foráneas
  CONSTRAINT libro_vendido_id_user_fk FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT libro_vendido_id_libro_fk FOREIGN KEY (`id_libro`) REFERENCES libro (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Crear la tabla carrito
CREATE TABLE IF NOT EXISTS carrito (
  `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user` BIGINT NOT NULL,
  `id_libro` BIGINT NOT NULL,
  FOREIGN KEY (`id_user`) REFERENCES `user` (`id`),
  FOREIGN KEY (`id_libro`) REFERENCES libro (`id`)
);

-- Crear la tabla compra
CREATE TABLE IF NOT EXISTS compra (
  `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user` BIGINT NOT NULL,
  `id_libro` BIGINT NOT NULL,
  `unidad_libro` INT NOT NULL,  -- Cambié numeric por INT
  `precio_unitario` DECIMAL(10, 2) NOT NULL,  -- Añadí precisión y escala al DECIMAL
  `fecha_compra` DATETIME NOT NULL,
  `fecha_Aproximada_Devolucion` DATETIME NOT NULL,
  `folio` VARCHAR(255) NOT NULL,  -- Especificado tamaño
  `Direccion_enviada` VARCHAR(255) DEFAULT NULL,
  FOREIGN KEY (`id_user`) REFERENCES `user` (`id`),
  FOREIGN KEY (`id_libro`) REFERENCES libro (`id`)
);

-- Crear índices para optimizar las consultas
CREATE INDEX idx_carrito_id_user ON carrito (`id_user`);
CREATE INDEX idx_compra_id_user ON compra (`id_user`);
CREATE INDEX idx_libro_vendido_id_user ON libro_vendido (`id_user`);
