
<?php
require_once __DIR__ . '/ModeloBase.php';

class Notificacion extends ModeloBase {

    public function listar(): array {

        $sql = "SELECT * FROM notificaciones ORDER BY id DESC";
        $datos = $this->db->query($sql);
        $resultado = $datos->fetchAll();

        return $resultado;
    }

    public function buscarPorId(int $id): ?array {

        $sql = "SELECT * FROM notificaciones WHERE id = :id";
        $buscar = $this->db->prepare($sql);
        $buscar->execute(["id" => $id]);

        $dato = $buscar->fetch();

        if ($dato) {
            return $dato;
        } else {
            return null;
        }
    }

    public function listarPendientes(int $destinatarioId): array {

        $sql = "SELECT * FROM notificaciones
        WHERE destinatario_id = :id AND leida = 0
        ORDER BY fecha_hora DESC";

        $buscar = $this->db->prepare($sql);
        $buscar->execute(["id" => $destinatarioId]);

        $pendientes = $buscar->fetchAll();

        return $pendientes;
    }

    public function marcarLeida(int $id): bool {

        $sql = "UPDATE notificaciones SET leida = 1
        WHERE id = :id AND leida = 0";

        $actualizar = $this->db->prepare($sql);
        $actualizar->execute(["id" => $id]);

        $filas = $actualizar->rowCount();

        if ($filas > 0) {
            return true;
        } else {
            return false;
        }
    }
}
