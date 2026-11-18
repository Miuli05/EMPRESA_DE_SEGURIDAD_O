<?php
require_once __DIR__ . '/../modelos/Asignacion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_asignacion = $_POST['fecha_asignacion'] ?? '';
    $turno_id = isset($_POST['turno_id']) ? (int)$_POST['turno_id'] : 0;
    $vigilante_id = isset($_POST['vigilante_id']) ? (int)$_POST['vigilante_id'] : 0;

    $asignacion = new Asignacion();

    try {
        if (empty($fecha_asignacion) || $turno_id <= 0 || $vigilante_id <= 0) {
            throw new Exception("Todos los campos son obligatorios.");
        }

        if ($asignacion->validarCruces($fecha_asignacion, $turno_id, $vigilante_id)) {
            throw new Exception("El vigilante ya está asignado a este turno en la fecha seleccionada.");
        }

        $asignacion->asignarVigilante($fecha_asignacion, $turno_id, $vigilante_id);

        header("Location: ../vistas/formulario_asignacion.php?mensaje=asignado");
        exit();

    } catch (Exception $e) {
        $error = urlencode($e->getMessage());
        header("Location: ../vistas/formulario_asignacion.php?error=" . $error);
        exit();
    }
}
?>