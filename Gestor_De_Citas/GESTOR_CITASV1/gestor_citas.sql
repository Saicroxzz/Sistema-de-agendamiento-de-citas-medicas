-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2024 a las 15:58:02
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
-- Base de datos: `gestor_citas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `estado` enum('pendiente','completada','cancelada') DEFAULT 'pendiente',
  `razon_cancelacion` varchar(255) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `notas_doctor` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `usuario_id`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `razon_cancelacion`, `doctor_id`, `notas_doctor`) VALUES
(1, 2, '2024-09-17', '17:00:00', '00:00:00', 'completada', NULL, NULL, NULL),
(2, 2, '2024-09-21', '08:00:00', '00:00:00', 'pendiente', NULL, NULL, NULL),
(3, 2, '2024-09-30', '14:00:00', '00:00:00', 'cancelada', 'Cancelado por el usuario', NULL, NULL),
(4, 2, '2024-09-21', '14:00:00', '00:00:00', 'cancelada', 'Horario no disponible por reunion administrativa [Re agendar]', NULL, NULL),
(5, 4, '2024-09-19', '07:00:00', '00:00:00', 'pendiente', NULL, NULL, NULL),
(6, 2, '2024-09-23', '15:00:00', '00:00:00', 'completada', NULL, NULL, 'Ricardo [Medicacion]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad_citas`
--

CREATE TABLE `disponibilidad_citas` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disponibilidad_citas`
--

INSERT INTO `disponibilidad_citas` (`id`, `doctor_id`, `fecha`, `hora_inicio`, `hora_fin`, `disponible`) VALUES
(1, 1, '2024-09-21', '08:00:00', '09:00:00', 0),
(2, 1, '2024-09-17', '17:00:00', '18:00:00', 0),
(3, 1, '2024-09-21', '14:00:00', '15:00:00', 0),
(4, 1, '2024-09-19', '07:00:00', '08:00:00', 0),
(5, 1, '2024-09-30', '14:00:00', '15:00:00', 0),
(6, 0, '2024-09-23', '15:00:00', '16:00:00', 0),
(7, 1, '2024-09-20', '16:00:00', '17:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `rol` enum('administrador','usuario','doctor') NOT NULL,
  `genero` enum('masculino','femenino') NOT NULL,
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `password`, `nombre`, `apellido`, `rol`, `genero`, `telefono`) VALUES
(1, 'doc1@example.com', '$2y$10$wHFlfoAWdZQK0aLBL4cpTeHYdualSofQL4OWdVt90XB64NwbHhfpG', 'Lucas', 'Brown', 'doctor', 'masculino', '300000001'),
(2, 'user1@example.com', '$2y$10$X3SxWRI1RoFrQFwLAsjA9.F5BxjHwZHTf8R6WGcMCjYhmUM6Ji4aq', 'Ricardo', 'Forest', 'usuario', 'masculino', '30203405950'),
(3, 'admin1@example.com', '$2y$10$D.fp6HQ7412jXAhZpJhVQ.dchPKU.g0IkXUfN0aQHZ/b324NMVxA6', 'Tomy', 'Shelby', 'administrador', 'masculino', ''),
(4, 'user2@example.com', '$2y$10$oLYc8Iu9iFQokJlLy81.a.1ZTCx38jRSl7wriPRfO3zdx9b01Z9/u', 'Jessy', 'Belford', 'usuario', 'femenino', '30203405950');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `disponibilidad_citas`
--
ALTER TABLE `disponibilidad_citas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `disponibilidad_citas`
--
ALTER TABLE `disponibilidad_citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
