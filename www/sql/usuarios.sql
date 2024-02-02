CREATE TABLE `usuarios` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `tipo` varchar(255) NOT NULL DEFAULT "usuario",
  `telefono` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255),
  `direccion` varchar(255),
  `activo` integer NOT NULL DEFAULT 0,
  `created_at` timestamp
);

CREATE TABLE `usuario_categoria` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `usuario_id` bigint,
  `categoria_id` bigint
);

CREATE TABLE `categoria` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(255)
);

ALTER TABLE `usuario_categoria` ADD FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

ALTER TABLE `usuario_categoria` ADD FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);
