-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-04-2025 a las 01:18:21
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reportes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `nombre`, `email`, `contraseña`, `created_at`) VALUES
(4, 'Marcos', 'remigiomarcos68@gmail.com', '$2y$10$bQav9ugzBZZ88Dxi5w3vHOyHQhSbyhna2Yq22QjEXfdjenBkB9uMm', '2025-03-15 00:36:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunidades`
--

CREATE TABLE `comunidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comunidades`
--

INSERT INTO `comunidades` (`id`, `nombre`, `created_at`) VALUES
(1, 'San Pedro Arriba', '2025-02-24 19:54:32'),
(2, 'San Pedro Abajo', '2025-02-24 19:54:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion_departamento`
--

CREATE TABLE `direccion_departamento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion_departamento`
--

INSERT INTO `direccion_departamento` (`id`, `nombre`, `descripcion`, `created_at`) VALUES
(1, 'Informática', 'Departamento de Tecnologías de la Información', '2025-03-07 01:19:23'),
(2, 'Recursos Humanos', 'Departamento de Gestión de Personal', '2025-03-07 01:19:23'),
(3, 'Finanzas', 'Departamento de Gestión Financiera', '2025-03-07 01:19:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peticiones`
--

CREATE TABLE `peticiones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tipo_servicio_id` int(11) NOT NULL,
  `comunidad_id` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `colonia` varchar(255) DEFAULT NULL,
  `numero_solicitud` varchar(50) NOT NULL,
  `estatus` enum('pendiente','en proceso','resuelto') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `evidencia_foto` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servidores_publicos`
--

CREATE TABLE `servidores_publicos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `comunidad_id` int(11) NOT NULL,
  `departamento_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `colonia` varchar(255) DEFAULT NULL,
  `numero_solicitud` varchar(50) NOT NULL DEFAULT 'SP-0000',
  `estatus` enum('pendiente','en proceso','resuelto') NOT NULL DEFAULT 'pendiente',
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `evidencia_foto` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servidores_publicos`
--

INSERT INTO `servidores_publicos` (`id`, `nombre`, `comunidad_id`, `departamento_id`, `email`, `contraseña`, `created_at`, `observaciones`, `direccion`, `colonia`, `numero_solicitud`, `estatus`, `latitud`, `longitud`, `evidencia_foto`) VALUES
(8, 'JUAN marcos remigio VICTORIANO', 1, 2, 'remigiomarcos68@gmail.com', '$2y$10$V8tnq2pXAzagvlVRBkSggOpxMiG9aWXag0/Tr7CfJBLZER1uIwUWK', '2025-04-04 00:24:07', NULL, NULL, NULL, 'SP-0000', 'pendiente', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_servicio`
--

CREATE TABLE `tipos_servicio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_servicio`
--

INSERT INTO `tipos_servicio` (`id`, `nombre`, `descripcion`, `created_at`) VALUES
(1, 'Luminaria', 'Reportar luminarias dañadas', '2025-02-24 19:56:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `comunidades`
--
ALTER TABLE `comunidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `direccion_departamento`
--
ALTER TABLE `direccion_departamento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `peticiones`
--
ALTER TABLE `peticiones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_solicitud` (`numero_solicitud`),
  ADD KEY `tipo_servicio_id` (`tipo_servicio_id`),
  ADD KEY `comunidad_id` (`comunidad_id`);

--
-- Indices de la tabla `servidores_publicos`
--
ALTER TABLE `servidores_publicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `comunidad_id` (`comunidad_id`),
  ADD KEY `departamento_id` (`departamento_id`);

--
-- Indices de la tabla `tipos_servicio`
--
ALTER TABLE `tipos_servicio`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `comunidades`
--
ALTER TABLE `comunidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `direccion_departamento`
--
ALTER TABLE `direccion_departamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `peticiones`
--
ALTER TABLE `peticiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `servidores_publicos`
--
ALTER TABLE `servidores_publicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tipos_servicio`
--
ALTER TABLE `tipos_servicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `peticiones`
--
ALTER TABLE `peticiones`
  ADD CONSTRAINT `peticiones_ibfk_1` FOREIGN KEY (`tipo_servicio_id`) REFERENCES `tipos_servicio` (`id`),
  ADD CONSTRAINT `peticiones_ibfk_2` FOREIGN KEY (`comunidad_id`) REFERENCES `comunidades` (`id`);

--
-- Filtros para la tabla `servidores_publicos`
--
ALTER TABLE `servidores_publicos`
  ADD CONSTRAINT `servidores_publicos_ibfk_1` FOREIGN KEY (`comunidad_id`) REFERENCES `comunidades` (`id`),
  ADD CONSTRAINT `servidores_publicos_ibfk_2` FOREIGN KEY (`departamento_id`) REFERENCES `direccion_departamento` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
