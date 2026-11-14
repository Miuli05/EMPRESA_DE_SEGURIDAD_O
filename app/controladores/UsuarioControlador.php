<?php
require_once "../modelos/Usuario.php";
class UsuarioControlador {
    
    private Usuario $usuarioModelo;

    public function __construct(PDO $db) {
        $this->usuarioModelo = new Usuario($db);
    }

    public function registrarUsuario(array $datos){
        if ($this->usuarioModelo->guardarUsuario($datos)) {
            return ['success' => true, 'message' => 'Usuario registrado exitosamente.'];
        } else {
            return ['success' => false, 'message' => 'Error al registrar el usuario.'];
        }
    }

    private function validarDatos (array $datos) {
        if (isset($datos['nombre'], $datos['correo'], $datos['contrasena']) &&
            !empty($datos['nombre']) && !empty($datos['correo']) && !empty($datos['contrasena'])) {
            return true;
        }
        return false;
    }

    public function iniciarSesion(array $datos) {
        if ($this->validarDatos($datos)) {
            $usuario = $this->usuarioModelo->iniciarSesion($datos);
            if ($usuario) {
                return ['success' => true, 'message' => 'Inicio de sesión exitoso.', 'usuario' => $usuario];
            } else {
                return ['success' => false, 'message' => 'Correo o contraseña incorrectos.'];
            }
        } else {
            return ['success' => false, 'message' => 'Datos incompletos.'];
        }
    }

    public function listarUsuarios() {
        $usuarios = $this->usuarioModelo->listarUsuarios();
        if ($usuarios !== false) {
            return ['success' => true, 'usuarios' => $usuarios];
        } else {
            return ['success' => false, 'message' => 'Error al listar los usuarios.'];
        }
    }
}

