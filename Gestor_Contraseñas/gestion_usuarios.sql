-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-05-2026 a las 23:11:23
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
-- Base de datos: `gestion_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credenciales`
--

CREATE TABLE `credenciales` (
  `id` int(11) NOT NULL,
  `codigo` varchar(200) NOT NULL,
  `tipo` enum('Administrador','Grupo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `credenciales`
--

INSERT INTO `credenciales` (`id`, `codigo`, `tipo`) VALUES
(1, 'AC-EB-DL-OP', 'Administrador'),
(2, 'AK-NF-LQ-KS', 'Grupo'),
(3, 'TP-NG-OP-XD', 'Grupo'),
(4, 'OG-ON-YD-HX', 'Administrador'),
(5, 'NB-HK-ER-ZT', 'Administrador'),
(6, 'AV-HX-KA-GE', 'Administrador'),
(8, 'OP-MK-XA-HZ', 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nombre_grupo` varchar(80) NOT NULL,
  `credencial_grupo_id` int(11) NOT NULL,
  `lider_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `nombre_grupo`, `credencial_grupo_id`, `lider_id`) VALUES
(1, 'Grupo 1', 2, 16),
(2, 'Grupo 2', 3, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre_rol` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`) VALUES
(1, 'Administrador'),
(3, 'Empleado'),
(2, 'Lider de Grupo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL,
  `remitente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `estado` varchar(20) DEFAULT 'Pendiente',
  `credencial_entregada` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(200) NOT NULL,
  `correo_electronico` varchar(200) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `credencial_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `correo_electronico`, `contrasena`, `rol_id`, `grupo_id`, `credencial_admin_id`) VALUES
(16, 'andres', 'andres@gmail.com', '$2y$10$wrw5uuYqQuBPijnSYn.N5eiQGLN7fgGP98KaZibMesmXgkiCAYEqS', 2, 1, NULL),
(17, 'alvaro', 'alvaro@gmail.com', '$2y$10$alt2olc1.RfzIGOzdTP.Bez8.k788GCrFdy4IRZRa/cW.8NTwbk0G', 3, 1, NULL),
(18, 'eduardo', 'eduardo@gmail.com', '$2y$10$j1pI/TsPB1N2qbgwf2iuwuW06PPBLNTnJtx6d6KBAgZrmzV8UFR3.', 2, 2, NULL),
(19, 'enguaima', 'enguaima@gmail.com', '$2y$10$ey06rsJn62cbzBXLIR1yoOpguOzONeJNBiLIWrcMF11JkRr.A9aS6', 3, 2, NULL),
(20, 'mister goku', 'goku@gmail.com', '$2y$10$BjXIKUgpoxxAvVsET6/PEOGdBpmQBE1JJ6W0q2oHYBwM1pkghM/oe', 1, NULL, 4),
(21, 'fernando', 'fernandoruiz@gmail.com', '$2y$10$sL2bHMC5W0LRZ8/LYwPYaeIcE3tlhj9CCOtz.m2UJGsJrVUurJB1O', 1, NULL, 5),
(22, 'francibel', 'francibel@gmail.com', '$2y$10$fprQ9QWVBvYHnWX/VZxgeOtSnTq7C0FBc5M7xlapgdiNwXCNdvwGS', 1, NULL, 6),
(25, 'laura', 'laura@gmail.com', '$2y$10$M0McqaujQ20B.JjHkeUrvOjgtfq5p44rCiqP9SeCQwUNXty42lgjW', 1, NULL, 8);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `credenciales`
--
ALTER TABLE `credenciales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_grupo` (`nombre_grupo`),
  ADD KEY `credencial_grupo_id` (`credencial_grupo_id`),
  ADD KEY `fk_grupo_lider` (`lider_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD KEY `rol_id` (`rol_id`),
  ADD KEY `grupo_id` (`grupo_id`),
  ADD KEY `credencial_admin_id` (`credencial_admin_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `credenciales`
--
ALTER TABLE `credenciales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `fk_grupo_lider` FOREIGN KEY (`lider_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`credencial_grupo_id`) REFERENCES `credenciales` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`credencial_admin_id`) REFERENCES `credenciales` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
