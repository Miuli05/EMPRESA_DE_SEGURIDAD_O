<?php
session_start();

require_once __DIR__ . '/../modelos/Usuario.php';

$usuarioModelo = new Usuario();
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'registrar') {
        $nombre   = trim($_POST['nombre'] ?? '');
        $correo   = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol      = $_POST['rol'] ?? 'coordinador';

        $rolesPermitidos = ['administrador', 'coordinador', 'vigilante', 'supervisor'];

        if ($nombre === '' || $correo === '' || $password === '' || !in_array($rol, $rolesPermitidos, true)) {
            $error = 'Completa todos los campos correctamente.';
        } else {
            $guardado = $usuarioModelo->registrar([
                'nombre'   => $nombre,
                'correo'   => $correo,
                'password' => $password,
                'rol'      => $rol
            ]);

            if ($guardado) {
                $mensaje = 'Usuario registrado correctamente en la base de datos.';
            } else {
                $error = 'No se pudo registrar el usuario (verifica si el correo ya existe).';
            }
        }
    }

    if ($accion === 'login') {
        $correo   = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($correo === '' || $password === '') {
            $error = 'Ingresa tu correo y contraseña.';
        } else {
            $sesion = $usuarioModelo->iniciarSesion($correo, $password);

            if ($sesion) {
                $_SESSION['usuario'] = $sesion;
                $mensaje = 'Sesión iniciada como ' . htmlspecialchars($sesion['nombre']) . ' (' . htmlspecialchars($sesion['rol']) . ').';
            } else {
                $error = 'Correo o contraseña incorrectos.';
            }
        }
    }
}

$usuarios = $usuarioModelo->listarUsuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso y Registro de Usuarios</title>
    <style>
    </style>
</head>
<body>

    <div class="bloque">
        <h2>Registro de Usuario</h2>

        <?php if ($mensaje !== ''): ?>
            <p class="ok"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <p class="err"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="accion" value="registrar">

            <div class="campo">
                <label for="reg_nombre">Nombre:</label>
                <input type="text" id="reg_nombre" name="nombre" placeholder="Ej: Juan Pérez" required>
            </div>

            <div class="campo">
                <label for="reg_correo">Correo:</label>
                <input type="email" id="reg_correo" name="correo" placeholder="correo@empresa.com" required>
            </div>

            <div class="campo">
                <label for="reg_pass">Contraseña:</label>
                <input type="password" id="reg_pass" name="password" required>
            </div>

            <div class="campo">
                <label for="reg_rol">Rol:</label>
                <select id="reg_rol" name="rol">
                    <option value="coordinador">Coordinador</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="administrador">Administrador</option>
                    <option value="vigilante">Vigilante</option>
                </select>
            </div>

            <button type="submit">Registrar Usuario</button>
        </form>
    </div>

    <hr>

    <div class="bloque">
        <h2>Iniciar Sesión</h2>
        <form method="post">
            <input type="hidden" name="accion" value="login">

            <div class="campo">
                <label for="log_correo">Correo:</label>
                <input type="email" id="log_correo" name="correo" required>
            </div>

            <div class="campo">
                <label for="log_pass">Contraseña:</label>
                <input type="password" id="log_pass" name="password" required>
            </div>

            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>

    <hr>

    <div class="bloque">
        <h2>Usuarios Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="3">Sin registros todavía.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['nombre']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($u['rol'])) ?></td>
                            <td><?= ((int)$u['activo'] === 1) ? 'Activo' : 'Inactivo' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>