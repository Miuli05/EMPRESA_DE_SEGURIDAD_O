
<?php
class Conn
{
    private ?PDO $conexion = null;

    public function conectar(): PDO
    {
        if ($this->conexion !== null) {
            return $this->conexion;
        }

        $host = getenv('DB_HOST') ?: 'localhost';
        $database = getenv('DB_NAME') ?: 'bd_seguridad';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

        $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";

        $this->conexion = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);

        $this->conexion->exec("SET time_zone = '-05:00'");

        return $this->conexion;
    }

    public function cerrar(): void
    {
        $this->conexion = null;
    }
}
