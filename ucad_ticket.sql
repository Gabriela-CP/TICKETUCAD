-- =========================================
-- CREACIÓN DE BASE DE DATOS
-- =========================================
CREATE DATABASE ucad_tiket
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE ucad_ticket;

-- =========================================
-- TABLAS BASE
-- =========================================

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único del rol',
    nombre VARCHAR(50) NOT NULL COMMENT 'Nombre del rol'
) ENGINE=InnoDB COMMENT='Roles del sistema';

CREATE TABLE departamentos (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador del departamento',
    nombre VARCHAR(100) NOT NULL COMMENT 'Nombre del departamento'
) ENGINE=InnoDB COMMENT='Departamentos de la organización';

CREATE TABLE estados_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID del estado del ticket',
    nombre VARCHAR(50) NOT NULL COMMENT 'Estado del ticket',
    es_final BOOLEAN DEFAULT FALSE COMMENT 'Indica si es estado final'
) ENGINE=InnoDB COMMENT='Estados de tickets';

CREATE TABLE prioridades (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID de prioridad',
    nombre VARCHAR(50) NOT NULL COMMENT 'Nombre de la prioridad',
    nivel INT NOT NULL COMMENT 'Nivel de prioridad'
) ENGINE=InnoDB COMMENT='Prioridades de tickets';

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID de categoría',
    nombre VARCHAR(100) NOT NULL COMMENT 'Categoría del ticket'
) ENGINE=InnoDB COMMENT='Categorías';

-- =========================================
-- USUARIOS
-- =========================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID del usuario',
    nombre VARCHAR(100) NOT NULL COMMENT 'Nombre completo',
    correo VARCHAR(100) NOT NULL UNIQUE COMMENT 'Correo electrónico',
    usuario VARCHAR(50) NOT NULL COMMENT 'Nombre de usuario',
    contraseña VARCHAR(255) NOT NULL COMMENT 'Contraseña encriptada',
    rol_id INT COMMENT 'Rol del usuario',
    departamento_id INT COMMENT 'Departamento del usuario',
    estado ENUM('activo','inactivo') DEFAULT 'activo' COMMENT 'Estado',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha creación',
    eliminado_en TIMESTAMP NULL COMMENT 'Soft delete',

    FOREIGN KEY (rol_id) REFERENCES roles(id),
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id)
) ENGINE=InnoDB COMMENT='Usuarios';

-- =========================================
-- TICKETS
-- =========================================

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID del ticket',
    titulo VARCHAR(255) NOT NULL COMMENT 'Título',
    descripcion TEXT COMMENT 'Descripción',
    usuario_id INT NOT NULL COMMENT 'Usuario creador',
    estado_id INT NOT NULL COMMENT 'Estado actual',
    prioridad_id INT NOT NULL COMMENT 'Prioridad',
    categoria_id INT COMMENT 'Categoría',
    departamento_id INT COMMENT 'Departamento',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha creación',
    fecha_actualizacion TIMESTAMP NULL COMMENT 'Última actualización',
    eliminado_en TIMESTAMP NULL COMMENT 'Soft delete',

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (estado_id) REFERENCES estados_ticket(id),
    FOREIGN KEY (prioridad_id) REFERENCES prioridades(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id)
) ENGINE=InnoDB COMMENT='Tickets';

-- =========================================
-- ASIGNACIONES
-- =========================================

CREATE TABLE asignaciones_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID asignación',
    ticket_id INT NOT NULL COMMENT 'Ticket',
    tecnico_id INT NOT NULL COMMENT 'Técnico asignado',
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha asignación',
    activo BOOLEAN DEFAULT TRUE COMMENT 'Asignación activa',

    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (tecnico_id) REFERENCES usuarios(id)
) ENGINE=InnoDB COMMENT='Asignaciones de tickets';

-- =========================================
-- COMENTARIOS
-- =========================================

CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID comentario',
    ticket_id INT NOT NULL COMMENT 'Ticket',
    usuario_id INT NOT NULL COMMENT 'Usuario',
    mensaje TEXT NOT NULL COMMENT 'Mensaje',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha',

    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB COMMENT='Comentarios';

-- =========================================
-- ADJUNTOS
-- =========================================

