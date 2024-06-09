-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 01-06-2024 a las 17:34:05
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `api`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimento`
--

CREATE TABLE `alimento` (
  `id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `marca` varchar(255) NOT NULL,
  `cantidad` double NOT NULL,
  `proteinas` double NOT NULL,
  `grasas` double NOT NULL,
  `carbohidratos` double NOT NULL,
  `azucares` double NOT NULL,
  `vitaminas` double NOT NULL,
  `minerales` double NOT NULL,
  `imagen` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `alimento`
--

INSERT INTO `alimento` (`id`, `id_usuario_id`, `nombre`, `descripcion`, `marca`, `cantidad`, `proteinas`, `grasas`, `carbohidratos`, `azucares`, `vitaminas`, `minerales`, `imagen`) VALUES
(1, 1, 'Manzana', 'Fruta', 'Ninguna', 1, 0.2, 0.2, 22.1, 0.2, 0.2, 0.2, 'imagen en base 64'),
(2, 2, 'Plátano', 'Fruta', 'Ninguna', 1, 1.3, 0.3, 27, 14.4, 0.5, 0.4, 'imagen en base 64'),
(3, 3, 'Yogur natural', 'Lácteo', 'Danone', 1, 4.5, 3.5, 5, 4.7, 0.1, 0.1, 'imagen en base 64'),
(4, 4, 'Pan integral', 'Panadería', 'Bimbo', 1, 7.5, 2.5, 44, 5, 0.4, 0.3, 'imagen en base 64'),
(5, 5, 'Pollo a la plancha', 'Carne', 'Ninguna', 1, 25, 3, 0, 0, 0.2, 0.2, 'imagen en base 64'),
(6, 4, 'Zanahoria', 'Verdura', 'Ninguna', 1, 0.9, 0.2, 10, 4.7, 0.2, 0.3, 'imagen en base 64');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumo_dia`
--

CREATE TABLE `consumo_dia` (
  `id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `comida` varchar(255) NOT NULL,
  `cantidad` double NOT NULL,
  `momento` varchar(255) NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `consumo_dia`
--

INSERT INTO `consumo_dia` (`id`, `id_usuario_id`, `fecha`, `comida`, `cantidad`, `momento`, `hora`) VALUES
(1, 2, '2024-06-01', 'Macarrones con tomate', 100, 'Almuerzo', '09:56:50'),
(2, 3, '2024-06-01', 'Ensalada César', 150, 'Cena', '19:30:00'),
(3, 2, '2024-06-01', 'Pollo al curry', 200, 'Almuerzo', '13:00:00'),
(4, 2, '2024-06-01', 'Batido de frutas', 250, 'Desayuno', '08:00:00'),
(5, 3, '2024-06-01', 'Sopa de lentejas', 300, 'Cena', '20:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240530152406', '2024-05-30 17:24:55', 1647),
('DoctrineMigrations\\Version20240531150412', '2024-05-31 17:04:55', 1797),
('DoctrineMigrations\\Version20240531160954', '2024-05-31 18:10:13', 828),
('DoctrineMigrations\\Version20240531164133', '2024-05-31 18:41:59', 698),
('DoctrineMigrations\\Version20240531175627', '2024-05-31 19:56:56', 780),
('DoctrineMigrations\\Version20240531175758', '2024-05-31 19:58:20', 915),
('DoctrineMigrations\\Version20240531183926', '2024-05-31 20:39:47', 848),
('DoctrineMigrations\\Version20240601110044', '2024-06-01 13:07:12', 1755);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicio`
--

CREATE TABLE `ejercicio` (
  `id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `grupo_muscular` varchar(255) NOT NULL,
  `dificultad` varchar(255) NOT NULL,
  `instrucciones` longtext NOT NULL,
  `valor_met` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ejercicio`
--

INSERT INTO `ejercicio` (`id`, `id_usuario_id`, `nombre`, `descripcion`, `grupo_muscular`, `dificultad`, `instrucciones`, `valor_met`) VALUES
(1, 1, 'Caminar', 'Caminar en terreno plano', 'Pierna', 'Fácil', 'Simplemente camina', 2),
(2, 1, 'Correr', 'Correr en terreno plano a ritmo moderado', 'Pierna', 'Moderado', 'Corre a un ritmo constante durante al menos 30 minutos', 7),
(3, 2, 'Flexiones', 'Flexiones de brazo en el suelo', 'Pecho', 'Moderado', 'Realiza flexiones manteniendo el cuerpo recto y bajando hasta que el pecho toque el suelo', 4),
(4, 3, 'Abdominales', 'Abdominales tradicionales', 'Abdomen', 'Moderado', 'Realiza abdominales levantando el torso hacia las rodillas mientras mantienes los pies en el suelo', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enlace`
--

