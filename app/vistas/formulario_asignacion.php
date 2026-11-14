<?php
require_once "../modelos/Turnos.php";
require_once "../modelos/Asignacion.php";

$turnoModel = new Turno();
$asignacionModel = new Asignacion();

$turnosDisponibles = $turnoModel->listar;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Asignacion de Vigilantes</title>
</head>
<body>
    <h2>Asignar Vigilante a Turnos</h2>

    <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'asignado'): ?>
        <p style="color: green;"><b>Vigilante asignado al turno.</b></p>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <p style="color: red;"><b>Error:</b> <?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="../controladores/ControladorAsignacion.php" method="POST">
        <label>Fecha de Asignacion:</label>
        <input type="date" name="fecha_asignacion" required><br><br>

        <label>Seleccionar Turno:</label>
        <select name="turno_id" required>
            <option value="">Seleccione un turno</option>
            <?php if($turnosDisponibles): ?>
                <?php foreach($turnosDisponibles as $turno): ?>
                    <option value="<?php echo $turno['id']; ?>">
                        <?php echo "Turno ID: " . $turno['id'] . " | Inicio: " . $turno['fecha_inicio'] . " | Fin: " . $turno['fecha_fin']; ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select><br><br>

        <label>ID del Vigilante:</label>
        <input type="number" name="vigilante_id" placeholder="Ej: 1" required><br><br>

        <input type="submit" value="Registrar Asignacion">
    </form>

    <hr>

    <h3>Datos de Asignaciones</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID Asignacioón</th>
            <th>Fecha de Asignacion</th>
            <th>ID Turno</th>
            <th>ID Vigilante</th>
        </tr>
        <?php
        $c = new Conn();
        $con = $c->conectar();
        $query = $con->query("SELECT * FROM asignaciones ORDER BY id DESC");
        $asignaciones = $query->fetchAll(PDO::FETCH_ASSOC);
        $c->cerrar();
        ?>

        <?php if(!empty($asignaciones)): ?>
            <?php foreach($asignaciones as $row): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['fecha_asignacion']; ?></td>
                    <td><?php echo $row['turno_id']; ?></td>
                    <td><?php echo $row['vigilante_id']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No hay Asignaciones registradas.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>