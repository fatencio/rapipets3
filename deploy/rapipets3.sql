-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2016 a las 17:07:27
-- Versión del servidor: 10.1.10-MariaDB
-- Versión de PHP: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rapipets3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `animal`
--

CREATE TABLE `animal` (
  `animal_id` int(11) NOT NULL,
  `animal_nombre` varchar(255) DEFAULT NULL,
  `animal_conraza` tinyint(4) NOT NULL DEFAULT '0',
  `animal_contamanios` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `animal`
--

INSERT INTO `animal` (`animal_id`, `animal_nombre`, `animal_conraza`, `animal_contamanios`) VALUES
(1, 'Perro', 0, 0),
(2, 'Gato', 0, 0),
(4, 'Conejo', 1, 0),
(23, 'Caballo', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('042ef7faec22d684d3498dd479be334f0b708b61', '::1', 1463007165, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436323939393730363b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('2340584dc56c33d14ef4df45e37cc7c6e2e83d90', '::1', 1462999295, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436323939363834323b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('4197b12686f1a0057d3d76071d202b3019b69dd0', '::1', 1463151953, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436333135303531343b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('47a31fb80d1c6d3f49e2a57b7c559aa54003d0d1', '::1', 1463150326, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436333134393834363b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('8bd16a598a1d30261f76ad16162d844306499c71', '::1', 1463011081, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436333030373235393b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('95f84f1e9e44cb0c0d673df3e1baec1e2f6c18c3', '::1', 1463104908, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436333130343537393b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('985e94add3d1f6e8a012fa015b62af0972bed87e', '::1', 1463149585, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436333134393437353b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('b072bbad36254881082de54c7cef526accf11b05', '::1', 1463147765, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436333134373637323b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('b30997ec879cf5df4990012bd11a2c29b18b0f6f', '::1', 1463148695, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436333134383431333b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('cf2e9d040f2a6de7a4415652904477b592e9a24f', '::1', 1462999565, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436323939393334383b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('eb514969de57967dba4329b52037f34d2d5c0743', '::1', 1463149456, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436333134383932373b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b),
('f483ef9ddca22babe80bb43cb1a3cddd54bec012', '::1', 1463027052, 0x5f5f63695f6c6173745f726567656e65726174657c693a313436333031313135353b757365725f69647c693a313b757365726e616d657c733a383a22666174656e63696f223b6c6f676765645f696e7c623a313b69735f636f6e6669726d65647c623a303b69735f61646d696e7c623a303b);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuento`
--

CREATE TABLE `descuento` (
  `descuento_id` int(11) NOT NULL,
  `descuento_nombre` varchar(50) DEFAULT NULL,
  `descuento_porcentaje` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `descuento`
--

INSERT INTO `descuento` (`descuento_id`, `descuento_nombre`, `descuento_porcentaje`) VALUES
(15, 'Descuento del 15%', 15),
(16, 'Descuento del 20%', 20),
(17, 'Descuento del 25%', 25),
(18, 'Descuento del 30%', 30),
(19, 'Descuento del 35%', 35),
(20, 'Descuento del 40%', 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `presentacion_id` int(11) NOT NULL,
  `presentacion_nombre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `presentacion`
--

INSERT INTO `presentacion` (`presentacion_id`, `presentacion_nombre`) VALUES
(7, '7,5 KG'),
(8, '12 KG'),
(9, '20 KG'),
(10, '10 KG'),
(11, '15 KG'),
(12, '2 KG'),
(13, '3 KG'),
(14, '1,5 KG9'),
(16, '8 KG'),
(17, '22 KG'),
(18, '800 GR'),
(19, '4 KG'),
(20, '3,5 KG'),
(21, '9 KG'),
(22, '13 KG'),
(23, '2,5 KG'),
(24, '21 KG'),
(25, '7 KG'),
(26, '340 GR'),
(27, '85 GR'),
(28, '100 GR'),
(29, '2,7 KG'),
(30, '1,6 KG'),
(31, '25 KG'),
(32, '80 KG'),
(33, '90 KG'),
(34, '5'),
(37, '1.3 KG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tamanio`
--

CREATE TABLE `tamanio` (
  `tamanio_id` int(11) NOT NULL,
  `tamanio_nombre` varchar(255) DEFAULT NULL,
  `tamanio_id_animal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tamanio`
--

INSERT INTO `tamanio` (`tamanio_id`, `tamanio_nombre`, `tamanio_id_animal`) VALUES
(3, 'Medianos', 2),
(4, 'Grandes', 1),
(5, 'kkk', 2),
(6, 'Peeee', NULL),
(7, '7777', NULL),
(8, 'Chicos', NULL),
(9, 'pequeñito', NULL),
(10, 'De perro', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) DEFAULT 'default.jpg',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `is_confirmed` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `avatar`, `created_at`, `updated_at`, `is_admin`, `is_confirmed`, `is_deleted`) VALUES
(1, 'fatencio', 'fatencio@gmail.com', '$2y$10$/9rnAHyKxIWVW4Q/RhW2iulhlc0Cf7nEd40NmR2d0ioXlgSeSYXe.', 'default.jpg', '2016-05-03 23:01:36', NULL, 0, 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`animal_id`);

--
-- Indices de la tabla `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indices de la tabla `descuento`
--
ALTER TABLE `descuento`
  ADD PRIMARY KEY (`descuento_id`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`presentacion_id`);

--
-- Indices de la tabla `tamanio`
--
ALTER TABLE `tamanio`
  ADD PRIMARY KEY (`tamanio_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `animal`
--
ALTER TABLE `animal`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `descuento`
--
ALTER TABLE `descuento`
  MODIFY `descuento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  MODIFY `presentacion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT de la tabla `tamanio`
--
ALTER TABLE `tamanio`
  MODIFY `tamanio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
