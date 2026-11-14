
<?php
require_once __DIR__ . '/ModeloBase.php';

class Incidencia extends ModeloBase
{
    public function listar(): array
    {
        $sql = 'SELECT * FROM incidencias ORDER BY id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM incidencias WHERE id = :id');
        $consulta->execute(['id' => $id]);

        $fila = $consulta->fetch();

        return $fila === false ? null : $fila;
    }

    public function registrar(int $turnoId, int $vigilanteId, string $tipo, string $descripcion): int
    {
        $tipo = trim($tipo);
        $descripcion = trim($descripcion);

        if ($tipo === '' || $descripcion === '') {
            throw new InvalidArgumentException('Complete todos los campos.');
        }

        $consulta = $this->db->prepare(
            "SELECT id FROM asignaciones
             WHERE turno_id = :turno
             AND vigilante_id = :vigilante
             AND estado = 'activa'
             LIMIT 1"
        );

        $consulta->execute([
            'turno' => $turnoId,
            'vigilante' => $vigilanteId
        ]);

        if (!$consulta->fetch()) {
            throw new DomainException('El vigilante no está asignado a este turno.');
        }

        $consulta = $this->db->prepare(
            'INSERT INTO incidencias
             (turno_id, vigilante_id, tipo, descripcion)
             VALUES (:turno, :vigilante, :tipo, :descripcion)'
        );

        $consulta->execute([
            'turno' => $turnoId,
            'vigilante' => $vigilanteId,
            'tipo' => $tipo,
            'descripcion' => $descripcion
        ]);

        return (int) $this->db->lastInsertId();
    }
}
