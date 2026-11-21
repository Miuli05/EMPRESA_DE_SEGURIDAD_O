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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Módulo de Incidencias</h2>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            if ($_GET['mensaje'] === 'registrado') echo "Incidencia registrada correctamente.";
            elseif ($_GET['mensaje'] === 'actualizado') echo "Estado de la incidencia actualizado.";
            elseif ($_GET['mensaje'] === 'eliminado') echo "Incidencia eliminada correctamente.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">Registrar Nueva Incidencia</div>
        <div class="card-body">
            <form action="../controladores/ControladorIncidencias.php" method="POST">
                <input type="hidden" name="accion" value="registrar">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Turno / Puesto</label>
                        <select name="turno_id" class="form-select" required>
                            <option value="">Seleccione un turno...</option>
                            <?php foreach ($turnos as $t): ?>
                                <option value="<?= $t['id'] ?>">
                                    #<?= $t['id'] ?> - <?= htmlspecialchars($t['puesto']) ?> (<?= $t['fecha_inicio'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Vigilante que reporta</label>
                        <select name="vigilante_id" class="form-select" required>
                            <option value="">Seleccione un vigilante...</option>
                            <?php foreach ($vigilantes as $v): ?>
                                <option value="<?= $v['id'] ?>">
                                    <?= htmlspecialchars($v['codigo']) ?> - <?= htmlspecialchars($v['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo de Incidencia</label>
                        <select name="tipo" class="form-select" required>
                            <option value="">Seleccione tipo...</option>
                            <option value="Intrusión o acceso no autorizado">Intrusión o acceso no autorizado</option>
                            <option value="Falla de equipo / cámaras">Falla de equipo / cámaras</option>
                            <option value="Emergencia médica">Emergencia médica</option>
                            <option value="Novedad en relevo de turno">Novedad en relevo de turno</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Descripción detallada</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describa lo ocurrido..." required></textarea>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Guardar Incidencia</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Listado de Incidencias Registradas</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
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
                </thead>
                <tbody>
                    <?php if (empty($listaIncidencias)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay incidencias registradas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($listaIncidencias as $inc): ?>
                            <tr>
                                <td><?= $inc['id'] ?></td>
                                <td><?= htmlspecialchars($inc['nombre_puesto']) ?> (#<?= $inc['turno_id'] ?>)</td>
                                <td><?= htmlspecialchars($inc['codigo_vigilante']) ?> - <?= htmlspecialchars($inc['nombre_vigilante']) ?></td>
                                <td><?= htmlspecialchars($inc['tipo']) ?></td>
                                <td><?= htmlspecialchars($inc['descripcion']) ?></td>
                                <td><?= $inc['fecha_creacion'] ?></td>
                                <td>
                                    <form action="../controladores/ControladorIncidencias.php" method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="accion" value="cambiar_estado">
                                        <input type="hidden" name="id" value="<?= $inc['id'] ?>">
                                        <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
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
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>