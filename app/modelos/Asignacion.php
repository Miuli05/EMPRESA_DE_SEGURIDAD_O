<?php
require_once "../config/Conn.php"; 

class Asignacion {
    public function asignarVigilante($fecha_asignacion, $id_turno, $id_vigilante) {
        $c = new Conn();
        $con = $c->conectar();
        
        $sql = "INSERT INTO asignaciones (fecha_asignacion, id_turno, id_vigilante) 
                VALUES (:fecha, :turno, :vigilante)";
        $stmt = $con->prepare($sql);
        
        $stmt->bindParam(':fecha', $fecha_asignacion);
        $stmt->bindParam(':turno', $id_turno);
        $stmt->bindParam(':vigilante', $id_vigilante);
        
        $stmt->execute();
        $c->cerrar();
    }

    public function validarCruces($id_vigilante, $id_turno) {
        $c = new Conn();
        $con = $c->conectar();
        
        $sql = "SELECT COUNT(*) as cruces FROM asignaciones 
                WHERE id_vigilante = :vigilante AND id_turno = :turno";
        $stmt = $con->prepare($sql);
        
        $stmt->bindParam(':vigilante', $id_vigilante);
        $stmt->bindParam(':turno', $id_turno);
        
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $c->cerrar();
        
        return $resultado['cruces'] > 0; 
    }
}
?>