CREATE TABLE adjuntos (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID adjunto',
    ticket_id INT NOT NULL COMMENT 'Ticket',
    ruta_archivo VARCHAR(255) NOT NULL COMMENT 'Ruta del archivo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha',

    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
) ENGINE=InnoDB COMMENT='Adjuntos';

-- =========================================
-- HISTORIAL
-- =========================================

CREATE TABLE historial_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID historial',
    ticket_id INT NOT NULL COMMENT 'Ticket',
    cambiado_por INT COMMENT 'Usuario que cambia',
    estado_anterior INT COMMENT 'Estado anterior',
    estado_nuevo INT COMMENT 'Estado nuevo',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha',

    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (cambiado_por) REFERENCES usuarios(id)
) ENGINE=InnoDB COMMENT='Historial de tickets';

-- =========================================
-- AUDITORÍA
-- =========================================

CREATE TABLE auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID auditoría',
    usuario_id INT COMMENT 'Usuario',
    entidad VARCHAR(50) COMMENT 'Entidad afectada',
    entidad_id INT COMMENT 'ID del registro',
    accion VARCHAR(50) COMMENT 'Acción',
    valor_anterior JSON COMMENT 'Datos antes',
    valor_nuevo JSON COMMENT 'Datos después',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha',

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB COMMENT='Auditoría';

-- =========================================
-- SLA
-- =========================================

CREATE TABLE sla_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID SLA',
    ticket_id INT NOT NULL COMMENT 'Ticket',
    fecha_limite_respuesta DATETIME COMMENT 'Límite respuesta',
    fecha_limite_resolucion DATETIME COMMENT 'Límite resolución',
    respondido_en DATETIME COMMENT 'Fecha respuesta',
    resuelto_en DATETIME COMMENT 'Fecha resolución',

    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
) ENGINE=InnoDB COMMENT='SLA';

-- =========================================
-- NOTIFICACIONES
-- =========================================

CREATE TABLE notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID notificación',
    usuario_id INT NOT NULL COMMENT 'Usuario',
    mensaje TEXT NOT NULL COMMENT 'Mensaje',
    leido BOOLEAN DEFAULT FALSE COMMENT 'Leído',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha',

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB COMMENT='Notificaciones';

-- =========================================
-- CONFIGURACIONES
-- =========================================

CREATE TABLE configuraciones (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID configuración',
    clave VARCHAR(100),
    valor TEXT
) ENGINE=InnoDB COMMENT='Configuraciones';

-- =========================================
-- ÍNDICES SIMPLES
-- =========================================

CREATE INDEX idx_ticket_usuario ON tickets(usuario_id);
CREATE INDEX idx_ticket_estado ON tickets(estado_id);
CREATE INDEX idx_ticket_prioridad ON tickets(prioridad_id);
CREATE INDEX idx_ticket_categoria ON tickets(categoria_id);

CREATE INDEX idx_comentarios_ticket ON comentarios(ticket_id);
CREATE INDEX idx_asignaciones_ticket ON asignaciones_ticket(ticket_id);
CREATE INDEX idx_notificaciones_usuario ON notificaciones(usuario_id);

-- =========================================
-- ÍNDICES COMPUESTOS (OPTIMIZACIÓN)
-- =========================================

CREATE INDEX idx_tickets_estado_prioridad 
ON tickets(estado_id, prioridad_id);

CREATE INDEX idx_tickets_estado_fecha 
ON tickets(estado_id, fecha_creacion);

CREATE INDEX idx_tickets_usuario_estado 
ON tickets(usuario_id, estado_id);

CREATE INDEX idx_tickets_eliminado 
ON tickets(eliminado_en);

CREATE INDEX idx_asignaciones_tecnico_activo 
ON asignaciones_ticket(tecnico_id, activo);

CREATE INDEX idx_comentarios_ticket_fecha 
ON comentarios(ticket_id, fecha_creacion);

CREATE INDEX idx_historial_ticket_fecha 
ON historial_ticket(ticket_id, fecha);

CREATE INDEX idx_sla_ticket 
ON sla_ticket(ticket_id);

CREATE INDEX idx_notificaciones_usuario_leido 
ON notificaciones(usuario_id, leido);