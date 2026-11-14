<?php
require_once __DIR__ . '/ModeloBase.php';

class Puesto extends ModeloBase
{
    public function listar(): array
    {
        $sql = 'SELECT * FROM puestos ORDER BY id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM puestos WHERE id = :id');
        $consulta->execute(['id' => $id]);
        $fila = $consulta->fetch();
        return $fila === false ? null : $fila;
    }

    public function registrar(string $nombre, string $ubicacion, int $supervisorId): int
    {
        $consulta = $this->db->prepare(
            'INSERT INTO puestos (nombre, ubicacion, supervisor_id) VALUES (:n, :u, :s)'
        );
        $consulta->execute(['n' => $nombre, 'u' => $ubicacion, 's' => $supervisorId]);
        return (int) $this->db->lastInsertId();
    }

}
