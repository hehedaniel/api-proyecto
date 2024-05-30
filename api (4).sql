-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 30-05-2024 a las 17:40:58
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
('DoctrineMigrations\\Version20240530152406', '2024-05-30 17:24:55', 1647);

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
  `valor_met` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ejercicio`
--

INSERT INTO `ejercicio` (`id`, `id_usuario_id`, `nombre`, `descripcion`, `grupo_muscular`, `dificultad`, `instrucciones`, `valor_met`) VALUES
(1, 1, 'Caminar', 'Caminar en terreno plano', 'Pierna', 'Fácil', 'Simplemente camina', 2.5),
(2, 1, 'Correr', 'Correr en terreno plano a ritmo moderado', 'Pierna', 'Moderado', 'Corre a un ritmo constante durante al menos 30 minutos', 7),
(3, 2, 'Flexiones', 'Flexiones de brazo en el suelo', 'Pecho', 'Moderado', 'Realiza flexiones manteniendo el cuerpo recto y bajando hasta que el pecho toque el suelo', 3.8),
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
(3, 'Carlos', 'González', 'carlos.gonzalez@trackit.com', 'true', 'carlos123', 0, 35, 175, 'mantener peso', 70);

--
-- Índices para tablas volcadas
--

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
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `enlace`
--
ALTER TABLE `enlace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
