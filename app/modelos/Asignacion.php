<?php
require_once __DIR__ . '/../config/Conn.php';

class Asignacion {
    
    public function asignarVigilante($fecha_asignacion, $turno_id, $vigilante_id) {
        $c = new Conn();
        $con = $c->conectar();
        
        $sql = "INSERT INTO asignaciones (fecha_asignacion, turno_id, vigilante_id) 
                VALUES (:fecha, :turno, :vigilante)";
        $stmt = $con->prepare($sql);
        
        $stmt->bindParam(':fecha', $fecha_asignacion);
        $stmt->bindParam(':turno', $turno_id);
        $stmt->bindParam(':vigilante', $vigilante_id);
        
        $stmt->execute();
        $c->cerrar();
    }

    public function validarCruces($fecha_asignacion, $turno_id, $vigilante_id) {
        $c = new Conn();
        $con = $c->conectar();
        
        $sql = "SELECT COUNT(*) as cruces FROM asignaciones 
                WHERE vigilante_id = :vigilante AND turno_id = :turno AND fecha_asignacion = :fecha";
        $stmt = $con->prepare($sql);
        
        $stmt->bindParam(':vigilante', $vigilante_id);
        $stmt->bindParam(':turno', $turno_id);
        $stmt->bindParam(':fecha', $fecha_asignacion);
        
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $c->cerrar();
        
        return $resultado['cruces'] > 0;
    }

    public function listar(): array {
        $c = new Conn();
        $con = $c->conectar();
        $sql = "SELECT * FROM asignaciones ORDER BY id DESC";
        $stmt = $con->query($sql);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $c->cerrar();
        return $resultado;
    }
}
?>