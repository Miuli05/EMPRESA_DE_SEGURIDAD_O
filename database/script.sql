CREATE DATABASE IF NOT EXISTS bd_seguridad
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bd_seguridad;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  correo VARCHAR(160) NOT NULL UNIQUE,
  clave_hash VARCHAR(255) NOT NULL,
  rol ENUM('administrador','coordinador','vigilante','supervisor') NOT NULL,
  activo BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS vigilantes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL UNIQUE,
  codigo VARCHAR(30) NOT NULL UNIQUE,
  estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS puestos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  ubicacion VARCHAR(250) NOT NULL,
  supervisor_id INT NOT NULL,
  activo BOOLEAN NOT NULL DEFAULT TRUE,
  FOREIGN KEY (supervisor_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS turnos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  puesto_id INT NOT NULL,
  fecha_inicio DATETIME NOT NULL,
  fecha_fin DATETIME NOT NULL,
  estado ENUM('pendiente','cubierto','cancelado') NOT NULL DEFAULT 'pendiente',
  creado_por INT NULL,
  FOREIGN KEY (puesto_id) REFERENCES puestos(id),
  FOREIGN KEY (creado_por) REFERENCES usuarios(id),
  CONSTRAINT chk_fechas_turno CHECK (fecha_fin > fecha_inicio),
  INDEX idx_turnos_puesto_fecha (puesto_id, fecha_inicio, fecha_fin)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS asignaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  turno_id INT NOT NULL,
  vigilante_id INT NOT NULL,
  fecha_asignacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('activa','retirada') NOT NULL DEFAULT 'activa',
  asignado_por INT NULL,
  FOREIGN KEY (turno_id) REFERENCES turnos(id),
  FOREIGN KEY (vigilante_id) REFERENCES vigilantes(id),
  FOREIGN KEY (asignado_por) REFERENCES usuarios(id),
  INDEX idx_asignaciones_vigilante (vigilante_id, estado)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS incidencias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  turno_id INT NOT NULL,
  vigilante_id INT NOT NULL,
  tipo VARCHAR(100) NOT NULL,
  descripcion TEXT NOT NULL,
  fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('abierta','en_seguimiento','cerrada') NOT NULL DEFAULT 'abierta',
  FOREIGN KEY (turno_id) REFERENCES turnos(id),
  FOREIGN KEY (vigilante_id) REFERENCES vigilantes(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS seguimientos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  incidencia_id INT NOT NULL,
  supervisor_id INT NOT NULL,
  comentario TEXT NOT NULL,
  fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (incidencia_id) REFERENCES incidencias(id),
  FOREIGN KEY (supervisor_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notificaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  turno_id INT NOT NULL,
  destinatario_id INT NOT NULL,
  fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  leida BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (turno_id) REFERENCES turnos(id),
  FOREIGN KEY (destinatario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;
