<?php
require_once __DIR__ . '/../modelos/Incidencia.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? 'registrar';
    $incidencia = new Incidencia();

    try {
        if ($accion === 'registrar') {
            $turnoId     = isset($_POST['turno_id']) ? (int)$_POST['turno_id'] : 0;
            $vigilanteId = isset($_POST['vigilante_id']) ? (int)$_POST['vigilante_id'] : 0;
            $tipo        = trim($_POST['tipo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');

            if ($turnoId <= 0 || $vigilanteId <= 0 || empty($tipo) || empty($descripcion)) {
                throw new Exception("Todos los campos son obligatorios.");
            }

            if (strlen($descripcion) < 10) {
                throw new Exception("La descripción debe tener al menos 10 caracteres.");
            }

            if ($incidencia->existeDuplicada($turnoId, $vigilanteId, $tipo)) {
                throw new Exception("Ya existe una incidencia abierta de este tipo para el vigilante en este turno.");
            }

            $incidencia->registrar($turnoId, $vigilanteId, $tipo, $descripcion);
            header("Location: ../vistas/formulario_incidencia.php?mensaje=registrado");
            exit();

        } elseif ($accion === 'cambiar_estado') {
            $id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $estado = $_POST['estado'] ?? '';
            $estadosValidos = ['abierta', 'en_seguimiento', 'cerrada'];

            if ($id <= 0 || !in_array($estado, $estadosValidos)) {
                throw new Exception("Datos no válidos para actualizar el estado.");
            }

            $incidencia->actualizarEstado($id, $estado);
            header("Location: ../vistas/formulario_incidencia.php?mensaje=actualizado");
            exit();

        } elseif ($accion === 'eliminar') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if ($id <= 0) {
                throw new Exception("ID de incidencia no válido.");
            }

            $incidencia->eliminarDeTabla('incidencias', $id);
            header("Location: ../vistas/formulario_incidencia.php?mensaje=eliminado");
            exit();
        }

    } catch (Exception $e) {
        $error = urlencode($e->getMessage());
        header("Location: ../vistas/formulario_incidencia.php?error=" . $error);
        exit();
    }
}