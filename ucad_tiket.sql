-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-04-2026 a las 05:07:59
-- Versión del servidor: 8.0.45
-- Versión de PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ucad_tiket`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adjuntos`
--

CREATE TABLE `adjuntos` (
  `id` int NOT NULL COMMENT 'ID adjunto',
  `ticket_id` int NOT NULL COMMENT 'Ticket',
  `ruta_archivo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Ruta del archivo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Adjuntos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones_ticket`
--

CREATE TABLE `asignaciones_ticket` (
  `id` int NOT NULL COMMENT 'ID asignación',
  `ticket_id` int NOT NULL COMMENT 'Ticket',
  `tecnico_id` int NOT NULL COMMENT 'Técnico asignado',
  `fecha_asignacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha asignación',
  `activo` tinyint(1) DEFAULT '1' COMMENT 'Asignación activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Asignaciones de tickets';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int NOT NULL COMMENT 'ID auditoría',
  `usuario_id` int DEFAULT NULL COMMENT 'Usuario',
  `entidad` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Entidad afectada',
  `entidad_id` int DEFAULT NULL COMMENT 'ID del registro',
  `accion` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Acción',
  `valor_anterior` json DEFAULT NULL COMMENT 'Datos antes',
  `valor_nuevo` json DEFAULT NULL COMMENT 'Datos después',
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Auditoría';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int NOT NULL COMMENT 'ID de categoría',
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Categoría del ticket'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Categorías';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int NOT NULL COMMENT 'ID comentario',
  `ticket_id` int NOT NULL COMMENT 'Ticket',
  `usuario_id` int NOT NULL COMMENT 'Usuario',
  `mensaje` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Mensaje',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Comentarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuraciones`
--

CREATE TABLE `configuraciones` (
  `id` int NOT NULL COMMENT 'ID configuración',
  `clave` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `valor` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Configuraciones';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int NOT NULL COMMENT 'Identificador del departamento',
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nombre del departamento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Departamentos de la organización';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_ticket`
--

CREATE TABLE `estados_ticket` (
  `id` int NOT NULL COMMENT 'ID del estado del ticket',
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Estado del ticket',
  `es_final` tinyint(1) DEFAULT '0' COMMENT 'Indica si es estado final'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Estados de tickets';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_ticket`
--

CREATE TABLE `historial_ticket` (
  `id` int NOT NULL COMMENT 'ID historial',
  `ticket_id` int NOT NULL COMMENT 'Ticket',
  `cambiado_por` int DEFAULT NULL COMMENT 'Usuario que cambia',
  `estado_anterior` int DEFAULT NULL COMMENT 'Estado anterior',
  `estado_nuevo` int DEFAULT NULL COMMENT 'Estado nuevo',
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Historial de tickets';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int NOT NULL COMMENT 'ID notificación',
  `usuario_id` int NOT NULL COMMENT 'Usuario',
  `mensaje` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Mensaje',
  `leido` tinyint(1) DEFAULT '0' COMMENT 'Leído',
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Notificaciones';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridades`
--

CREATE TABLE `prioridades` (
  `id` int NOT NULL COMMENT 'ID de prioridad',
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nombre de la prioridad',
  `nivel` int NOT NULL COMMENT 'Nivel de prioridad'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Prioridades de tickets';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL COMMENT 'Identificador único del rol',
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nombre del rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Roles del sistema';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sla_ticket`
--

CREATE TABLE `sla_ticket` (
  `id` int NOT NULL COMMENT 'ID SLA',
  `ticket_id` int NOT NULL COMMENT 'Ticket',
  `fecha_limite_respuesta` datetime DEFAULT NULL COMMENT 'Límite respuesta',
  `fecha_limite_resolucion` datetime DEFAULT NULL COMMENT 'Límite resolución',
  `respondido_en` datetime DEFAULT NULL COMMENT 'Fecha respuesta',
  `resuelto_en` datetime DEFAULT NULL COMMENT 'Fecha resolución'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='SLA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` int NOT NULL COMMENT 'ID del ticket',
  `titulo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Título',
  `descripcion` text COLLATE utf8mb4_general_ci COMMENT 'Descripción',
  `usuario_id` int NOT NULL COMMENT 'Usuario creador',
  `estado_id` int NOT NULL COMMENT 'Estado actual',
  `prioridad_id` int NOT NULL COMMENT 'Prioridad',
  `categoria_id` int DEFAULT NULL COMMENT 'Categoría',
  `departamento_id` int DEFAULT NULL COMMENT 'Departamento',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha creación',
  `fecha_actualizacion` timestamp NULL DEFAULT NULL COMMENT 'Última actualización',
  `eliminado_en` timestamp NULL DEFAULT NULL COMMENT 'Soft delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tickets';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL COMMENT 'ID del usuario',
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nombre completo',
  `correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Correo electrónico',
  `usuario` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nombre de usuario',
  `contraseña` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Contraseña encriptada',
  `rol_id` int DEFAULT NULL COMMENT 'Rol del usuario',
  `departamento_id` int DEFAULT NULL COMMENT 'Departamento del usuario',
  `estado` enum('activo','inactivo') COLLATE utf8mb4_general_ci DEFAULT 'activo' COMMENT 'Estado',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha creación',
  `eliminado_en` timestamp NULL DEFAULT NULL COMMENT 'Soft delete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Usuarios';

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adjuntos`
--
ALTER TABLE `adjuntos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indices de la tabla `asignaciones_ticket`
--
ALTER TABLE `asignaciones_ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_asignaciones_ticket` (`ticket_id`),
  ADD KEY `idx_asignaciones_tecnico_activo` (`tecnico_id`,`activo`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_comentarios_ticket` (`ticket_id`),
  ADD KEY `idx_comentarios_ticket_fecha` (`ticket_id`,`fecha_creacion`);

--
-- Indices de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados_ticket`
--
ALTER TABLE `estados_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_ticket`
--
ALTER TABLE `historial_ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cambiado_por` (`cambiado_por`),
  ADD KEY `idx_historial_ticket_fecha` (`ticket_id`,`fecha`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notificaciones_usuario` (`usuario_id`),
  ADD KEY `idx_notificaciones_usuario_leido` (`usuario_id`,`leido`);

--
-- Indices de la tabla `prioridades`
--
ALTER TABLE `prioridades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sla_ticket`
--
ALTER TABLE `sla_ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sla_ticket` (`ticket_id`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departamento_id` (`departamento_id`),
  ADD KEY `idx_ticket_usuario` (`usuario_id`),
  ADD KEY `idx_ticket_estado` (`estado_id`),
  ADD KEY `idx_ticket_prioridad` (`prioridad_id`),
  ADD KEY `idx_ticket_categoria` (`categoria_id`),
  ADD KEY `idx_tickets_estado_prioridad` (`estado_id`,`prioridad_id`),
  ADD KEY `idx_tickets_estado_fecha` (`estado_id`,`fecha_creacion`),
  ADD KEY `idx_tickets_usuario_estado` (`usuario_id`,`estado_id`),
  ADD KEY `idx_tickets_eliminado` (`eliminado_en`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `rol_id` (`rol_id`),
  ADD KEY `departamento_id` (`departamento_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `adjuntos`
--
ALTER TABLE `adjuntos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID adjunto';

--
-- AUTO_INCREMENT de la tabla `asignaciones_ticket`
--
ALTER TABLE `asignaciones_ticket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID asignación';

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID auditoría';

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID de categoría';

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID comentario';

--
-- AUTO_INCREMENT de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID configuración';

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador del departamento';

--
-- AUTO_INCREMENT de la tabla `estados_ticket`
--
ALTER TABLE `estados_ticket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID del estado del ticket';

--
-- AUTO_INCREMENT de la tabla `historial_ticket`
--
ALTER TABLE `historial_ticket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID historial';

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID notificación';

--
-- AUTO_INCREMENT de la tabla `prioridades`
--
ALTER TABLE `prioridades`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID de prioridad';

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del rol';

--
-- AUTO_INCREMENT de la tabla `sla_ticket`
--
ALTER TABLE `sla_ticket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID SLA';

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID del ticket';

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID del usuario';

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adjuntos`
--
ALTER TABLE `adjuntos`
  ADD CONSTRAINT `adjuntos_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`);

--
-- Filtros para la tabla `asignaciones_ticket`
--
ALTER TABLE `asignaciones_ticket`
  ADD CONSTRAINT `asignaciones_ticket_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
  ADD CONSTRAINT `asignaciones_ticket_ibfk_2` FOREIGN KEY (`tecnico_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `historial_ticket`
--
ALTER TABLE `historial_ticket`
  ADD CONSTRAINT `historial_ticket_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
  ADD CONSTRAINT `historial_ticket_ibfk_2` FOREIGN KEY (`cambiado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `sla_ticket`
--
ALTER TABLE `sla_ticket`
  ADD CONSTRAINT `sla_ticket_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`);

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`estado_id`) REFERENCES `estados_ticket` (`id`),
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`prioridad_id`) REFERENCES `prioridades` (`id`),
  ADD CONSTRAINT `tickets_ibfk_4` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `tickets_ibfk_5` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
