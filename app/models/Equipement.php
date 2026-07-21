<?php

class Equipement
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
                    e.ID_EQUIPEMENT_ AS id_equipement,
                    e.NUMERO_SERIE AS numero_serie,
                    e.MARQUE AS marque,
                    e.MODELE AS modele,
                    e.DATE_ACHAT AS date_achat,
                    e.STATUT AS statut,
                    e.ID_CATEGORIE AS id_categorie,
                    e.ID_LOCAL AS id_local,
                    c.NOM_CATEGORIE AS nom_categorie,
                    l.NOM_LOCAL AS nom_local
                FROM equipement e
                INNER JOIN categorie c ON e.ID_CATEGORIE = c.ID_CATEGORIE
                INNER JOIN `local` l ON e.ID_LOCAL = l.ID_LOCAL
                ORDER BY e.ID_EQUIPEMENT_ DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO equipement
                (NUMERO_SERIE, MARQUE, MODELE, DATE_ACHAT, STATUT, ID_CATEGORIE, ID_LOCAL)
                VALUES
                (:numero_serie, :marque, :modele, :date_achat, :statut, :id_categorie, :id_local)";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':numero_serie' => $data['numero_serie'],
            ':marque' => $data['marque'],
            ':modele' => $data['modele'],
            ':date_achat' => $data['date_achat'],
            ':statut' => $data['statut'],
            ':id_categorie' => $data['id_categorie'],
            ':id_local' => $data['id_local']
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function getById($id)
    {
        $sql = "SELECT 
                    ID_EQUIPEMENT_ AS id_equipement,
                    NUMERO_SERIE AS numero_serie,
                    MARQUE AS marque,
                    MODELE AS modele,
                    DATE_ACHAT AS date_achat,
                    STATUT AS statut,
                    ID_CATEGORIE AS id_categorie,
                    ID_LOCAL AS id_local
                FROM equipement
                WHERE ID_EQUIPEMENT_ = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $sql = "UPDATE equipement SET
                    NUMERO_SERIE = :numero_serie,
                    MARQUE = :marque,
                    MODELE = :modele,
                    DATE_ACHAT = :date_achat,
                    STATUT = :statut,
                    ID_CATEGORIE = :id_categorie,
                    ID_LOCAL = :id_local
                WHERE ID_EQUIPEMENT_ = :id_equipement";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':numero_serie' => $data['numero_serie'],
            ':marque' => $data['marque'],
            ':modele' => $data['modele'],
            ':date_achat' => $data['date_achat'],
            ':statut' => $data['statut'],
            ':id_categorie' => $data['id_categorie'],
            ':id_local' => $data['id_local'],
            ':id_equipement' => $data['id_equipement']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM equipement
                WHERE ID_EQUIPEMENT_ = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM equipement";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function search($search)
    {
        $sql = "SELECT 
                    e.ID_EQUIPEMENT_ AS id_equipement,
                    e.NUMERO_SERIE AS numero_serie,
                    e.MARQUE AS marque,
                    e.MODELE AS modele,
                    e.DATE_ACHAT AS date_achat,
                    e.STATUT AS statut,
                    e.ID_CATEGORIE AS id_categorie,
                    e.ID_LOCAL AS id_local,
                    c.NOM_CATEGORIE AS nom_categorie,
                    l.NOM_LOCAL AS nom_local
                FROM equipement e
                INNER JOIN categorie c ON e.ID_CATEGORIE = c.ID_CATEGORIE
                INNER JOIN `local` l ON e.ID_LOCAL = l.ID_LOCAL
                WHERE e.NUMERO_SERIE LIKE :search
                   OR e.MARQUE LIKE :search
                   OR e.MODELE LIKE :search
                   OR c.NOM_CATEGORIE LIKE :search
                   OR l.NOM_LOCAL LIKE :search
                ORDER BY e.ID_EQUIPEMENT_ DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}