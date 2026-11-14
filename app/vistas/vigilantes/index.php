<?php
require_once __DIR__ . '/../../controladores/vigilanteController.php';

$controlador = new VigilanteController();
$vigilantes = [];
$error = '';

try {
    $vigilantes = $controlador->listar();
} catch (PDOException $e) {
    $error = 'No se pudo conectar con la base de datos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vigilantes</title>
</head>
<body>
    <h1>Vigilantes</h1>

    <?php if (isset($_GET['ok'])): ?>
        <p>Vigilante registrado correctamente.</p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <a href="crear.php">Registrar vigilante</a>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>ID de usuario</th>
            <th>Código</th>
            <th>Estado</th>
        </tr>
        <?php foreach ($vigilantes as $fila): ?>
            <tr>
                <td><?= htmlspecialchars($fila['id']) ?></td>
                <td><?= htmlspecialchars($fila['usuario_id']) ?></td>
                <td><?= htmlspecialchars($fila['codigo']) ?></td>
                <td><?= htmlspecialchars($fila['estado']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>