
<?php
require_once __DIR__ . '/ModeloBase.php';

class Puesto extends ModeloBase {

    public function listar(): array {
        $sql = "SELECT * FROM puestos ORDER BY id DESC";
        $datos = $this->db->query($sql);
        $resultado = $datos->fetchAll();
        return $resultado;
    }

    public function buscarPorId(int $id): ?array {
        $sql = "SELECT * FROM puestos WHERE id = :id";
        $buscar = $this->db->prepare($sql);
        $buscar->execute(["id" => $id]);

        $dato = $buscar->fetch();

        if ($dato) {
            return $dato;
        } else {
            return null;
        }
    }

    public function registrar(string $nombre, string $ubicacion, int $supervisorId): int {

        $sql = "INSERT INTO puestos (nombre, ubicacion, supervisor_id)
        VALUES (:nombre, :ubicacion, :supervisor)";

        $guardar = $this->db->prepare($sql);

        $guardar->execute([
            "nombre" => $nombre,
            "ubicacion" => $ubicacion,
            "supervisor" => $supervisorId
        ]);

        $id = $this->db->lastInsertId();

        return (int)$id;
    }
}
