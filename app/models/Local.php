<?php

class Local
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAll()
    {
        $sql = "SELECT 
                    ID_LOCAL AS id_local,
                    NOM_LOCAL AS nom_local,
                    TYPE_LOCAL AS type_local
                FROM `local`
                ORDER BY ID_LOCAL DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO `local`
                (NOM_LOCAL, TYPE_LOCAL)
                VALUES
                (:nom_local, :type_local)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nom_local' => $data['nom_local'],
            ':type_local' => $data['type_local']
        ]);
    }

    public function getById($id)
    {
        $sql = "SELECT 
                    ID_LOCAL AS id_local,
                    NOM_LOCAL AS nom_local,
                    TYPE_LOCAL AS type_local
                FROM `local`
                WHERE ID_LOCAL = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $sql = "UPDATE `local` SET
                    NOM_LOCAL = :nom_local,
                    TYPE_LOCAL = :type_local
                WHERE ID_LOCAL = :id_local";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nom_local' => $data['nom_local'],
            ':type_local' => $data['type_local'],
            ':id_local' => $data['id_local']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM `local`
                WHERE ID_LOCAL = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM `local`";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function search($search)
    {
        $sql = "SELECT 
                    ID_LOCAL AS id_local,
                    NOM_LOCAL AS nom_local,
                    TYPE_LOCAL AS type_local
                FROM `local`
                WHERE NOM_LOCAL LIKE :search
                   OR TYPE_LOCAL LIKE :search
                ORDER BY ID_LOCAL DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}