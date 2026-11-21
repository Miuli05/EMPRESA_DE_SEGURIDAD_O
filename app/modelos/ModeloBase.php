<?php
require_once __DIR__ . '/../config/Conn.php';

class ModeloBase
{
    protected PDO $db;
    protected Conn $conexion;

    public function __construct()
    {
        $this->conexion = new Conn();
        $this->db = $this->conexion->conectar();
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function listarDeTabla(string $tabla, string $orden = 'id DESC'): array
    {
        $sql = "SELECT * FROM {$tabla} ORDER BY {$orden}";
        $consulta = $this->db->query($sql);
        return $consulta->fetchAll();
    }

    public function eliminarDeTabla(string $tabla, int $id): bool
    {
        $consulta = $this->db->prepare("DELETE FROM {$tabla} WHERE id = :id");
        return $consulta->execute(['id' => $id]);
    }
}