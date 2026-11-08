<?php
require_once __DIR__ . '/ModeloBase.php';

class Usuario extends ModeloBase
{
    public function listar(): array
    {
        $sql = 'SELECT * FROM usuarios ORDER BY id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM usuarios WHERE id = :id');
        $consulta->execute(['id' => $id]);
        $fila = $consulta->fetch();
        return $fila === false ? null : $fila;
    }

}
