<?php
require_once __DIR__ . '/ModeloBase.php';

class Incidencia extends ModeloBase

{
    public function listar(): array
    {
        return $this->listarDetallado();
    }
    
    public function registrar(int $turnoId, int $vigilanteId, string $tipo, string $descripcion): int
    {
        $sql = "INSERT INTO incidencias (turno_id, vigilante_id, tipo, descripcion)
                VALUES (:turno_id, :vigilante_id, :tipo, :descripcion)";
        $consulta = $this->db->prepare($sql);
        $consulta->execute([
            'turno_id'     => $turnoId,
            'vigilante_id' => $vigilanteId,
            'tipo'         => $tipo,
            'descripcion'  => $descripcion
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function actualizarEstado(int $id, string $estado): bool
    {
        $sql = "UPDATE incidencias SET estado = :estado WHERE id = :id";
        $consulta = $this->db->prepare($sql);
        return $consulta->execute([
            'estado' => $estado,
            'id'     => $id
        ]);
    }

    public function listarDetallado(): array
    {
        $sql = "SELECT i.id, i.turno_id, i.vigilante_id, i.tipo, i.descripcion,
                       i.fecha_creacion, i.estado,
                       v.codigo AS codigo_vigilante, u.nombre AS nombre_vigilante,
                       p.nombre AS nombre_puesto
                FROM incidencias i
                INNER JOIN vigilantes v ON i.vigilante_id = v.id
                INNER JOIN usuarios u ON v.usuario_id = u.id
                INNER JOIN turnos t ON i.turno_id = t.id
                INNER JOIN puestos p ON t.puesto_id = p.id
                ORDER BY i.id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function existeDuplicada(int $turnoId, int $vigilanteId, string $tipo): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM incidencias
                WHERE turno_id = :turno_id
                  AND vigilante_id = :vigilante_id
                  AND tipo = :tipo
                  AND estado <> 'cerrada'";
        $consulta = $this->db->prepare($sql);
        $consulta->execute([
            'turno_id'     => $turnoId,
            'vigilante_id' => $vigilanteId,
            'tipo'         => $tipo
        ]);

        $fila = $consulta->fetch();
        return ($fila['total'] ?? 0) > 0;
    }
}