
<?php
require_once __DIR__ . '/ModeloBase.php';

class Turno extends ModeloBase
{
    public function listar(): array
    {
        $sql = 'SELECT * FROM turnos ORDER BY id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM turnos WHERE id = :id');
        $consulta->execute(['id' => $id]);

        $fila = $consulta->fetch();

        return $fila === false ? null : $fila;
    }

    public function listarPorFecha(string $fecha): array
    {
        $consulta = $this->db->prepare(
            'SELECT * FROM turnos WHERE DATE(fecha_inicio) = :fecha ORDER BY fecha_inicio'
        );

        $consulta->execute(['fecha' => $fecha]);

        return $consulta->fetchAll();
    }

    public function registrar(int $puestoId, string $inicio, string $fin, ?int $creadoPor = null): int
    {
        $fechaInicio = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $inicio);
        $fechaFin = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $fin);

        if (
            !$fechaInicio ||
            !$fechaFin ||
            $fechaInicio->format('Y-m-d H:i:s') !== $inicio ||
            $fechaFin->format('Y-m-d H:i:s') !== $fin ||
            $fechaFin <= $fechaInicio
        ) {
            throw new InvalidArgumentException('Las fechas del turno no son válidas.');
        }

        $this->db->beginTransaction();

        try {
            $consulta = $this->db->prepare(
                'SELECT id FROM puestos WHERE id = :id AND activo = 1 FOR UPDATE'
            );

            $consulta->execute(['id' => $puestoId]);

            if (!$consulta->fetch()) {
                throw new DomainException('El puesto no existe o está inactivo.');
            }

            $consulta = $this->db->prepare(
                "SELECT id FROM turnos
                 WHERE puesto_id = :puesto
                 AND estado <> 'cancelado'
                 AND fecha_inicio < :fin
                 AND fecha_fin > :inicio
                 LIMIT 1"
            );

            $consulta->execute([
                'puesto' => $puestoId,
                'inicio' => $inicio,
                'fin' => $fin
            ]);

            if ($consulta->fetch()) {
                throw new DomainException('Ya existe un turno en ese horario.');
            }

            $consulta = $this->db->prepare(
                'INSERT INTO turnos
                 (puesto_id, fecha_inicio, fecha_fin, creado_por)
                 VALUES (:puesto, :inicio, :fin, :autor)'
            );

            $consulta->execute([
                'puesto' => $puestoId,
                'inicio' => $inicio,
                'fin' => $fin,
                'autor' => $creadoPor
            ]);

            $id = (int) $this->db->lastInsertId();

            $this->db->commit();

            return $id;

        } catch (Throwable $error) {
            $this->db->rollBack();
            throw $error;
        }
    }
}
