<?php
require_once __DIR__ . '/ModeloBase.php';

class Asignacion extends ModeloBase
{
    public function listar(): array
    {
        $sql = 'SELECT * FROM asignaciones ORDER BY id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM asignaciones WHERE id = :id');
        $consulta->execute(['id' => $id]);
        $fila = $consulta->fetch();
        return $fila === false ? null : $fila;
    }

    public function buscarPorTurno(int $turnoId): array
    {
        $consulta = $this->db->prepare('SELECT * FROM asignaciones WHERE turno_id = :id ORDER BY id DESC');
        $consulta->execute(['id' => $turnoId]);
        return $consulta->fetchAll();
    }

    public function registrar(int $turnoId, int $vigilanteId, ?int $asignadoPor = null): int
    {
        $this->db->beginTransaction();
        try {
            $guardar = $this->db->prepare('SELECT id FROM vigilantes WHERE id = :id AND estado = \'activo\' FOR UPDATE');
            $guardar->execute(['id' => $vigilanteId]);
            if (!$guardar->fetch()) {
                throw new DomainException('El vigilante está inactivo o no existe.');
            }
            $turno = $this->db->prepare('SELECT * FROM turnos WHERE id = :id FOR UPDATE');
            $turno->execute(['id' => $turnoId]);
            $actual = $turno->fetch();
            if (!$actual || $actual['estado'] !== 'pendiente') {
                throw new DomainException('El turno no existe o no está pendiente.');
            }
            $ocupado = $this->db->prepare(
                "SELECT a.id FROM asignaciones a JOIN turnos t ON a.turno_id = t.id " .
                "WHERE a.vigilante_id = :v AND a.estado = 'activa' " .
                "AND t.estado <> 'cancelado' AND t.fecha_inicio < :fin AND t.fecha_fin > :ini LIMIT 1"
            );
            $ocupado->execute([
                'v' => $vigilanteId,
                'fin' => $actual['fecha_fin'],
                'ini' => $actual['fecha_inicio'],
            ]);
            if ($ocupado->fetch()) {
                throw new DomainException('El vigilante tiene un turno que se superpone.');
            }
            $insertar = $this->db->prepare(
                'INSERT INTO asignaciones (turno_id, vigilante_id, asignado_por) VALUES (:t, :v, :autor)'
            );
            $insertar->execute(['t' => $turnoId, 'v' => $vigilanteId, 'autor' => $asignadoPor]);
            $id = (int) $this->db->lastInsertId();
            $actualizar = $this->db->prepare("UPDATE turnos SET estado = 'cubierto' WHERE id = :id");
            $actualizar->execute(['id' => $turnoId]);
            $this->db->commit();
            return $id;
        } catch (Throwable $error) {
            $this->db->rollBack();
            throw $error;
        }
    }

}
