
<?php
require_once __DIR__ . '/ModeloBase.php';

class Seguimiento extends ModeloBase
{
    public function listar(): array
    {
        $sql = 'SELECT * FROM seguimientos ORDER BY id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM seguimientos WHERE id = :id');
        $consulta->execute(['id' => $id]);

        $fila = $consulta->fetch();

        return $fila === false ? null : $fila;
    }

    public function listarPorIncidencia(int $incidenciaId): array
    {
        $consulta = $this->db->prepare(
            'SELECT * FROM seguimientos
             WHERE incidencia_id = :id
             ORDER BY fecha_hora DESC'
        );

        $consulta->execute(['id' => $incidenciaId]);

        return $consulta->fetchAll();
    }

    public function registrar(int $incidenciaId, int $supervisorId, string $comentario): int
    {
        $comentario = trim($comentario);

        if ($comentario === '') {
            throw new InvalidArgumentException('Ingrese un comentario.');
        }

        $this->db->beginTransaction();

        try {
            $consulta = $this->db->prepare(
                'SELECT estado FROM incidencias
                 WHERE id = :id FOR UPDATE'
            );

            $consulta->execute(['id' => $incidenciaId]);
            $estado = $consulta->fetchColumn();

            if ($estado === false) {
                throw new DomainException('La incidencia no existe.');
            }

            if (!in_array($estado, ['abierta', 'en_seguimiento'], true)) {
                throw new DomainException('La incidencia no permite nuevos seguimientos.');
            }

            $consulta = $this->db->prepare(
                'INSERT INTO seguimientos
                 (incidencia_id, supervisor_id, comentario)
                 VALUES (:incidencia, :supervisor, :comentario)'
            );

            $consulta->execute([
                'incidencia' => $incidenciaId,
                'supervisor' => $supervisorId,
                'comentario' => $comentario
            ]);

            $id = (int) $this->db->lastInsertId();

            if ($estado === 'abierta') {
                $consulta = $this->db->prepare(
                    "UPDATE incidencias
                     SET estado = 'en_seguimiento'
                     WHERE id = :id"
                );

                $consulta->execute(['id' => $incidenciaId]);
            }

            $this->db->commit();

            return $id;

        } catch (Throwable $error) {
            $this->db->rollBack();
            throw $error;
        }
    }
}
