<?php
require_once __DIR__ . '/../config/Conn.php';

abstract class ModeloBase
{
    protected PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? (new Conn())->conectar();
    }
}
