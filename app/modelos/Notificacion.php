<?php
require_once __DIR__ . '/ModeloBase.php';

class Notificacion extends ModeloBase
{
    public function listar(): array
    {
        $sql = 'SELECT * FROM notificaciones ORDER BY id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM notificaciones WHERE id = :id');
        $consulta->execute(['id' => $id]);
        $fila = $consulta->fetch();
        return $fila === false ? null : $fila;
    }

    public function listarPendientes(int $destinatarioId): array
    {
        $consulta = $this->db->prepare(
            'SELECT * FROM notificaciones WHERE destinatario_id = :id AND leida = 0 ORDER BY fecha_hora DESC'
        );
        $consulta->execute(['id' => $destinatarioId]);
        return $consulta->fetchAll();
    }

    public function marcarLeida(int $id): bool
    {
        $consulta = $this->db->prepare('UPDATE notificaciones SET leida = 1 WHERE id = :id AND leida = 0');
        $consulta->execute(['id' => $id]);
        return $consulta->rowCount() > 0;
    }

}
