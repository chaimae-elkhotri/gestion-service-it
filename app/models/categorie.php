<?php

class Categorie
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
                    ID_CATEGORIE AS id_categorie,
                    NOM_CATEGORIE AS nom_categorie
                FROM categorie
                ORDER BY NOM_CATEGORIE ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM categorie";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getById($id)
{
    $sql = "SELECT 
                ID_CATEGORIE AS id_categorie,
                NOM_CATEGORIE AS nom_categorie
            FROM categorie
            WHERE ID_CATEGORIE = :id
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}