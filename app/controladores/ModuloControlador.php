
<?php
class ModuloControlador
{
    public function obtenerListado(string $claseModelo): array
    {
        $permitidos = [
            'Usuario',
            'Vigilante',
            'Puesto',
            'Turno',
            'Asignacion',
            'Incidencia',
            'Seguimiento',
            'Notificacion'
        ];

        if (!in_array($claseModelo, $permitidos, true)) {
            throw new InvalidArgumentException('El módulo no existe.');
        }

        require_once __DIR__ . '/../modelos/' . $claseModelo . '.php';

        try {
            $modelo = new $claseModelo();
            $filas = $modelo->listar();

            return [
                'filas' => $filas,
                'error' => null
            ];

        } catch (PDOException $error) {
            error_log($error->getMessage());

            return [
                'filas' => [],
                'error' => 'No se pudieron cargar los registros. Revisa la conexión con la base de datos.'
            ];
        }
    }

    public function registrarPuesto(string $nombre, string $ubicacion, int $supervisorId): int
    {
        $nombre = trim($nombre);
        $ubicacion = trim($ubicacion);

        if ($nombre === '' || $ubicacion === '' || $supervisorId <= 0) {
            throw new InvalidArgumentException('Complete correctamente los datos del puesto.');
        }

        require_once __DIR__ . '/../modelos/Puesto.php';

        $puesto = new Puesto();

        return $puesto->registrar($nombre, $ubicacion, $supervisorId);
    }

    
public function registrarTurno(int $puestoId, string $inicio, string $fin, ?int $creadoPor = null): int
{
    if ($puestoId <= 0) {
        throw new InvalidArgumentException('Seleccione un puesto válido.');
    }

    require_once __DIR__ . '/../modelos/Turno.php';

    $turno = new Turno();

    return $turno->registrar(
        $puestoId,
        $inicio,
        $fin,
        $creadoPor
    );
}

}
