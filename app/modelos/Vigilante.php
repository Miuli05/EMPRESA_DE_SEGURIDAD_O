<?php
require_once __DIR__ . '/ModeloBase.php';

class Vigilante extends ModeloBase
{
    public function listar(): array
    {
        $sql = 'SELECT * FROM vigilantes ORDER BY id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM vigilantes WHERE id = :id');
        $consulta->execute(['id' => $id]);
        $fila = $consulta->fetch();
        return $fila === false ? null : $fila;
    }

    public function actualizarEstado(int $id, string $estado): bool
    {
        if (!in_array($estado, ['activo', 'inactivo'], true)) {
            throw new InvalidArgumentException('Estado no válido.');
        }
        $consulta = $this->db->prepare('UPDATE vigilantes SET estado = :estado WHERE id = :id');
        $consulta->execute(['id' => $id, 'estado' => $estado]);
        return $consulta->rowCount() > 0;
    }

    public function registrar(int $usuarioId, string $codigo): int
    {
        
        $codigo = trim($codigo);
        if ($usuarioId <= 0 || $codigo === '') {
            throw new InvalidArgumentException('Complete los datos del vigilante.');
        }

        
        $consulta = $this->db->prepare(
            'SELECT id FROM vigilantes WHERE usuario_id = :usuario OR codigo = :codigo'
        );
        $consulta->execute(['usuario' => $usuarioId, 'codigo' => $codigo]);
        if ($consulta->fetch()) {
            throw new DomainException('El usuario o el código ya están registrados.');
        }

        
        $consulta = $this->db->prepare(
            'INSERT INTO vigilantes (usuario_id, codigo) VALUES (:usuario, :codigo)'
        );
        $consulta->execute(['usuario' => $usuarioId, 'codigo' => $codigo]);

        return (int) $this->db->lastInsertId();
    }
}