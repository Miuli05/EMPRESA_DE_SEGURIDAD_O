<?php
require_once "../modelos/turnos.php";
$turnoModel = new Turno();

$listaTurnos = $turnoModel->listar(); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Prueba de Turnos</title>
</head>
<body>
    <h2>Registro de Turnos</h2>

  
    <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'registrado'): ?>
        <p style="color: green;"><b>Turno registrado.</b></p>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <p style="color: red;"><b>Error:</b> <?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="../controladores/ControladorTurnos.php" method="POST">
        <label>Inicio del turno:</label>
        <input type="datetime-local" name="fecha_inicio" required><br><br>

        <label>Fin del turno:</label>
        <input type="datetime-local" name="fecha_fin" required><br><br>

        <label>ID del Puesto:</label>
        <input type="number" name="puesto_id" placeholder="Ej: 1" required><br><br>

        <input type="submit" value="Guardar Turno">
    </form>

    <hr>

    <h3>Datos en la Tabla</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>ID Puesto</th>
            <th>Estado</th>
        </tr>
        <?php if(!empty($listaTurnos)): ?>
            <?php foreach($listaTurnos as $row): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['fecha_inicio']; ?></td>
                    <td><?php echo $row['fecha_fin']; ?></td>
                    <td><?php echo $row['puesto_id']; ?></td>
                    <td><?php echo isset($row['estado']) ? $row['estado'] : 'N/A'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Tabla vacía</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>