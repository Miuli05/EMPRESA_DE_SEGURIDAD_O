<?php
require_once __DIR__ . '/../modelos/Incidencia.php';
require_once __DIR__ . '/../config/Conn.php';

$incidenciaModel = new Incidencia();
$listaIncidencias = $incidenciaModel->listarDetallado();

$c = new Conn();
$con = $c->conectar();

$turnos = $con->query("SELECT t.id, p.nombre AS puesto, t.fecha_inicio 
                       FROM turnos t 
                       INNER JOIN puestos p ON t.puesto_id = p.id 
                       ORDER BY t.fecha_inicio DESC")->fetchAll(PDO::FETCH_ASSOC);

$vigilantes = $con->query("SELECT v.id, v.codigo, u.nombre 
                           FROM vigilantes v 
                           INNER JOIN usuarios u ON v.usuario_id = u.id 
                           WHERE v.estado = 'activo'")->fetchAll(PDO::FETCH_ASSOC);
$c->cerrar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Incidencias</title>
</head>
<body>
    <h2>Registro de Incidencias</h2>

    <?php if (isset($_GET['mensaje'])): ?>
        <p style="color: green;"><b>
            <?php
            if ($_GET['mensaje'] === 'registrado') echo "Incidencia registrada correctamente.";
            elseif ($_GET['mensaje'] === 'actualizado') echo "Estado de la incidencia actualizado.";
            elseif ($_GET['mensaje'] === 'eliminado') echo "Incidencia eliminada correctamente.";
            ?>
        </b></p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><b>Error:</b> <?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form action="../controladores/ControladorIncidencias.php" method="POST">
        <input type="hidden" name="accion" value="registrar">

        <label>Turno / Puesto:</label>
        <select name="turno_id" required>
            <option value="">Seleccione un turno...</option>
            <?php foreach ($turnos as $t): ?>
                <option value="<?= $t['id'] ?>">
                    #<?= $t['id'] ?> - <?= htmlspecialchars($t['puesto']) ?> (<?= $t['fecha_inicio'] ?>)
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Vigilante que reporta:</label>
        <select name="vigilante_id" required>
            <option value="">Seleccione un vigilante...</option>
            <?php foreach ($vigilantes as $v): ?>
                <option value="<?= $v['id'] ?>">
                    <?= htmlspecialchars($v['codigo']) ?> - <?= htmlspecialchars($v['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Tipo de Incidencia:</label>
        <input type="text" name="tipo" maxlength="100" placeholder="Ingrese el tipo de incidencia..." required><br><br>

        <label>Descripción detallada:</label><br>
        <textarea name="descripcion" rows="3" cols="40" placeholder="Describa lo ocurrido..." required></textarea><br><br>

        <input type="submit" value="Guardar Incidencia">
    </form>

    <hr>

    <h3>Listado de Incidencias</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Puesto (Turno)</th>
            <th>Vigilante</th>
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php if (!empty($listaIncidencias)): ?>
            <?php foreach ($listaIncidencias as $inc): ?>
                <tr>
                    <td><?= $inc['id'] ?></td>
                    <td><?= htmlspecialchars($inc['nombre_puesto']) ?> (#<?= $inc['turno_id'] ?>)</td>
                    <td><?= htmlspecialchars($inc['codigo_vigilante']) ?> - <?= htmlspecialchars($inc['nombre_vigilante']) ?></td>
                    <td><?= htmlspecialchars($inc['tipo']) ?></td>
                    <td><?= htmlspecialchars($inc['descripcion']) ?></td>
                    <td><?= $inc['fecha_creacion'] ?></td>
                    <td>
                        <form action="../controladores/ControladorIncidencias.php" method="POST">
                            <input type="hidden" name="accion" value="cambiar_estado">
                            <input type="hidden" name="id" value="<?= $inc['id'] ?>">
                            <select name="estado" onchange="this.form.submit()">
                                <option value="abierta" <?= $inc['estado'] === 'abierta' ? 'selected' : '' ?>>Abierta</option>
                                <option value="en_seguimiento" <?= $inc['estado'] === 'en_seguimiento' ? 'selected' : '' ?>>En seguimiento</option>
                                <option value="cerrada" <?= $inc['estado'] === 'cerrada' ? 'selected' : '' ?>>Cerrada</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form action="../controladores/ControladorIncidencias.php" method="POST" onsubmit="return confirm('¿Eliminar esta incidencia?');">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= $inc['id'] ?>">
                            <input type="submit" value="Eliminar">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No hay incidencias registradas.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>