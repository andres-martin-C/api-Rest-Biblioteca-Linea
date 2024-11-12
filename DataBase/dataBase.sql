CREATE TABLE IF NOT EXISTS carrito (
  `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user` bigint NOT NULL,
  `id_libro` bigint NOT NULL
);

CREATE TABLE IF NOT EXISTS compra (
  `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user` bigint NOT NULL,
  `id_libro` bigint NOT NULL,
  `unidad_libro` numeric NOT NULL,
  `precio_unitario` decimal NOT NULL,
  `fecha_compra` datetime NOT NULL,
  `fecha_Aproximada_Devolucion` datetime NOT NULL,
  `folio` varchar NOT NULL,
  `Direccion_enviada` varchar DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS libro (
  `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `titulo` varchar NOT NULL,
  `escritor` varchar NOT NULL,
  `isbn` numeric NOT NULL,
  `idioma` varchar NOT NULL,
  `descripcion` text,
  `genero` varchar NOT NULL,
  `tipo` enum NOT NULL,
  `cantidad_Disponible` numeric NOT NULL,
  `precio` decimal NOT NULL,
  `fecha_ingreso` datetime NOT NULL
);

CREATE TABLE IF NOT EXISTS libro_vendido (
  `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user` bigint NOT NULL,
  `titulo_Libro` varchar NOT NULL,
  `cantidad` numeric NOT NULL,
  `precio_venta` decimal NOT NULL,
  `fecha_vendida` datetime NOT NULL,
  `folio` varchar NOT NULL
);

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar NOT NULL,
  `apellido_Paterno` bigint NOT NULL,
  `apellido_Materno` bigint NOT NULL,
  `correo` varchar NOT NULL,
  `password` varchar NOT NULL,
  `token` varchar NOT NULL,
  `tokenActivado` datetime NOT NULL,
  `rol` enum NOT NULL
);

ALTER TABLE carrito ADD CONSTRAINT carrito_id_libro_fk FOREIGN KEY (`id_libro`) REFERENCES libro (`id`);
ALTER TABLE carrito ADD CONSTRAINT carrito_id_user_fk FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);
ALTER TABLE compra ADD CONSTRAINT compra_id_libro_fk FOREIGN KEY (`id_libro`) REFERENCES libro (`id`);
ALTER TABLE compra ADD CONSTRAINT compra_id_user_fk FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);
ALTER TABLE libro_vendido ADD CONSTRAINT libro_vendido_id_user_fk FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

CREATE INDEX idx_carrito_id_user ON carrito (`id_user`);
CREATE INDEX idx_compra_id_user ON compra (`id_user`);
CREATE INDEX idx_libro_vendido_id_user ON libro_vendido (`id_user`);