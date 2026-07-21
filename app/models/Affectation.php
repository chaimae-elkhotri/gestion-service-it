<?php

class Affectation
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
                    a.ID_AFFECTATION_EQUIP AS id_affectation,
                    a.ID_UTILISATEUR AS id_utilisateur,
                    a.ID_EQUIPEMENT_ AS id_equipement,
                    a.DATE_AFFECTATION AS date_affectation,
                    a.DATE_FIN_AFFECTATION AS date_fin_affectation,

                    u.NOM AS nom,
                    u.PRENOM AS prenom,

                    e.NUMERO_SERIE AS numero_serie,
                    e.MARQUE AS marque,
                    e.MODELE AS modele

                FROM affectation_equipement a

                LEFT JOIN utilisateur u
                    ON a.ID_UTILISATEUR = u.ID_UTILISATEUR

                LEFT JOIN equipement e
                    ON a.ID_EQUIPEMENT_ = e.ID_EQUIPEMENT_

                ORDER BY a.ID_AFFECTATION_EQUIP DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO affectation_equipement
                (ID_UTILISATEUR, ID_EQUIPEMENT_, DATE_AFFECTATION, DATE_FIN_AFFECTATION)
                VALUES
                (:id_utilisateur, :id_equipement, :date_affectation, :date_fin_affectation)";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':id_utilisateur' => $data['id_utilisateur'],
            ':id_equipement' => $data['id_equipement'],
            ':date_affectation' => $data['date_affectation'],
            ':date_fin_affectation' => !empty($data['date_fin_affectation']) ? $data['date_fin_affectation'] : null
        ]);

        if ($result) {

            $idAffectation = $this->db->lastInsertId();

            $sqlUpdate = "UPDATE equipement 
                          SET STATUT = 'Affecté'
                          WHERE ID_EQUIPEMENT_ = :id_equipement";

            $stmtUpdate = $this->db->prepare($sqlUpdate);

            $stmtUpdate->execute([
                ':id_equipement' => $data['id_equipement']
            ]);

            return $idAffectation;
        }

        return false;
    }

    public function getById($id)
    {
        $sql = "SELECT
                    ID_AFFECTATION_EQUIP AS id_affectation,
                    ID_UTILISATEUR AS id_utilisateur,
                    ID_EQUIPEMENT_ AS id_equipement,
                    DATE_AFFECTATION AS date_affectation,
                    DATE_FIN_AFFECTATION AS date_fin_affectation
                FROM affectation_equipement
                WHERE ID_AFFECTATION_EQUIP = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $ancienneAffectation = $this->getById($data['id_affectation']);

        $sql = "UPDATE affectation_equipement SET
                    ID_UTILISATEUR = :id_utilisateur,
                    ID_EQUIPEMENT_ = :id_equipement,
                    DATE_AFFECTATION = :date_affectation,
                    DATE_FIN_AFFECTATION = :date_fin_affectation
                WHERE ID_AFFECTATION_EQUIP = :id_affectation";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':id_utilisateur' => $data['id_utilisateur'],
            ':id_equipement' => $data['id_equipement'],
            ':date_affectation' => $data['date_affectation'],
            ':date_fin_affectation' => !empty($data['date_fin_affectation']) ? $data['date_fin_affectation'] : null,
            ':id_affectation' => $data['id_affectation']
        ]);

        if ($result) {

            if (!empty($ancienneAffectation) && $ancienneAffectation['id_equipement'] != $data['id_equipement']) {

                $sqlOld = "UPDATE equipement 
                           SET STATUT = 'Disponible'
                           WHERE ID_EQUIPEMENT_ = :id_equipement";

                $stmtOld = $this->db->prepare($sqlOld);

                $stmtOld->execute([
                    ':id_equipement' => $ancienneAffectation['id_equipement']
                ]);
            }

            $sqlNew = "UPDATE equipement 
                       SET STATUT = 'Affecté'
                       WHERE ID_EQUIPEMENT_ = :id_equipement";

            $stmtNew = $this->db->prepare($sqlNew);

            $stmtNew->execute([
                ':id_equipement' => $data['id_equipement']
            ]);
        }

        return $result;
    }

    public function delete($id)
    {
        $affectation = $this->getById($id);

        $sql = "DELETE FROM affectation_equipement
                WHERE ID_AFFECTATION_EQUIP = :id";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':id' => $id
        ]);

        if ($result && !empty($affectation)) {

            $sqlUpdate = "UPDATE equipement 
                          SET STATUT = 'Disponible'
                          WHERE ID_EQUIPEMENT_ = :id_equipement";

            $stmtUpdate = $this->db->prepare($sqlUpdate);

            $stmtUpdate->execute([
                ':id_equipement' => $affectation['id_equipement']
            ]);
        }

        return $result;
    }

    public function search($search)
    {
        $sql = "SELECT
                    a.ID_AFFECTATION_EQUIP AS id_affectation,
                    a.ID_UTILISATEUR AS id_utilisateur,
                    a.ID_EQUIPEMENT_ AS id_equipement,
                    a.DATE_AFFECTATION AS date_affectation,
                    a.DATE_FIN_AFFECTATION AS date_fin_affectation,

                    u.NOM AS nom,
                    u.PRENOM AS prenom,

                    e.NUMERO_SERIE AS numero_serie,
                    e.MARQUE AS marque,
                    e.MODELE AS modele

                FROM affectation_equipement a

                LEFT JOIN utilisateur u
                    ON a.ID_UTILISATEUR = u.ID_UTILISATEUR

                LEFT JOIN equipement e
                    ON a.ID_EQUIPEMENT_ = e.ID_EQUIPEMENT_

                WHERE u.NOM LIKE :search
                   OR u.PRENOM LIKE :search
                   OR e.NUMERO_SERIE LIKE :search
                   OR e.MARQUE LIKE :search
                   OR e.MODELE LIKE :search
                   OR a.DATE_AFFECTATION LIKE :search
                   OR a.DATE_FIN_AFFECTATION LIKE :search

                ORDER BY a.ID_AFFECTATION_EQUIP DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM affectation_equipement";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}