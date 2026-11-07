<?php
class Conn {
    private $dns = '';
    private $user = '';
    private $password = '';
    private $conexion = null;

    public function __construct() {
        $this->dns = 'mysql:host=localhost;dbname=bd_seguridad;charset=utf8';
        $this->user = 'root';
        $this->password = '';
    }

    public function conectar() {
        try {
            $this->conexion = new PDO($this->dns, $this->user, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
        }
    }

    public function cerrar() {
        $this->conexion = null;
    }



}
?>