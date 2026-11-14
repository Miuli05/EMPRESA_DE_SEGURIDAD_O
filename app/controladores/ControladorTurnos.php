<?php

require_once "../modelos/turnos.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $fecha_inicio_raw = $_POST['fecha_inicio']; 
    $fecha_fin_raw = $_POST['fecha_fin'];
    $puesto_id = (int)$_POST['puesto_id'];
    
    $inicio = str_replace('T', ' ', $fecha_inicio_raw) . ':00';
    $fin = str_replace('T', ' ', $fecha_fin_raw) . ':00';

    $turno = new Turno();

   
    try {
        $turno->registrar($puesto_id, $inicio, $fin);
        header("Location: ../vistas/formulario_turno.php?mensaje=registrado");
        exit();
    } catch (Exception $e) {
  
        $error = urlencode($e->getMessage());
        header("Location: ../vistas/formulario_turno.php?error=" . $error);
        exit();
    }
}
?>