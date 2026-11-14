
<?php
$h = static fn($valor) => htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$modulo = $modulos[$seleccionado];
$filas = $datos['filas'];
$puedeRegistrar = $seleccionado === 'puestos';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $h($modulo['titulo']) ?> | Seguridad Privada O</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7fb;
            color: #172436;
        }

        header {
            background: #12253d;
            color: white;
            padding: 22px 5%;
        }

        h1 {
            margin: 0;
            font-size: 26px;
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 16px 5%;
        }

        nav a {
            padding: 10px 14px;
            background: white;
            color: #153a61;
            text-decoration: none;
            border: 1px solid #ced6df;
            border-radius: 6px;
        }

        nav a[aria-current="page"] {
            background: #173e65;
            color: white;
        }

        main {
            margin: 0 5% 45px;
        }

        section {
            background: white;
            padding: 22px;
            margin: 16px 0;
            border: 1px solid #e5e9f0;
            border-radius: 10px;
            overflow-x: auto;
        }

        form, .campos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 14px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 11px;
            margin-top: 6px;
            border: 1px solid #adb8c6;
            border-radius: 6px;
        }

        input:disabled {
            background: #f0f2f5;
        }

        input[type="hidden"] {
            display: none;
        }

        button {
            background: #173e65;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        button:hover {
            background: #245787;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e7ecf2;
            text-align: left;
        }

        th {
            background: #f0f5fa;
        }

        .nota {
            font-size: 14px;
            color: #526275;
        }

        .alerta {
            background: #fff5d7;
            padding: 12px;
            color: #654900;
            border-radius: 6px;
        }

        .mensaje {
            background: #e9f5ec;
            color: #24643c;
            padding: 12px;
            border-radius: 6px;
        }

        .encabezado {
            font-size: 13px;
            color: #c8d9f2;
        }

        .vacio {
            text-align: center;
            color: #667085;
        }
    </style>
</head>

<body>
    <header>
        <p class="encabezado">
            UNIVERSIDAD DE HUÁNUCO - LENGUAJE DE PROGRAMACIÓN II
        </p>

        <h1>Sistema de Gestión de Seguridad Privada O</h1>

        <p>Control de usuarios, vigilantes, turnos y puestos de vigilancia</p>
    </header>

    <nav aria-label="Módulos del sistema">
        <?php foreach ($modulos as $clave => $item): ?>
            <a href="?modulo=<?= $h($clave) ?>"
               <?= $clave === $seleccionado ? 'aria-current="page"' : '' ?>>
                <?= $h($item['titulo']) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <main>
        <section>
            <h2><?= $h($modulo['titulo']) ?></h2>

            <?php if (!empty($mensaje)): ?>
                <p class="mensaje"><?= $h($mensaje) ?></p>
            <?php endif; ?>

            <?php if (!empty($errorFormulario)): ?>
                <p class="alerta"><?= $h($errorFormulario) ?></p>
            <?php endif; ?>

            <?php if ($puedeRegistrar): ?>
                <p class="nota">Ingrese los datos del puesto de vigilancia.</p>

                <form method="post" action="?modulo=puestos">
                    <?php foreach ($modulo['campos'] as $campo => $etiqueta): ?>
                        <label>
                            <?= $h($etiqueta) ?>
                            <input
                                type="<?= $campo === 'supervisor_id' ? 'number' : 'text' ?>"
                                name="<?= $h($campo) ?>"
                                placeholder="<?= $h($etiqueta) ?>"
                                <?= $campo === 'supervisor_id' ? 'min="1"' : '' ?>
                                required
                            >
                        </label>
                    <?php endforeach; ?>

                    <input
                        type="hidden"
                        name="token_formulario"
                        value="<?= $h($_SESSION['token_formulario']) ?>"
                    >

                    <button type="submit">Guardar puesto</button>
                </form>

            <?php else: ?>
                <p class="nota">
                    El registro de información de este módulo todavía no está habilitado.
                </p>

                <div class="campos">
                    <?php foreach ($modulo['campos'] as $campo => $etiqueta): ?>
                        <label>
                            <?= $h($etiqueta) ?>
                            <input
                                type="text"
                                name="<?= $h($campo) ?>"
                                placeholder="<?= $h($etiqueta) ?>"
                                disabled
                            >
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section>
            <h2>Registro de <?= $h(strtolower($modulo['titulo'])) ?></h2>

            <?php if ($datos['error'] !== null): ?>
                <p class="alerta"><?= $h($datos['error']) ?></p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($modulo['columnas'] as $col): ?>
                                <th><?= $h(ucwords(str_replace('_', ' ', $col))) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($filas)): ?>
                            <tr>
                                <td colspan="<?= count($modulo['columnas']) ?>" class="vacio">
                                    No hay registros disponibles.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($filas as $fila): ?>
                                <tr>
                                    <?php foreach ($modulo['columnas'] as $col): ?>
                                        <td><?= $h($fila[$col] ?? '—') ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
