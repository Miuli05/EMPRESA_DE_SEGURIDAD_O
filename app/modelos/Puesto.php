
<?php
require_once __DIR__ . '/ModeloBase.php';

// Se está eliminando el duplicado de los métodos repititivos
class Puesto extends ModeloBase {

    public function listar(): array {
        $sql = "SELECT * FROM puestos ORDER BY id DESC";
        $datos = $this->db->query($sql);
        $resultado = $datos->fetchAll();
        $return $resultado;
    }

    // Correción: Se están cerrando las llaves
    public function buscarPorId(int $id): ?array {
        $sql = "SELECT * FROM puestos WHERE id = :id";
        $buscar = $this->db->prepare($sql);
        $buscar->execute(["id" => $id]);

        $dato = $buscar->fetch();
    
    //Devuelve el dato si lo encuentra
        return $dato ?: null;
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
