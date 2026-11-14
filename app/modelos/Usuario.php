<?php
require_once __DIR__ . '/ModeloBase.php';

class Usuario extends ModeloBase
{
   public function guardarUsuario(array $datos): bool{
        try {
            $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES (:nombre, :correo, :contrasena)";
            $stmt = $this->db->prepare($sql);
            $passwordHash = password_hash($datos['contrasena'], PASSWORD_BCRYPT);

            return $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':correo' => $datos['correo'],
                ':contrasena' => $passwordHash
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function iniciarSesion(array $datos): false|array {
        try {
            $sql = "SELECT * FROM usuarios WHERE correo = :correo";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':correo' => $datos['correo']]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($datos['contrasena'], $usuario['contrasena'])) {
                return $usuario;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarUsuarios() {
        try{
            $sql = "SELECT id, nombre, email FROM usuarios";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
}
  

