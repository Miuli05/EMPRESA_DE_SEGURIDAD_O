<?php
require_once __DIR__ . '/ModeloBase.php';

class Usuario extends ModeloBase
{
    public function registrar(array $datos): bool
    {
        try {
            $sql = "INSERT INTO usuarios (nombre, correo, clave_hash, rol, activo)
                    VALUES (:nombre, :correo, :clave_hash, :rol, :activo)";
            $stmt = $this->db->prepare($sql);

            $claveHash = password_hash($datos['password'], PASSWORD_BCRYPT);

            return $stmt->execute([
                ':nombre'     => trim($datos['nombre']),
                ':correo'     => trim($datos['correo']),
                ':clave_hash' => $claveHash,
                ':rol'        => $datos['rol'],
                ':activo'     => 1
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function iniciarSesion(string $correo, string $password): array|false
    {
        try {
            $sql = "SELECT id, nombre, correo, clave_hash, rol, activo
                    FROM usuarios
                    WHERE correo = :correo AND activo = 1
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':correo' => trim($correo)]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['clave_hash'])) {
                unset($usuario['clave_hash']);
                return $usuario;
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarUsuario(): array
    {
        try {
            $sql = "SELECT nombre, rol, activo FROM usuarios ORDER BY id DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function listar(): array
    {
        return $this->listarUsuario();
    }
}