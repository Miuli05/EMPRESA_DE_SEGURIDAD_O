<?php
require_once __DIR__ . '/../controladores/vigilanteController.php';

$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioId = (int) $_POST['usuario_id'];
    $codigo = $_POST['codigo'];

    try {
        $controlador = new VigilanteController();
        $controlador->registrar($usuarioId, $codigo);

    
        header('Location: index.php?ok=1');
        exit;
    } catch (InvalidArgumentException | DomainException $e) {
        $mensaje = $e->getMessage();
    } catch (PDOException $e) {
        $mensaje = 'Error con la base de datos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar vigilante</title>
</head>
<body>
    <h1>Registrar vigilante</h1>

    <?php if ($mensaje !== ''): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>ID de usuario:</label>
        <input type="number" name="usuario_id" required>

        <label>Código:</label>
        <input type="text" name="codigo" required>

        <button type="submit">Guardar</button>
    </form>

    <a href="index.php">Volver</a>
</body>
</html>