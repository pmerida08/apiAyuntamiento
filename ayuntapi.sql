-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-03-2025 a las 19:49:21
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ayuntapi`
--
CREATE DATABASE IF NOT EXISTS `ayuntapi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ayuntapi`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `centcivicos_id` int(11) NOT NULL,
  `nombre` varchar(64) NOT NULL,
  `descripcion` varchar(64) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `horario` varchar(64) NOT NULL,
  `plaza` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `actividades`:
--   `centcivicos_id`
--       `centcivicos` -> `id`
--

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `centcivicos_id`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_final`, `horario`, `plaza`) VALUES
(1, 1, 'Actividad1', 'Actividad 1 centro 1', '0000-00-00', '0000-00-00', '13:00', '65'),
(3, 1, 'Actividad2', 'Actividad1 centro1', '2025-03-02', '2025-03-13', '13:00', '65'),
(4, 2, 'Actividad1', 'acctividad1', '2025-03-14', '2025-03-15', '12:30', '54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centcivicos`
--

CREATE TABLE `centcivicos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(64) NOT NULL,
  `direccion` varchar(64) NOT NULL,
  `tel_contacto` int(64) NOT NULL,
  `horario` varchar(64) NOT NULL,
  `foto` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `centcivicos`:
--

--
-- Volcado de datos para la tabla `centcivicos`
--

INSERT INTO `centcivicos` (`id`, `nombre`, `direccion`, `tel_contacto`, `horario`, `foto`) VALUES
(1, 'Centro1', 'centro1@gmail.com', 666666666, '13:00', 'logo.png'),
(2, 'CentroCivicos2', 'CCC', 6666666, 'si', 'logo.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int(11) NOT NULL,
  `nom_solicitante` varchar(64) NOT NULL,
  `telefono` int(11) NOT NULL,
  `correo` varchar(64) NOT NULL,
  `actividades_id` int(11) NOT NULL,
  `fecha_incripcion` date DEFAULT current_timestamp(),
  `estado` varchar(64) NOT NULL COMMENT 'Aceptada/Lista de espera/Rechazada',
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `inscripciones`:
--   `actividades_id`
--       `actividades` -> `id`
--   `usuario_id`
--       `usuarios` -> `id`
--

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `nom_solicitante`, `telefono`, `correo`, `actividades_id`, `fecha_incripcion`, `estado`, `usuario_id`) VALUES
(7, 'Jose', 666666666, 'jose2@gmail.com', 1, '2025-03-13', 'pendiente', 18),
(8, 'lourdes', 666666666, 'lourdes@gmail.com', 1, '2025-03-13', 'pendiente', 19),
(13, 'Inscripcion7', 666666666, 'jose2@gmail.com', 4, '2025-03-13', 'pendiente', 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instalaciones`
--

CREATE TABLE `instalaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(64) NOT NULL,
  `descripcion` varchar(64) NOT NULL,
  `centcivicos_id` int(11) NOT NULL,
  `capacidad_max` int(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `instalaciones`:
--   `centcivicos_id`
--       `centcivicos` -> `id`
--

--
-- Volcado de datos para la tabla `instalaciones`
--

INSERT INTO `instalaciones` (`id`, `nombre`, `descripcion`, `centcivicos_id`, `capacidad_max`) VALUES
(2, 'Instalaciones2', 'Instalaciones 2 del centro 1', 1, 24),
(3, 'Instalacion5', 'Instalacion5', 2, 67);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `nom_solicitante` varchar(64) NOT NULL,
  `telefono` int(11) NOT NULL,
  `correo` varchar(64) NOT NULL,
  `instalaciones_id` int(11) NOT NULL,
  `fechahora_inicio` datetime NOT NULL DEFAULT current_timestamp(),
  `fechahora_final` datetime NOT NULL,
  `estado` varchar(64) NOT NULL COMMENT 'Confirmada/pendiente',
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `reservas`:
--   `instalaciones_id`
--       `instalaciones` -> `id`
--   `usuario_id`
--       `usuarios` -> `id`
--

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `nom_solicitante`, `telefono`, `correo`, `instalaciones_id`, `fechahora_inicio`, `fechahora_final`, `estado`, `usuario_id`) VALUES
(32, 'Reserva7', 55, 'jose2@gmail.com', 2, '2025-03-13 18:40:00', '2025-03-28 18:40:00', 'pendiente', 19),
(33, 'Reserva7', 77777, 'jose2@gmail.com', 2, '2025-03-13 18:40:00', '2025-03-28 18:40:00', 'pendiente', 19),
(34, 'Jose', 66666, 'jose2@gmail.com', 2, '2025-03-14 18:43:00', '2025-03-27 18:43:00', 'pendiente', 19),
(41, 'Reserva5', 2147483647, 'reserva2@gmail.com', 2, '2025-03-17 13:22:00', '2025-03-21 13:22:00', 'pendiente', 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `usuarios`:
--

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`) VALUES
(18, 'Pablo', 'pablomerida03@gmail.com', '1234'),
(19, 'admin4', 'adm@gmail.com', '1234'),
(20, 'mafalda', 'mafalda@gmail.com', '1234');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_centcivicosId` (`centcivicos_id`);

--
-- Indices de la tabla `centcivicos`
--
ALTER TABLE `centcivicos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_actividadesId` (`actividades_id`),
  ADD KEY `fk_inscripciones_usuario` (`usuario_id`);

--
-- Indices de la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_centCivicosId2` (`centcivicos_id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservas_usuario` (`usuario_id`),
  ADD KEY `fk_instalacionesId` (`instalaciones_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `centcivicos`
--
ALTER TABLE `centcivicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `fk_centcivicosId` FOREIGN KEY (`centcivicos_id`) REFERENCES `centcivicos` (`id`);

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `fk_actividadesId` FOREIGN KEY (`actividades_id`) REFERENCES `actividades` (`id`),
  ADD CONSTRAINT `fk_inscripciones_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  ADD CONSTRAINT `fk_centCivicosId2` FOREIGN KEY (`centcivicos_id`) REFERENCES `centcivicos` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_instalacionesId` FOREIGN KEY (`instalaciones_id`) REFERENCES `instalaciones` (`id`),
  ADD CONSTRAINT `fk_reservas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- Metadatos
--
USE `phpmyadmin`;

--
-- Metadatos para la tabla actividades
--

--
-- Metadatos para la tabla centcivicos
--

--
-- Metadatos para la tabla inscripciones
--

--
-- Metadatos para la tabla instalaciones
--

--
-- Metadatos para la tabla reservas
--

--
-- Metadatos para la tabla usuarios
--

--
-- Metadatos para la base de datos ayuntapi
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
