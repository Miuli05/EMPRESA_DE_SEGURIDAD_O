<?php
require_once __DIR__ . '/app/modelos/IncidenciaModelo.php';

$modeloIncidencia = new Incidencia();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $turnoId = isset($_POST['turno_id']) ? (int) $_POST['turno_id'] : 0;
    $vigilanteId = isset($_POST['vigilante_id']) ? (int) $_POST['vigilante_id'] : 0;
    $tipo = $_POST['tipo'] ?? '';
    $description = $_POST['descripcion'] ?? '';

    try {
        $modeloIncidencia->registrar($turnoId, $vigilanteId, $tipo, $description);
        header('Location: ../vistas/formulario_Incidencia.php?mensaje=registrado');
        exit;
    } catch (Throwable $e) {
        header('Location: ../vistas/formulario_Incidencia.php?mensaje=error&detalle=' . urlencode($e->getMessage()));
        exit;
    }
}