<?php

class Licence
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
                    l.ID_LICENCE AS id_licence,
                    l.ID_LOGICIEL AS id_logiciel,
                    l.CLE_LICENCE AS cle_licence,
                    l.DATE_DEBUT AS date_debut,
                    l.DATE_FIN AS date_fin,
                    l.NOMBRE_POSTES AS nombre_postes,

                    lo.NOM_LOGICIEL AS nom_logiciel,
                    lo.VERSION AS version,
                    lo.EDITEUR AS editeur

                FROM licence l

                LEFT JOIN logiciel lo
                    ON l.ID_LOGICIEL = lo.ID_LOGICIEL

                ORDER BY l.ID_LICENCE DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO licence
                (ID_LOGICIEL, CLE_LICENCE, DATE_DEBUT, DATE_FIN, NOMBRE_POSTES)
                VALUES
                (:id_logiciel, :cle_licence, :date_debut, :date_fin, :nombre_postes)";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':id_logiciel' => $data['id_logiciel'],
            ':cle_licence' => $data['cle_licence'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':nombre_postes' => $data['nombre_postes']
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function getById($id)
    {
        $sql = "SELECT 
                    ID_LICENCE AS id_licence,
                    ID_LOGICIEL AS id_logiciel,
                    CLE_LICENCE AS cle_licence,
                    DATE_DEBUT AS date_debut,
                    DATE_FIN AS date_fin,
                    NOMBRE_POSTES AS nombre_postes
                FROM licence
                WHERE ID_LICENCE = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $sql = "UPDATE licence SET
                    ID_LOGICIEL = :id_logiciel,
                    CLE_LICENCE = :cle_licence,
                    DATE_DEBUT = :date_debut,
                    DATE_FIN = :date_fin,
                    NOMBRE_POSTES = :nombre_postes
                WHERE ID_LICENCE = :id_licence";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_logiciel' => $data['id_logiciel'],
            ':cle_licence' => $data['cle_licence'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':nombre_postes' => $data['nombre_postes'],
            ':id_licence' => $data['id_licence']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM licence WHERE ID_LICENCE = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM licence";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function countExpirees()
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM licence 
                WHERE DATE_FIN < CURDATE()";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function search($search)
    {
        $sql = "SELECT 
                    l.ID_LICENCE AS id_licence,
                    l.ID_LOGICIEL AS id_logiciel,
                    l.CLE_LICENCE AS cle_licence,
                    l.DATE_DEBUT AS date_debut,
                    l.DATE_FIN AS date_fin,
                    l.NOMBRE_POSTES AS nombre_postes,

                    lo.NOM_LOGICIEL AS nom_logiciel,
                    lo.VERSION AS version,
                    lo.EDITEUR AS editeur

                FROM licence l

                LEFT JOIN logiciel lo
                    ON l.ID_LOGICIEL = lo.ID_LOGICIEL

                WHERE l.CLE_LICENCE LIKE :search
                   OR l.DATE_DEBUT LIKE :search
                   OR l.DATE_FIN LIKE :search
                   OR lo.NOM_LOGICIEL LIKE :search
                   OR lo.EDITEUR LIKE :search
                   OR lo.VERSION LIKE :search

                ORDER BY l.ID_LICENCE DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}