CREATE TABLE `enlace` (
  `id` int(11) NOT NULL,
  `id_ejercicio_id` int(11) NOT NULL,
  `enlace` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `enlace`
--

INSERT INTO `enlace` (`id`, `id_ejercicio_id`, `enlace`) VALUES
(1, 1, 'https://trackit.com'),
(2, 2, 'https://trackit.com'),
(3, 3, 'https://trackit.com'),
(4, 4, 'https://trackit.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peso`
--

CREATE TABLE `peso` (
  `id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `peso` double NOT NULL,
  `imc` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `peso`
--

INSERT INTO `peso` (`id`, `id_usuario_id`, `fecha`, `hora`, `peso`, `imc`) VALUES
(1, 3, '2024-06-01', '17:04:34', 80, 26.1),
(2, 2, '2024-06-02', '08:00:00', 65, 23.9),
(3, 2, '2024-06-03', '08:00:00', 66, 24.2),
(4, 2, '2024-06-04', '08:00:00', 64, 23.5),
(5, 2, '2024-06-05', '08:00:00', 67, 24.6),
(6, 2, '2024-06-06', '08:00:00', 65.5, 24.1),
(7, 3, '2024-06-02', '09:00:00', 79, 25.8),
(8, 3, '2024-06-03', '09:00:00', 78, 25.5),
(9, 3, '2024-06-04', '09:00:00', 81, 26.4),
(10, 3, '2024-06-05', '09:00:00', 80, 26.1),
(11, 3, '2024-06-06', '09:00:00', 82, 26.8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `instrucciones` longtext NOT NULL,
  `cantidad_final` double NOT NULL,
  `proteinas` double NOT NULL,
  `grasas` double NOT NULL,
  `carbohidratos` double NOT NULL,
  `azucares` double NOT NULL,
  `vitaminas` double NOT NULL,
  `minerales` double NOT NULL,
  `imagen` longtext NOT NULL,
  `id_usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `nombre`, `descripcion`, `instrucciones`, `cantidad_final`, `proteinas`, `grasas`, `carbohidratos`, `azucares`, `vitaminas`, `minerales`, `imagen`, `id_usuario_id`) VALUES
(1, 'Macarrones con tomate', 'Los macarrones con tomate de mi abuela que tanto me han gustado siempre', '- Cocer los macarrones al gusto\r\n- Echar el tomate a los macarrones\r\n\r\nOpcional\r\n- Echar salchichas\r\n- Echar queso rallado y gratinarlo 5 min', 1, 19.64, 12.96, 85.2, 7.98, 23.78, 0, 'imagen en base 64', 2),
(2, 'Ensalada César', 'Ensalada clásica con pollo, lechuga, y aderezo César', '- Cortar la lechuga y el pollo en tiras\r\n- Mezclar la lechuga, el pollo, los croutones y el queso parmesano\r\n- Agregar el aderezo César y mezclar bien\r\n- Servir y disfrutar', 1, 21.6, 18.3, 11.2, 2.1, 17.4, 0, 'imagen en base 64', 3),
(3, 'Pollo al curry', 'Pollo cocinado en una rica salsa de curry con vegetales', '- Cortar el pollo en trozos\r\n- Saltear la cebolla y el ajo\r\n- Agregar el pollo y cocinar hasta dorar\r\n- Añadir la leche de coco y el curry\r\n- Cocinar a fuego lento hasta que el pollo esté tierno\r\n- Servir con arroz', 1, 31.2, 15.4, 12.9, 5.4, 21.3, 0, 'imagen en base 64', 2),
(4, 'Batido de frutas', 'Batido refrescante con una mezcla de frutas', '- Cortar las frutas en trozos\r\n- Añadir las frutas y el yogur en la licuadora\r\n- Licuar hasta obtener una mezcla homogénea\r\n- Servir frío', 1, 4.2, 1.1, 23.8, 18.6, 29.8, 0, 'imagen en base 64', 2),
(5, 'Sopa de lentejas', 'Sopa nutritiva de lentejas con verduras', '- Remojar las lentejas durante la noche\r\n- Cocinar las lentejas con zanahoria, apio, y cebolla\r\n- Agregar especias al gusto\r\n- Cocinar a fuego lento hasta que las lentejas estén tiernas\r\n- Servir caliente', 1, 12.8, 3.7, 30.4, 4.5, 18.2, 0, 'imagen en base 64', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `correo_v` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` tinyint(1) NOT NULL,
  `edad` int(11) NOT NULL,
  `altura` double NOT NULL,
  `objetivo_opt` varchar(255) NOT NULL,
  `objetivo_num` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellidos`, `correo`, `correo_v`, `contrasena`, `rol`, `edad`, `altura`, `objetivo_opt`, `objetivo_num`) VALUES
(1, 'admin', 'Admin Base', 'admin@trackit.com', 'true', 'admin', 1, -1, -1, 'ninguno', -1),
(2, 'Laura', 'Martínez', 'laura.martinez@trackit.com', 'true', 'laura456', 0, 28, 165, 'perder peso', 55),
(3, 'Carlos', 'González', 'carlos.gonzalez@trackit.com', 'true', 'carlos123', 0, 35, 175, 'mantener peso', 70),
(4, 'Danielprueba2', 'Mar2dtínez', 'laura2.martinez@trackit.com', 'false', 'pestillo', 0, 28, 1.55, 'perder peso', 55),
(5, 'Danielprueba2', 'Mar2dtínez', 'pruebaspruebas@trackit.com', 'false', 'pestillo', 0, 28, 1.55, 'perder peso', 55);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_realiza_ejercicio`
--

CREATE TABLE `usuario_realiza_ejercicio` (
  `id` int(11) NOT NULL,
  `id_ejercicio_id` int(11) NOT NULL,
  `id_usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `calorias` double NOT NULL,
  `tiempo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_realiza_ejercicio`
--

INSERT INTO `usuario_realiza_ejercicio` (`id`, `id_ejercicio_id`, `id_usuario_id`, `fecha`, `hora`, `calorias`, `tiempo`) VALUES
(1, 2, 3, '2024-06-01', '17:13:32', 280, 30),
(2, 1, 2, '2024-06-02', '07:00:00', 65, 30),
(3, 2, 2, '2024-06-03', '08:30:00', 341.25, 45),
(4, 3, 2, '2024-06-04', '07:45:00', 86.24, 20),
(5, 4, 2, '2024-06-05', '06:30:00', 81.9, 25),
(6, 1, 2, '2024-06-06', '18:00:00', 130, 60),
(7, 2, 3, '2024-06-02', '07:30:00', 280, 30),
(8, 3, 3, '2024-06-03', '06:45:00', 80, 15),
(9, 4, 3, '2024-06-04', '19:00:00', 160.8, 40),
(10, 1, 3, '2024-06-05', '08:00:00', 120, 45),
(11, 2, 3, '2024-06-06', '06:00:00', 560, 60);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alimento`
--
ALTER TABLE `alimento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A3C395937EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `consumo_dia`
--
ALTER TABLE `consumo_dia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_77412CD17EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_95ADCFF47EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `enlace`
--
ALTER TABLE `enlace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8414B27913487F0F` (`id_ejercicio_id`);

--
-- Indices de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indices de la tabla `peso`
--
ALTER TABLE `peso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DD7820B77EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8ADA30D57EB2C349` (`id_usuario_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario_realiza_ejercicio`
--
ALTER TABLE `usuario_realiza_ejercicio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C16D635113487F0F` (`id_ejercicio_id`),
  ADD KEY `IDX_C16D63517EB2C349` (`id_usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alimento`
--
ALTER TABLE `alimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `consumo_dia`
--
ALTER TABLE `consumo_dia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `enlace`
--
ALTER TABLE `enlace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `peso`
--
ALTER TABLE `peso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario_realiza_ejercicio`
--
ALTER TABLE `usuario_realiza_ejercicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alimento`
--
ALTER TABLE `alimento`
  ADD CONSTRAINT `FK_A3C395937EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `consumo_dia`
--
ALTER TABLE `consumo_dia`
  ADD CONSTRAINT `FK_77412CD17EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  ADD CONSTRAINT `FK_95ADCFF47EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `enlace`
--
ALTER TABLE `enlace`
  ADD CONSTRAINT `FK_8414B27913487F0F` FOREIGN KEY (`id_ejercicio_id`) REFERENCES `ejercicio` (`id`);

--
-- Filtros para la tabla `peso`
--
ALTER TABLE `peso`
  ADD CONSTRAINT `FK_DD7820B77EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `FK_8ADA30D57EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `usuario_realiza_ejercicio`
--
ALTER TABLE `usuario_realiza_ejercicio`
  ADD CONSTRAINT `FK_C16D635113487F0F` FOREIGN KEY (`id_ejercicio_id`) REFERENCES `ejercicio` (`id`),
  ADD CONSTRAINT `FK_C16D63517EB2C349` FOREIGN KEY (`id_usuario_id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
