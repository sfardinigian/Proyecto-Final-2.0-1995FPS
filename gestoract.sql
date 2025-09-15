-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-09-2025 a las 23:33:04
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
-- Base de datos: `gestoract`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id_actividad` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `color` varchar(20) NOT NULL DEFAULT '#000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(64) NOT NULL,
  `apellido` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `pass` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `email`, `pass`) VALUES
(33, 'Gian', 'Sfardini', 'gianmsfardini@gmail.com', '$2y$10$pWDQYanR2UVmi30fl/mVHunJUdbw93sxy9Mtt/A7H3AkwI4zT/0F2'),
(37, 'Franco', 'Ibarra', 'franco@gmail.com', '$2y$10$hYwjADsz/t4rNounU7o34OkO/5Q55vYke55BB6Oz.6724Yk0CGTfK'),
(40, 'amd', 'ryzen', 'amdryzen@gmail.com', '$2y$10$hUDYLCFbZn5Hi4Bro9vgiech3PGGqaVa3t4fcA.vcrGrhH9bPGufW'),
(43, 'intel', 'core', 'intelcore@gmail.com', '$2y$10$0biKbMf6AKIJYk5S7CPohuYyYhrn0rSU6fO6Tczk3/6ZV4GFCz86.'),
(44, 'Ana', 'Jara', 'jaraana789@gmail.com', '$2y$10$h5VU.Aic5tATE4zK.tMDxeBczL74IlBGI6V2Il4DSTooUVO7cs1w2'),
(45, 'Pepe', 'Pérez', 'pepeperez1@gmail.com', '$2y$10$kXg3McXwbm7TQKZaIri9suotIY3Dj99ZKHf/ObO4VPNbPhb1DjcCW'),
(46, 'hola', 'hola', 'hola@gmail.com', '$2y$10$BvVTmou75R2yfEBCSJznc.SWtR9dLTSrbOjVJH/IYf8hbKbpwYNpC'),
(47, 'g', 's', 'gs@gmail.com', '$2y$10$14aF6MGqn18hMThf4kbDJucy.mhhb.WOTM9ADd0zKqcWmSE.aAB6O');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id_actividad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `fk_usuarios_actividades` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
