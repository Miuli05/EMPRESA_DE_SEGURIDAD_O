<?php
// Asegúrate de que la ruta apunte correctamente a tu nuevo turnos.php
require_once "../modelos/turnos.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recibir los datos
    $fecha_inicio_raw = $_POST['fecha_inicio']; 
    $fecha_fin_raw = $_POST['fecha_fin'];
    $puesto_id = (int)$_POST['puesto_id'];
    
    // 2. Darles el formato exacto 'Y-m-d H:i:s' que pide tu modelo
    // El input datetime-local envía "2026-11-14T08:30", lo cambiamos a "2026-11-14 08:30:00"
    $inicio = str_replace('T', ' ', $fecha_inicio_raw) . ':00';
    $fin = str_replace('T', ' ', $fecha_fin_raw) . ':00';

    $turno = new Turno();

    // 3. Intentar registrar capturando las excepciones del modelo
    try {
        $turno->registrar($puesto_id, $inicio, $fin);
        // Si sale bien, redirige con éxito
        header("Location: ../vistas/formulario_turno.php?mensaje=registrado");
        exit();
    } catch (Exception $e) {
        // Si el modelo lanza un error (ej. "Ya existe un turno en ese horario"), lo enviamos a la vista
        $error = urlencode($e->getMessage());
        header("Location: ../vistas/formulario_turno.php?error=" . $error);
        exit();
    }
}
?>