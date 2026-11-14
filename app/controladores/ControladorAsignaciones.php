<?php
require_once "../modelos/Asignacion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_asignacion = $_POST['fecha_asignacion'];
    $turno_id = (int)$_POST['turno_id'];
    $vigilante_id = (int)$_POST['vigilante_id'];

    $asignacion = new Asignacion();

    try {
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