
<?php
session_start();

require_once __DIR__ . '/app/controladores/ModuloControlador.php';

if (empty($_SESSION['token_formulario'])) {
    $_SESSION['token_formulario'] = bin2hex(random_bytes(32));
}

$modulos = [
    'usuarios' => [
        'titulo' => 'Usuarios',
        'modelo' => 'Usuario',
        'campos' => [
            'nombre' => 'Nombre completo',
            'correo' => 'Correo',
            'rol' => 'Rol'
        ],
        'columnas' => ['id', 'nombre', 'correo', 'rol', 'activo']
    ],
    'vigilantes' => [
        'titulo' => 'Vigilantes',
        'modelo' => 'Vigilante',
        'campos' => [
            'usuario_id' => 'ID de usuario',
            'codigo' => 'Código',
            'estado' => 'Estado'
        ],
        'columnas' => ['id', 'usuario_id', 'codigo', 'estado']
    ],
    'puestos' => [
        'titulo' => 'Puestos de vigilancia',
        'modelo' => 'Puesto',
        'campos' => [
            'nombre' => 'Nombre del puesto',
            'ubicacion' => 'Ubicación',
            'supervisor_id' => 'ID del supervisor'
        ],
        'columnas' => ['id', 'nombre', 'ubicacion', 'supervisor_id', 'activo']
    ],
    'turnos' => [
        'titulo' => 'Turnos',
        'modelo' => 'Turno',
        'campos' => [
            'puesto_id' => 'ID del puesto',
            'fecha_inicio' => 'Fecha y hora de inicio',
            'fecha_fin' => 'Fecha y hora de fin'
        ],
        'columnas' => ['id', 'puesto_id', 'fecha_inicio', 'fecha_fin', 'estado']
    ],
    'asignaciones' => [
        'titulo' => 'Asignaciones',
        'modelo' => 'Asignacion',
        'campos' => [
            'turno_id' => 'ID del turno',
            'vigilante_id' => 'ID del vigilante',
            'asignado_por' => 'ID del coordinador'
        ],
        'columnas' => ['id', 'turno_id', 'vigilante_id', 'fecha_asignacion', 'estado']
    ],
    'incidencias' => [
        'titulo' => 'Incidencias',
        'modelo' => 'Incidencia',
        'campos' => [
            'turno_id' => 'ID del turno',
            'vigilante_id' => 'ID del vigilante',
            'tipo' => 'Tipo',
            'descripcion' => 'Descripción'
        ],
        'columnas' => ['id', 'turno_id', 'vigilante_id', 'tipo', 'descripcion', 'estado']
    ],
    'seguimientos' => [
        'titulo' => 'Seguimientos',
        'modelo' => 'Seguimiento',
        'campos' => [
            'incidencia_id' => 'ID de incidencia',
            'supervisor_id' => 'ID del supervisor',
            'comentario' => 'Comentario'
        ],
        'columnas' => ['id', 'incidencia_id', 'supervisor_id', 'comentario', 'fecha_hora']
    ],
    'notificaciones' => [
        'titulo' => 'Alertas a supervisores',
        'modelo' => 'Notificacion',
        'campos' => [
            'turno_id' => 'ID del turno',
            'destinatario_id' => 'ID del supervisor'
        ],
        'columnas' => ['id', 'turno_id', 'destinatario_id', 'fecha_hora', 'leida']
    ]
];

$seleccionado = $_GET['modulo'] ?? 'turnos';

if (!is_string($seleccionado) || !isset($modulos[$seleccionado])) {
    http_response_code(404);
    $seleccionado = 'turnos';
}

$controlador = new ModuloControlador();
$errorFormulario = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if ($seleccionado !== 'puestos') {
        http_response_code(405);
        $errorFormulario = 'Esta operación no está disponible.';
    } elseif (
        !is_string($_POST['token_formulario'] ?? null) ||
        !hash_equals(
            $_SESSION['token_formulario'],
            $_POST['token_formulario']
        )
    ) {
        http_response_code(403);
        $errorFormulario = 'La solicitud no es válida.';
    } else {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $ubicacion = $_POST['ubicacion'] ?? '';
            $supervisor = $_POST['supervisor_id'] ?? null;

            if (
                !is_string($nombre) ||
                !is_string($ubicacion) ||
                !is_string($supervisor)
            ) {
                throw new InvalidArgumentException('Datos incorrectos.');
            }

            $supervisorId = filter_var(
                $supervisor,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            );

            if ($supervisorId === false) {
                throw new InvalidArgumentException('Ingrese un supervisor válido.');
            }

            $controlador->registrarPuesto(
                trim($nombre),
                trim($ubicacion),
                $supervisorId
            );

            $_SESSION['aviso'] = 'Puesto registrado correctamente.';

            header('Location: ?modulo=puestos', true, 303);
            exit;

        } catch (InvalidArgumentException | DomainException $error) {
            $errorFormulario = $error->getMessage();

        } catch (PDOException $error) {
            error_log($error->getMessage());
            $errorFormulario = 'No se pudo guardar el puesto.';
        }
    }
}

$mensaje = $_SESSION['aviso'] ?? null;
unset($_SESSION['aviso']);

$datos = $controlador->obtenerListado(
    $modulos[$seleccionado]['modelo']
);

require __DIR__ . '/app/vistas/modulo.php';
